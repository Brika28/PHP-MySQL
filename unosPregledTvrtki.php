<?php 
include("baza.php");
include("header.php");

ob_start();

	$connect=connectDB();

	$showFirm="SELECT tvrtka_id,naziv
				FROM tvrtka";
$result=queryDB($connect,$showFirm);

if(mysqli_num_rows($result) >= 0)
		{ ?>

			<table class="table table-bordered">
			<thead class="thead-dark">
			<tr>
			<th scope="col">Popis tvrtki</th>
			</tr>
			</thead>
			<tbody>

	<?php	while(list($tvrtka_id,$naziv)=mysqli_fetch_row($result))
				{ ?>
					<tr>
					<td><a href= <?php echo "azuriranjeTvrtke.php?id=$tvrtka_id" ?> > <?php echo"$naziv"; ?></a></td>
					</tr>
	<?php		} ?>
				</tbody>
				</table>
	<?php } 


	$modUsers="SELECT korisnik_id,ime,prezime
				FROM korisnik
                LEFT OUTER JOIN tvrtka
        		ON tvrtka.moderator_id=korisnik.korisnik_id 
				WHERE korisnik.tip_id = 1 AND tvrtka.moderator_id IS null";
	$resultUser=queryDB($connect,$modUsers);

?>
	<div class="container" id="loginForm">
		<h2 class="h2"> Unos nove tvrtke </h2>
		<form action=" " method="POST" name="unos">
			<label> Izaberite moderatora: </label> 
			<?php 
			if(mysqli_num_rows($resultUser) <= 0) 
			{ ?>
				<h6> Nema slobodnih moderatora za kreiranje tvrtke! </h6>
	<?php	}
			else
			{ 
			    while($row = mysqli_fetch_array($resultUser))
				{ 
					$razmak =" ";
					$modID=$row['korisnik_id'];
					$ime=$row['ime'];
					$prezime=$row['prezime'];
					?>
					<input type="radio" name="slobodniMod" required="required" value = <?php echo "'.$modID.'"; ?>/> <?php echo "$ime $razmak $prezime"; ?>
		<?php	}
		} ?> 
		<div class="form-group">
			<label> Naziv tvrtke: </label>
			<input type="text" name="nazivTvrtke" required="required" class="form-control">
		</div>
		<div class="form-group">
			<label> Opis tvrtke: </label>
			<input type="text" name="opis" required="required" class="form-control">
		</div>
		<div class="form-group">
			<label> Broj zaposlenika: </label>
			<input type="text" name="brojZaposlenika" required="required" class="form-control">
		</div>
			<button type="submit" name="unesi" class="btn btn-primary"> Unesi tvrtku! </button>
		</form>
	</div>
<?php  
if(isset($_POST['unesi']))
		{
			$modTvrtke=$_POST['slobodniMod'];
			$naziv=$_POST['nazivTvrtke'];
			$opis=$_POST['opis'];
			$brojZaposlenika=$_POST['brojZaposlenika'];

			$newFirm= "INSERT INTO tvrtka
							(moderator_id,naziv,opis,broj_zaposlenika,preostaliOdgovori,zahtjev)
		         			VALUES ('$modTvrtke','$naziv','$opis','$brojZaposlenika',0,0)";

			$result= queryDB($connect,$newFirm);
			header("location:unosPregledTvrtki.php");
		}



$questionAnswerNumber = "SELECT 
    				tvrtka.naziv,
    				COUNT(DISTINCT pitanje.pitanje_id) as brojPitanja,
    				COUNT(odgovor.odgovor_id) as brojOdgovora
					FROM tvrtka
					LEFT JOIN pitanje ON pitanje.tvrtka_id = tvrtka.tvrtka_id
					LEFT JOIN odgovor ON odgovor.pitanje_id = pitanje.pitanje_id
					GROUP BY tvrtka.naziv
					ORDER BY tvrtka.tvrtka_id";
$questionAnswerResult = queryDB($connect,$questionAnswerNumber);

if(mysqli_num_rows($questionAnswerResult) >= 0)
{ ?>
	<div class="container">
		<h2 class="h2"> Statistika broja odgovora </h2>
	<table class="table table-bordered">
	<thead class="thead-dark">
	<tr>
	<th> Tvrtka </th>
	<th> Broj pitanja </th>
	<th> Broj odgovora </th>
	</tr>
	</thead>
	<tbody>

<?php	while(list($naziv,$brojPitanja,$brojOdgovora)= mysqli_fetch_row($questionAnswerResult))
		{ ?>
			<tr>
			<td> <?php echo "$naziv"; ?> </td>
			<td> <?php echo "$brojPitanja"; ?> </td>
			<td> <?php echo "$brojOdgovora"; ?> </td>
			</tr>

<?php 	} ?>
		</tbody>
		</table>
		</div>
<?php } 

$employeeStats ="SELECT naziv, ime, prezime, COUNT(*) AS broj_odgovora FROM tvrtka, korisnik, zaposlenik, odgovor
				WHERE korisnik.korisnik_id = zaposlenik.korisnik_id AND zaposlenik.tvrtka_id = tvrtka.tvrtka_id
				AND zaposlenik.zaposlenik_id = odgovor.zaposlenik_id
				GROUP BY korisnik.korisnik_id order by broj_odgovora desc";
$employeeStatsResult = queryDB($connect,$employeeStats);

if(mysqli_num_rows($employeeStatsResult) >= 0)
{ ?>
	<div class="container">
		<h2 class="h2"> Statistika zaposlenika </h2>
	<table class="table table-bordered">
	<thead class="thead-dark">
	<tr>
	<th> Tvrtka </th>
	<th> Ime </th>
	<th> Prezime </th>
	<th> Broj odgovora </th>
	</thead>
	<tbody>

<?php	while(list($naziv,$ime,$prezime,$broj_odgovora)= mysqli_fetch_row($employeeStatsResult))
		{ ?>
			<tr>
			<td> <?php echo"$naziv"; ?> </td>
			<td> <?php echo"$ime"; ?> </td>
			<td> <?php echo"$prezime"; ?> </td>
			<td> <?php echo"$broj_odgovora"; ?> </td>
			</tr>

<?php	} ?>
		</tbody>
		</table>
	</div>
<?php } 
$answerRequest = "SELECT tvrtka.naziv,tvrtka.tvrtka_id,tvrtka.zahtjev
					FROM tvrtka 
					WHERE tvrtka.zahtjev = 1";
$resultA=queryDB($connect,$answerRequest);

	if(mysqli_num_rows($resultA) > 0)
	{ ?>
		<div class="container">
		<h2 class="h2"> Popis tvrtki sa zahtjevom</h2>
		<table class="table table-bordered">
		<thead class="thead-dark">
		<tr>
		<th>Naziv </th>
		<th>Id tvrtke </th>
		<th>Odobrenje zahtjeva </th>
		</tr>
		</thead>
		<tbody>

<?php	while(list($naziv,$tvrtka_id)=mysqli_fetch_row($resultA))
				{ ?>
					<tr>
					<td> <?php echo "$naziv"; ?> </td>
					<td> <?php echo "$tvrtka_id"; ?> </td>
					<td>	
					<form action="" method="POST">
										<input type="hidden" name="id" value="<?php echo "$tvrtka_id"; ?>">
										<input type="submit" name="odobriZahtjev" value="Odobri zahtjev"> 
										</form>
								</td>
					</tr>
			<?php 	} ?>
		</tbody>
		</table>
	</div>
<?php 	}
	else
		{ ?>
		<h2 class="h2"> Nema zahtijeva za povećanjem odgovora</h2>	
<?php	}
	if(isset($_POST['odobriZahtjev']))
				{
					$firmId = $_POST['id'];
					$updateAnswers = "UPDATE tvrtka 
									   SET zahtjev = '0', preostaliOdgovori=preostaliOdgovori + 10
									   WHERE tvrtka.tvrtka_id='$firmId'";
					$result=queryDB($connect,$updateAnswers);
					header("location:unosPregledTvrtki.php");

					ob_end_flush();
				}

disconnectDB($connect);
include("footer.php");
?>
