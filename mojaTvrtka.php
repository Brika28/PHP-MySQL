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


	if(mysqli_num_rows($result) >= 0)
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
disconnectDB($connect);
}
else if($_SESSION['activeUserType']==1)
{
	$connect=connectDB();
	$query="SELECT pitanje.pitanje_id,pitanje.naslov, tvrtka.moderator_id
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       	WHERE tvrtka.moderator_id = '$activeUserId'";


	$result=queryDB($connect,$query);


	if(mysqli_num_rows($result) >= 0)
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
	$employeesList="SELECT korisnik.ime,korisnik.prezime,korisnik.korisnik_id
						FROM zaposlenik
						INNER JOIN korisnik ON korisnik.korisnik_id = zaposlenik.korisnik_id
						INNER JOIN tvrtka ON tvrtka.tvrtka_id=zaposlenik.tvrtka_id
						WHERE tvrtka.moderator_id = '$activeUserId'";
	$employeesResult=queryDB($connect,$employeesList);

	if(mysqli_num_rows($employeesResult) >= 0)
		{
			echo "<h2> Popis zaposlenika</h2>";
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
	$freeEmployees ="SELECT korisnik.ime,korisnik.prezime,korisnik.korisnik_id
					FROM korisnik
					LEFT OUTER JOIN zaposlenik ON zaposlenik.korisnik_id=korisnik.korisnik_id
					LEFT OUTER JOIN tvrtka ON korisnik.korisnik_id = tvrtka.moderator_id
					WHERE zaposlenik.korisnik_id IS NULL AND tvrtka.moderator_id IS NULL AND korisnik.korisnik_id != 1";

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

		$getTime ="SELECT datum_vrijeme_pitanja 
							FROM pitanje 
							LEFT JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id 
							WHERE tvrtka.moderator_id =".$_SESSION['activeUserId'];
			$resultTime = queryDB($connect,$getTime);
		

?>
			
<form action="mojaTvrtka.php" method="POST">


	<select name="dropdown">
<?php	
	if($resultTime)
		{

			while($row = mysqli_fetch_array($resultTime))
    		{  ?>    
				<option value="<?php echo $row['datum_vrijeme_pitanja'];?>">
					<?php echo $row['datum_vrijeme_pitanja']; ?>
				</option>
			<?php } ?>
		}
?>
	</select>
<?php
			echo "DO";

			$getTime1 ="SELECT datum_vrijeme_pitanja 
							FROM pitanje 
							LEFT JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id 
							WHERE tvrtka.moderator_id =".$_SESSION['activeUserId'];
			$resultTime1 = queryDB($connect,$getTime1);
?>
		
<select name="dropdown1">
<?php	
	if($resultTime1)
		{

			while($row = mysqli_fetch_array($resultTime1))
    		{  ?>    
				<option value="<?php echo $row['datum_vrijeme_pitanja'];?>">
					<?php echo $row['datum_vrijeme_pitanja']; ?>
				</option>
			<?php } ?>
		}
?>
</select>
 <?php
    if(isset($_POST['dropdown']))
    {
    	$selectedDatum = $_POST['dropdown'];
    }
        if(isset($_POST['dropdown1']))
    {
    	$selectedDatum1 = $_POST['dropdown1'];
    }
    echo
      "<form action='' method='post'>
        <input type='submit' name='zatraziRezultat' value='Zatrazi rezultat!' />
       </form>";

		if(isset($_POST['zatraziRezultat']))
		{
			$statsQuery ="SELECT ime, prezime, COUNT(*) AS broj_odgovora FROM korisnik, zaposlenik, odgovor
								WHERE korisnik.korisnik_id = zaposlenik.korisnik_id AND zaposlenik.zaposlenik_id = odgovor.zaposlenik_id
								AND zaposlenik.tvrtka_id ='{$_SESSION['tvrtkaId']}' AND odgovor.datum_vrijeme_odgovora BETWEEN 
								'{$selectedDatum}' AND 
								'{$selectedDatum1}'
								 GROUP BY korisnicko_ime";
								
				$statsResult = queryDB($connect,$statsQuery);

				if(mysqli_num_rows($statsResult) > 0)
				{

			echo "<h2> Popis odgovora </h2>";
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
	}
}

										
disconnectDB($connect);

?>
</body>
</html>
