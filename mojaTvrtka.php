<?php 
include("header.php");
include("baza.php"); 

$activeUserId=$_SESSION['activeUserId'];

if (isset($_GET['activeUserId'])) 
{              
	$_SESSION['activeUserId'] = $_GET['activeUserId']; 
}

$connect=connectDB();

if($_SESSION['activeUserType']==2)
{
	$connect = connectDB();
	$query ="SELECT pitanje.pitanje_id,pitanje.naslov
				FROM pitanje 
        		INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       			INNER JOIN zaposlenik ON zaposlenik.tvrtka_id = pitanje.tvrtka_id
       			WHERE korisnik_id = '$activeUserId'";

	$result=queryDB($connect,$query);
	?>

    				<?php if(mysqli_num_rows($result) > 0)
							{  ?>
	<div class="container">		
				<div class="table-responsive">
					<table class="table table-bordered">
  						<thead class="thead-dark">
  							<tr>
  								<th scope="col-md"> Naslov pitanja </th>
							</tr>
						</thead>
							<tbody>

								<?php while(list($pitanjeId,$naslov)=mysqli_fetch_row($result))
												{ ?>
												<tr>
													<td class="col-md"><a  href=<?php echo "detaljiPitanja.php?id=$pitanjeId" ?>><?php echo "$naslov"; ?></a></td>
												</tr>
											<?php } ?>
							</tbody>
					</table>
					  <?php }
							elseif(mysqli_num_rows($result) == 0)
							  { ?>
								<div class="alert alert-dark" role="alert">
  									<p> Nema postavljenih pitanja </p>
								</div>
						<?php } ?>
					</div>
				</div>
<?php
}  
else if($_SESSION['activeUserType']==1)
{
	$connect=connectDB();
	$query="SELECT pitanje.pitanje_id,pitanje.naslov, tvrtka.moderator_id
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       	WHERE tvrtka.moderator_id = '$activeUserId'";


	$result=queryDB($connect,$query);
	?>

	<?php if(mysqli_num_rows($result) > 0) 
		{ ?>
			<div class="container">
			<div class="table-responsive">
			<table class="table table-bordered">
  			<thead class="thead-dark">
  				<tr>
					<th class="col"> Naslov pitanja </th>
				</tr>
			</thead>
			<tbody>

				<?php while(list($pitanjeId,$naslov)=mysqli_fetch_row($result))
				{ ?>
					<tr>
					<td><a href=<?php echo"detaljiPitanja.php?id=$pitanjeId" ?>><?php echo"$naslov" ?></a></td>
					</tr>
				<?php } ?>
				</tbody>
				</table>
	<?php	}
		elseif(mysqli_num_rows($result) == 0)
		{ ?>
			<div class="alert alert-dark" role="alert">
  				 Nema postavljenih pitanja!
			</div>
		<?php } ?>
	</div>
</div>

<?php
	$employeesList="SELECT korisnik.ime,korisnik.prezime,korisnik.korisnik_id
						FROM zaposlenik
						INNER JOIN korisnik ON korisnik.korisnik_id = zaposlenik.korisnik_id
						INNER JOIN tvrtka ON tvrtka.tvrtka_id=zaposlenik.tvrtka_id
						WHERE tvrtka.moderator_id = '$activeUserId'";
	$employeesResult=queryDB($connect,$employeesList);
	?>

<?php  if(mysqli_num_rows($employeesResult) > 0)
		{ ?>
<div class="container">
			<div class="table-responsive">
			<table class="table table-bordered">
  			<thead class="thead-dark">
  				<tr>
					<th>Zaposlenici</th>
	            </tr>
			</thead>
			<tbody>
			<?php	while(list($ime,$prezime)=mysqli_fetch_row($employeesResult))
					{ ?>
						<tr>
						<td><?php echo"$ime $prezime"; ?></a></td>
						</tr>
			<?php	} ?>
					</tbody>
					</table>
<?php	}
	elseif(mysqli_num_rows($employeesResult) == 0)
		{ ?>
			<div class="alert alert-dark" role="alert">
  				 Nema zaposlenih korisnika!
			</div>
		<?php } ?>
	</div>
</div>
<?php
	$freeEmployees ="SELECT korisnik.ime,korisnik.prezime,korisnik.korisnik_id
					FROM korisnik
					LEFT OUTER JOIN zaposlenik ON zaposlenik.korisnik_id=korisnik.korisnik_id
					LEFT OUTER JOIN tvrtka ON korisnik.korisnik_id = tvrtka.moderator_id
					WHERE zaposlenik.korisnik_id IS NULL AND tvrtka.moderator_id IS NULL AND korisnik.korisnik_id != 1 AND korisnik.tip_id != 1";

	$result=queryDB($connect,$freeEmployees); ?>

	<?php if(mysqli_num_rows($result) >= 0)
		{ ?>
<div class="container">
	<div class="table-responsive">
		<table class="table table-bordered">
  			<thead class="thead-dark">
  				<tr>
					<th>Slobodni korisnici za zaposlenje (kliknuti ime!) </th>
				</tr>
			</thead>
			<tbody>

				<?php while(list($ime,$prezime,$korisnikId)=mysqli_fetch_row($result))
				{ ?>
					<tr>
					<td><a href=<?php echo"zaposliMe.php?uid=$korisnikId" ?> ><?php echo"$ime $prezime"; ?></a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php	} ?> 
	</div>
</div>

<?php	
	$requestStatus = "SELECT tvrtka.zahtjev
						FROM tvrtka 
						WHERE tvrtka.tvrtka_id =".$_SESSION['tvrtkaId'];
	$resultStatus = queryDB($connect,$requestStatus);

	if($resultStatus)
	{
		if(mysqli_num_rows($resultStatus) != 0)
		{
			list($zahtjev)=mysqli_fetch_array($resultStatus);
			$_SESSION['zahtjev']=$zahtjev;
		} ?>
	<div class="container">
		<div class="table-responsive">
			<table class="table table-bordered">
  				<thead class="thead-dark">
  					<tr> <th> Zahtjev za dodatnim odgovorima </th> </tr>
  				</thead>
  					<tbody>
  						<tr>
  							<td id="buttonZahtjev">
								 <form action ='mojaTvrtka.php' method = 'POST'>
									<div class="form-group">
								<button id="odgovoriButton" type ='submit' name ='posaljiZahtjev' class="btn btn-primary">Pošalji zahtjev za odgovorima </button>
							</div>
						</form> 
					</td>
				</tr>
					</tbody>
			</table>
		</div>
	</div>

<?php			if(isset($_POST['posaljiZahtjev']))
			{
				if($_SESSION['zahtjev'] == 0)
				{
				$updateZahtjev = "UPDATE tvrtka
								set zahtjev = 1
								WHERE tvrtka.tvrtka_id =".$_SESSION ['tvrtkaId'];

				$resultUpdate = queryDB ($connect,$updateZahtjev); ?>
				<div class="alert alert-success" role="alert">
 				 Zahtjev je uspiješno prosljeđen
				</div>	
		<?php	}
				else
				{ ?>
					<div class="alert alert-warning" role="alert">
 				 	Zahtjev je zatražen, obrada u tijeku!
					</div>	
		<?php	}
				
			}
	}  ?>
<div class="container">
<div class="table-responsive">
	<table class="table table-bordered">
  				<thead class="thead-dark">
  					<tr> 
  						<th> Statistika po datumu </th> 
  					</tr>
  				</thead>
  					<tbody>
  					<tr>	
						<th>
							<form action="mojaTvrtka.php" method="POST">
								<div class="form-group">
							    	<label> OD: </label>
										<input input type="text" name="vrijeme" placeholder="DD-MM-GGGG" >
								</div>
							    <div class="form-group">
							   		<label>DO:</label>
								<input input type="text" name="vrijeme1" placeholder="DD-MM-GGGG">
								</div>
								<button type='submit' name='postavi' class="btn btn-primary"> Zatraži rezultat </button>
							</form>
						</th>
					</tr>
				</tbody>
			</table>
</div>
</div>
<?php
	if(isset($_POST['vrijeme']))
    {
    	$postavljeno = date_create($_POST['vrijeme']);
    }
    if(isset($_POST['vrijeme1']))
    {
    	$postavljeno1 = date_create($_POST['vrijeme1']);	
    }

    if(isset($_POST['postavi']))
		{
			$format = date_format($postavljeno,"Y-m-d H:i:s");
			$format1 = date_format($postavljeno1,"Y-m-d H:i:s");
			$connect=connectDB();
			$statsQuery ="SELECT ime, prezime, COUNT(*) AS broj_odgovora FROM korisnik, zaposlenik, odgovor
								WHERE korisnik.korisnik_id = zaposlenik.korisnik_id AND zaposlenik.zaposlenik_id = odgovor.zaposlenik_id
								AND zaposlenik.tvrtka_id ='{$_SESSION['tvrtkaId']}' AND odgovor.datum_vrijeme_odgovora BETWEEN 
								'{$format}' AND '{$format1}' GROUP BY korisnicko_ime";
								
				$statsResult = queryDB($connect,$statsQuery);

				if(mysqli_num_rows($statsResult) > 0)
				{ ?>
	<div class="container">
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr> 
						<th> Broj odgovora </th>
						<th> Ime i prezime </th>
					</tr>
				</thead>
					<tbody>

					<?php	while(list($ime,$prezime,$broj_odgovora)=mysqli_fetch_row($statsResult))
						{ ?>
							<tr>
							<td><?php echo "$ime $prezime"; ?></a></td>
							<td><?php echo "$broj_odgovora"; ?></td>
							</tr>
				<?php	} ?>
					</tbody>
			</table>
				<?php	}
					else 
					{ ?>
						<div class="alert alert-warning" role="alert">
 				 		Nema rezultata za traženo razdoblje!
						</div>	
				<?php	}
		}

}
		
disconnectDB($connect);
include("footer.php");
?>
