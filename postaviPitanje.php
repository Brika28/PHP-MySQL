<?php 
include("header.php");
include("baza.php");

$notFound=$_POST['naziv'];
$id=$_POST['id'];
$ime=$_POST['ime'];
$prezime=$_POST['prezime'];
$naslov=$_POST['naslov'];
$pitanje=$_POST['pitanje'];
$slika=$_POST['slika'];
$video=$_POST['video'];
$datum=date('Y-m-d H:i:s');







$connect = connectDB();
$query = "INSERT INTO pitanje 
		(tvrtka_id,naslov,datum_vrijeme_pitanja,tekst,slika,video)
         VALUES('$id','$naslov','$datum','$pitanje','$slika','$video')";

$result= queryDB($connect,$query);

disconnectDB($connect);
header("Location: detalji.php?id=$id");




?>