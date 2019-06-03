<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php
include("header.php");
include("baza.php");

$activeUserId=$_SESSION['activeUserId'];

if (isset($_GET['activeUserId'])) 
{              
	$_SESSION['activeUserId'] = $_GET['activeUserId']; 
}

$connect=connectDB();
$checkEmployee="SELECT zaposlenik.korisnik_id, korisnik.korisnik_id, korisnik.ime
					FROM zaposlenik
					INNER JOIN korisnik ON zaposlenik.korisnik_id = korisnik.korisnik_id
					WHERE zaposlenik.korisnik_id = ".$_SESSION['activeUserId'];

$resultEmployee=queryDb($connect,$checkEmployee);

if($resultEmployee)
{
	if(mysqli_num_rows($resultEmployee) !=0)
	{
		list($employed) = mysqli_fetch_array($resultEmployee);
		$_SESSION['employed']=$employed;
	}

}


if($_SESSION['activeUserType']==2)
{
	$connect = connectDB();
	$query ="SELECT pitanje.pitanje_id,pitanje.naslov
				FROM pitanje 
        		INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       			INNER JOIN zaposlenik ON zaposlenik.tvrtka_id = pitanje.tvrtka_id
       			WHERE korisnik_id = '$activeUserId'";

	$result=queryDB($connect,$query);


	if(mysqli_num_rows($result) > 0)
		{

			echo "<h2> Popis pitanja</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Naslov</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($pitanjeId,$naslov)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='detaljiPitanja.php?id=$pitanjeId'>".$naslov."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}
		elseif(mysqli_num_rows($result) == 0)
		{
			echo "Nema postavljenih pitanja!";
		}

}
else if($_SESSION['activeUserType']==1)
{
	$connect=connectDB();
	$query="SELECT pitanje.pitanje_id,pitanje.naslov, tvrtka.moderator_id
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       	WHERE tvrtka.moderator_id = '$activeUserId'";


	$result=queryDB($connect,$query);

	echo "<h2> Popis pitanja</h2>";
	if(mysqli_num_rows($result) > 0)
		{

			
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Naslov</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($pitanjeId,$naslov)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='detaljiPitanja.php?id=$pitanjeId'>".$naslov."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}
		elseif(mysqli_num_rows($result) == 0)
		{
			echo "Nema postavljenih pitanja!";
		}

	$employeesList="SELECT korisnik.ime,korisnik.prezime,korisnik.korisnik_id
						FROM zaposlenik
						INNER JOIN korisnik ON korisnik.korisnik_id = zaposlenik.korisnik_id
						INNER JOIN tvrtka ON tvrtka.tvrtka_id=zaposlenik.tvrtka_id
						WHERE tvrtka.moderator_id = '$activeUserId'";
	$employeesResult=queryDB($connect,$employeesList);

	
	echo "<h2> Popis zaposlenika</h2>";
	if(mysqli_num_rows($employeesResult) > 0)
		{
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Zaposlenik</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
				while(list($ime,$prezime)=mysqli_fetch_row($employeesResult))
					{
						echo "<tr>";
						echo "<td>".$ime." ".$prezime."</a></td>";
						echo "</tr>";
					}
					echo "</tbody>";
					echo "</table>";
		}
		elseif(mysqli_num_rows($employeesResult) == 0)
		{
			echo "Nemate zaposlenih korisnika!";
		}
	$freeEmployees ="SELECT korisnik.ime,korisnik.prezime,korisnik.korisnik_id
					FROM korisnik
					LEFT OUTER JOIN zaposlenik ON zaposlenik.korisnik_id=korisnik.korisnik_id
					LEFT OUTER JOIN tvrtka ON korisnik.korisnik_id = tvrtka.moderator_id
					WHERE zaposlenik.korisnik_id IS NULL AND tvrtka.moderator_id IS NULL AND korisnik.korisnik_id != 1 AND korisnik.tip_id != 1";

	$result=queryDB($connect,$freeEmployees);

	if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis slobodnih korisnika</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Ime i prezime </th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($ime,$prezime,$korisnikId)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='zaposliMe.php?uid=$korisnikId'>".$ime." ".$prezime."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}

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
		}

			echo "<h2> Zahtjev za dodatnim odgovorima </h2>";
			echo "<form action ='mojaTvrtka.php' method = 'POST'> ";
			echo "<input type = 'submit' name = 'posaljiZahtjev' value='Pošalji zahtjev za odgovorima'> ";
			echo "</form>";

			if(isset($_POST['posaljiZahtjev']))
			{
				if($_SESSION['zahtjev'] == 0)
				{
				$updateZahtjev = "UPDATE tvrtka
								set zahtjev = 1
								WHERE tvrtka.tvrtka_id =".$_SESSION ['tvrtkaId'];

				$resultUpdate = queryDB ($connect,$updateZahtjev);
				echo "Zahtjev uspiješno prosljeđen!";		
				}
				else
				{
					echo "Zahtjev je zatražen, obrada u tijeku!";
				}
				
			}
	}
		echo "<h2> Statistika po zaposleniku </h2>";

	?>
	<form action="mojaTvrtka.php" method="POST">
		<?php echo "OD"; ?>
		<input input type="text" name="vrijeme" placeholder="YYYY-MM-DD" required 
			pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" >
		<?php echo "DO"; ?>
		<input input type="text" name="vrijeme1" placeholder="YYYY-MM-DD" required 
			pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))">
		<input type='submit' name='postavi' value="Zatraži rezultatz!">
	</form>
<?php
	if(isset($_POST['vrijeme']))
    {
    	$postavljeno = $_POST['vrijeme'];
    }
    if(isset($_POST['vrijeme1']))
    {
    	$postavljeno1 = $_POST['vrijeme1'];	
    }

    if(isset($_POST['postavi']))
		{
			$connect=connectDB();
			$statsQuery ="SELECT ime, prezime, COUNT(*) AS broj_odgovora FROM korisnik, zaposlenik, odgovor
								WHERE korisnik.korisnik_id = zaposlenik.korisnik_id AND zaposlenik.zaposlenik_id = odgovor.zaposlenik_id
								AND zaposlenik.tvrtka_id ='{$_SESSION['tvrtkaId']}' AND odgovor.datum_vrijeme_odgovora BETWEEN 
								'{$postavljeno}' AND '{$postavljeno1}' GROUP BY korisnicko_ime";
								
				$statsResult = queryDB($connect,$statsQuery);

				if(mysqli_num_rows($statsResult) > 0)
				{

			echo "<h2> Broj odgovora </h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Ime i prezime </th>";
			echo "<th>Odgovori </th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

					while(list($ime,$prezime,$broj_odgovora)=mysqli_fetch_row($statsResult))
					{
						echo "<tr>";
						echo "<td>".$ime." ".$prezime."</a></td>";
						echo "<td>".$broj_odgovora."</td>";
						echo "</tr>";
					}
					echo "</tbody>";
					echo "</table>";


					}
					else 
					{
						echo"Nema rezultata za traženo razdoblje!";
					}
		}

}
		
disconnectDB($connect);
	
?>

</body>
</html>
