<?php 
include("baza.php");
include("header.php");

$pid=$_POST['id'];
$zaposlenik=$_SESSION['activeUserId'];
$tekst=$_POST["tekstOdgovora"];
$datum=date('Y-m-d H:i:s');

$connect=connectDB();
$query1="SELECT pitanje_id,odgovor.zaposlenik_id AS odgZap
FROM odgovor
LEFT JOIN zaposlenik
ON odgovor.pitanje_id=zaposlenik.korisnik_id
WHERE odgovor.zaposlenik_id='$activeUserId' AND odgovor.pitanje_id='$pid'";

$result1=queryDB($connect,$query1);
	
if(mysqli_fetch_array($result1))
{
    header("Location:mojaTvrtka.php?id=$id"); 
    exit();
}
else 
{
	$query="INSERT INTO odgovor
	(pitanje_id,zaposlenik_id,tekst,datum_vrijeme_odgovora) VALUES ('$pid','$zaposlenik','$tekst','$datum')";

	$result=queryDB($connect,$query);

header("Location: detaljiPitanja.php?id=$pid");
}


disconnectDB($connect);
?>