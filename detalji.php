<?php 
include("baza.php");
include("header.php");

$id=$_GET['id'];

$connect=connectDB();

$query ="SELECT tvrtka.tvrtka_id,naziv, pitanje.tvrtka_id,naslov,pitanje.tekst AS pitanje , odgovor.pitanje_id,ISNULL(odgovor.tekst) AS status 
			FROM pitanje  
			INNER JOIN tvrtka ON pitanje.tvrtka_id = tvrtka.tvrtka_id
            LEFT JOIN odgovor ON pitanje.pitanje_id = odgovor.pitanje_id 
            WHERE tvrtka.tvrtka_id  = '$id'
            GROUP BY pitanje 
            ORDER BY datum_vrijeme_pitanja DESC";


$result = queryDB($connect,$query);

if(mysqli_num_rows($result) >= 0)
{
	echo "<h2> Popis pitanja </h2>";

	echo "<table border ='1'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Naziv tvrtke</th><th>Naslov</th><th>Pitanje</th><th>Odgovor</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

		while($row = mysqli_fetch_array($result))
		{
			echo "<tr>";
			echo "<td>".$row["naziv"]."</td>";
			echo "<td>".$row["naslov"]."</td>";
			echo "<td>".$row["pitanje"]."</td>";
			if($row['status'] == 0) 
				{
					echo "<td>Ima odgovor</td>";
				} 
			else 
				{
					echo "<td>Nema odgovor</td>";
				}
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
	<title>Popis pitanja </title>
</head>
<body>
	<div>
		<h2> Postavi pitanje: </h2>
		<form action="postaviPitanje.php" method="POST">
			<label> NASLOV: </label>
			<input type="text" name="naslov" required="required"> <br> 
			<label> PITANJE: </label>
			<input type="text" name="pitanje" required="required"> <br>
			<label> SLIKA: </label>
			<input type="src" name="slika"> <br>
			<label> VIDEO: </label>
			<input type="src" name="video">

			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
			<input type="hidden" name="naziv" value="<?php echo $notFound; ?>">
			<input type="submit" name="Postavi" value="Postavi pitanje!">



		</form>
	</div>
</body>
</html>

