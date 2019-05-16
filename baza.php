<?php
define ("HOST","localhost");
define ("BAZA", "iwa_2018_zb_projekt");
define ("BAZA_KORISNIK", "iwa_2018");
define ("BAZA_SIFRA", "foi2018");

function connectDB()
{

	$connect = mysqli_connect(HOST,BAZA_KORISNIK,BAZA_SIFRA);

	if(!$connect)
	{
		echo "Error: nemoguce se povezati sa bazom";
	}

	mysqli_select_db($connect,BAZA);

	mysqli_set_charset($connect,"utf8");

	if(mysqli_error($connect) !== "")
	{
		echo "Error: problem s odabirom baze!".mysqli_error($connect);
	}

	return $connect;
}

function queryDB($connect, $query)
{
	$result = mysqli_query($connect, $query);

	if(!$result)
	{
		die('Greška u upitu' .mysqli_error($connect));
	}

	return $result;
}

function disconnectDB ($connect)
{
	mysqli_close($connect);
} 

?>