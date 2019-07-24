<?php 
include("baza.php");
include("header.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<div>
<?php 

	$connect=connectDB();

	$modUsers="SELECT korisnik_id,ime,prezime
				FROM korisnik
                LEFT OUTER JOIN tvrtka
        		ON tvrtka.moderator_id=korisnik.korisnik_id 
				WHERE korisnik.tip_id = 1 AND tvrtka.moderator_id IS null ";
	$result=queryDB($connect,$modUsers);

?>

		<h2> Unesi novu tvrtku </h2>
		<form action=" " method="POST" name="unos">
			<label> Izaberite moderatora: </label> <br>
			<?php 
			if($result)
			{
				while($row = mysqli_fetch_array($result))
				{
					$razmak =" ";
					$modID=$row['korisnik_id'];
					$ime=$row['ime'];
					$prezime=$row['prezime'];
					echo '<input type="radio" name="slobodniMod" value="'.$modID.'" />'.$ime .$razmak .$prezime; 
				}
			}
			elseif($result == 0)
			{
				echo "Nema slobodnih moderatora za kreiranje tvrtke!";
			}
			?> <br>
			<label> Naziv tvrtke: </label>
			<input type="text" name="nazivTvrtke" required="required"> <br>
			<label> Opis tvrtke: </label>
			<input type="text" name="opis" required="required"> <br>
			<label> Broj zaposlenika: </label>
			<input type="text" name="brojZaposlenika" required="required"> <br>
			<input type="submit" name="unesi" value="Unesi tvrtku">
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


$showFirm="SELECT tvrtka_id,naziv
				FROM tvrtka";
$result=queryDB($connect,$showFirm);

if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis tvrtki</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Naziv </th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($tvrtka_id,$naziv)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='azuriranjeTvrtke.php?id=$tvrtka_id'>".$naziv."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}


$answerRequest = "SELECT tvrtka.naziv,tvrtka.tvrtka_id,tvrtka.zahtjev
					FROM tvrtka 
					WHERE tvrtka.zahtjev = 1";
$resultA=queryDB($connect,$answerRequest);

	if(mysqli_num_rows($resultA) > 0)
	{
		echo "<h2> Popis tvrtki sa zahtjevom</h2>";
		echo "<table border ='1'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Naziv </th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

			while(list($naziv,$tvrtka_id)=mysqli_fetch_row($resultA))
				{
					echo "<tr>";
					echo "<td>".$naziv."</td>";
					echo "<td>".$tvrtka_id."</td>";
					echo "<td>"	?> 	<form action="" method="POST">
										<input type="hidden" name="id" value="<?php echo "$tvrtka_id"; ?>">
										<input type="submit" name="odobriZahtjev" value="Odobri zahtjev"> 
										</form>
								<?php "</td>";
					echo "</tr>";
				}
		echo "</tbody>";
		echo "</table>";
	}
	else
	{
		echo "<h2> Nema zahtijeva za povećanjem odgovora</h2>";	
	}
	if(isset($_POST['odobriZahtjev']))
				{
					$firmId = $_POST['id'];
					$updateAnswers = "UPDATE tvrtka 
									   SET zahtjev = '0', preostaliOdgovori=preostaliOdgovori + 10
									   WHERE tvrtka.tvrtka_id='$firmId'";
					$result=queryDB($connect,$updateAnswers);
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
{
	echo "<h2> Statistika broja pitanja i odgovora </h2>";
	echo "<table border = '1'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th> Tvrtka </th>";
	echo "<th> Broj pitanja </th>";
	echo "<th> Broj odgovora </th>";
	echo "</thead>";
	echo "<tbody>";

		while(list($naziv,$brojPitanja,$brojOdgovora)= mysqli_fetch_row($questionAnswerResult))
		{
			echo "<tr>";
			echo "<td>".$naziv."</td>";
			echo "<td>".$brojPitanja."</td>";
			echo "<td>".$brojOdgovora."</td>";
			echo "</tr>";

		}
		echo "</tbody>";
		echo "</table>";
}

$employeeStats ="SELECT naziv, ime, prezime, COUNT(*) AS broj_odgovora FROM tvrtka, korisnik, zaposlenik, odgovor
				WHERE korisnik.korisnik_id = zaposlenik.korisnik_id AND zaposlenik.tvrtka_id = tvrtka.tvrtka_id
				AND zaposlenik.zaposlenik_id = odgovor.zaposlenik_id
				GROUP BY korisnik.korisnik_id order by broj_odgovora desc";
$employeeStatsResult = queryDB($connect,$employeeStats);

if(mysqli_num_rows($employeeStatsResult) >= 0)
{
	echo "<h2> Statistika broja odgovora po zaposleniku </h2>";
	echo "<table border = '1'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th> Tvrtka </th>";
	echo "<th> Ime </th>";
	echo "<th> Prezime </th>";
	echo "<th> Broj odgoora </th>";
	echo "</thead>";
	echo "<tbody>";

	while(list($naziv,$ime,$prezime,$broj_odgovora)= mysqli_fetch_row($employeeStatsResult))
		{
			echo "<tr>";
			echo "<td>".$naziv."</td>";
			echo "<td>".$ime."</td>";
			echo "<td>".$prezime."</td>";
			echo "<td>".$broj_odgovora."</td>";
			echo "</tr>";

		}
		echo "</tbody>";
		echo "</table>";
}


disconnectDB($connect);
?>
</body>
</html>