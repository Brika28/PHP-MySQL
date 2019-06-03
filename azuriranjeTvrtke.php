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

<?php 
$id=$_GET['id'];

$connect=connectDB();

$detaljiTvrtke="SELECT korisnik.korisnicko_ime,naziv,opis,broj_zaposlenika,tvrtka_id
				FROM tvrtka
				LEFT JOIN korisnik ON korisnik.korisnik_id = tvrtka.moderator_id
				WHERE tvrtka.tvrtka_id ='$id'";
$result=queryDb($connect,$detaljiTvrtke);

if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis tvrtki</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Moderator </th>";
			echo "<th>Naziv </th>";
			echo "<th>Opis </th>";
			echo "<th>Broj zaposlenih </th>";
			echo "<th>Azuriranje tvrtke </th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($korisnicko_ime,$naziv,$opis,$broj,$tvrtka_id)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td>".$korisnicko_ime."</td>";
					echo "<td>".$naziv."</td>";
					echo "<td>".$opis."</td>";
					echo "<td>".$broj."</td>";
					echo "<td><a href='azuriranjeTvrtke.php?row['tvrtka_id']'> Azuriranje </a></td>";
					echo "</tr>";

				}
				echo "</tbody>";
				echo "</table>";
		}



 ?>

</body>
</html>