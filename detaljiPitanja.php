<?php 
include("baza.php");
include("header.php");

$pid=$_GET['id'];



$connect=connectDB();
$query ="SELECT pitanje.tvrtka_id,naslov,datum_vrijeme_pitanja,pitanje.tekst,slika,video,pitanje.pitanje_id, tvrtka.tvrtka_id, odgovor.odgovor_id,preostaliOdgovori,odgovor.tekst AS odgTekst 
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
        LEFT JOIN odgovor ON odgovor.pitanje_id = pitanje.pitanje_id
		WHERE pitanje.pitanje_id = '$pid'";


$result=queryDB($connect,$query);

if(mysqli_num_rows($result) >= 0)
{
	echo "<h2> Detalji pitanja </h2>";

	echo "<table border ='1'> ";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Naslov</th>
			<th>Datum i vrijeme</th>
			<th>Tekst</th>
			<th>Slika</th>
			<th>Video</th>
			<th>Odgovor</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

		while($row = mysqli_fetch_array($result))
		{
			$vrijeme = strtotime($row["datum_vrijeme_pitanja"]);
			$mojeVrijeme = date("m/d/Y G:i", $vrijeme);
			echo "<tr>";
			echo "<td>".$row["naslov"]."</td>";
			echo "<td>".$mojeVrijeme."</td>";
			echo "<td>".$row["tekst"]."</td>";
			echo "<td>".$row["slika"]."</td>";
			echo "<td>".$row["video"]."</td>";
			echo "<td>".$row["odgTekst"]."</td>";
			echo "</tr>";
		}

	echo "</tbody>";
	echo "</table>";
}
disconnectDB($connect);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Detalji pitanja</title>
</head>
<body>
	<div>
		<h2>Odgovori na pitanje </h2>
			<?php if($_SESSION['preostaliOdgovori'] == 0)
			{?>
			<label>Nemate vise preostalih odgovora!</label>
			<?php } 
			 else { ?>
			 <form action="postaviOdgovor.php" method="POST">
			<label> ODGOVOR </label>
			<input type="textarea" name="tekstOdgovora"> <br>
			<input type="submit" name="postaviOdgovor" value="Odgovori!">
			<?php } ?>
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		</form>
	</div>
</body>
</html>

