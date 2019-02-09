<?php
include ("baza.php");
include ("header.php");
?>

<?php 
$connect = connectDB();
$query = "SELECT tvrtka_id,naziv,opis FROM tvrtka";


$result= queryDB($connect,$query);

if($result){
	echo "<h2> Popis tvrtki</h2>";

	echo "<table border ='1' ";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Naziv</th><th>Opis</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

		while(list($tvrtkaId,$naziv,$opis)=mysqli_fetch_array($result)){
			echo "<tr>";
			echo "<td><a href='detalji.php?id=$tvrtkaId&naziv=$naziv'>".$naziv."</a></td>";
			echo "<td>".$opis."</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
}
disconnectDB($connect);
?>
