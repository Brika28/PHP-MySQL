<?php 
include("baza.php");
include("header.php");

$pid=$_POST['id'];

if(isset($_SESSION['zaposlenik_id']))
{
	$zaposlenik = $_SESSION['zaposlenik_id'];
}
if(isset($_SESSION['modID']))
{
	$modId=$_SESSION['modID'];
}
$tekst=$_POST["tekstOdgovora"];
$datum=date('Y-m-d H:i:s');
$tvrtkaId = $_SESSION['tvrtkaId'];

$connect=connectDB();

if($_SESSION['activeUserType'] == 1)
{

	$query1="SELECT pitanje_id,odgovor.zaposlenik_id
			FROM odgovor
			INNER JOIN zaposlenik
			ON odgovor.pitanje_id=zaposlenik.korisnik_id
			WHERE odgovor.zaposlenik_id='{$_SESSION['activeUserId']}' AND odgovor.pitanje_id='$pid'";

$result1=queryDB($connect,$query1);
	
	if(mysqli_fetch_array($result1))
		{
	   	 header("Location:mojaTvrtka.php?id=$id"); 
	   	 exit();
		}
		else 
		{
			$insertQuery="INSERT INTO odgovor
						(pitanje_id,zaposlenik_id,tekst,datum_vrijeme_odgovora) 
						VALUES ('$pid','$modId','$tekst','$datum')";

			$result=queryDB($connect,$insertQuery);

			$checkQuery="SELECT tvrtka.preostaliOdgovori
						FROM tvrtka
						INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
						WHERE zaposlenik.tvrtka_id=".$_SESSION['activeUserId'];

			$checkResult=queryDB($connect,$checkQuery);

		if($checkResult > 0)
		{
			$updateQuery="UPDATE tvrtka 
							JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
							SET preostaliOdgovori=preostaliOdgovori-1
							WHERE zaposlenik.korisnik_id=".$_SESSION['activeUserId'];

			$result=queryDB($connect,$updateQuery);
		}
		else
		{
			header("Location:detaljiPitanja.php?id=$pid");
			exit();
		}
	}	

		$sessionQuery= "SELECT preostaliOdgovori 
				FROM tvrtka
				INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
				WHERE zaposlenik.korisnik_id = ".$_SESSION['activeUserId'];

		$result=queryDB($connect,$sessionQuery);

		if($result)
		{
			if(mysqli_num_rows($result) != 0)
			{
				list($preostaliOdgovori) = mysqli_fetch_array($result);
				$_SESSION['preostaliOdgovori']=$preostaliOdgovori;
			}
		}

		header("Location: detaljiPitanja.php?id=$pid");
}
elseif (isset($_SESSION['zaposlenik_id']) && $_SESSION['activeUserType'] == 2) {


			$query1="SELECT pitanje_id,odgovor.zaposlenik_id
			FROM odgovor
			INNER JOIN zaposlenik
			ON odgovor.pitanje_id=zaposlenik.korisnik_id
			WHERE odgovor.zaposlenik_id='$zaposlenik' AND odgovor.pitanje_id='$pid'";

$result1=queryDB($connect,$query1);
	
	if(mysqli_fetch_array($result1))
		{
	   	 header("Location:mojaTvrtka.php?id=$id"); 
	   	 exit();
		}
		else 
		{
			$insertQuery="INSERT INTO odgovor
						(pitanje_id,zaposlenik_id,tekst,datum_vrijeme_odgovora) 
						VALUES ('$pid','$zaposlenik','$tekst','$datum')";

			$result=queryDB($connect,$insertQuery);

			$checkQuery="SELECT tvrtka.preostaliOdgovori
						FROM tvrtka
						INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
						WHERE zaposlenik.tvrtka_id='$zaposlenik'";

			$checkResult=queryDB($connect,$checkQuery);

		if($checkResult > 0)
		{
			$updateQuery="UPDATE tvrtka 
							JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
							SET preostaliOdgovori=preostaliOdgovori-1
							WHERE zaposlenik.korisnik_id='$zaposlenik'";

			$result=queryDB($connect,$updateQuery);
		}
		else
		{
			header("Location:detaljiPitanja.php?id=$pid");
			exit();
		}
	}	

		$sessionQuery= "SELECT preostaliOdgovori 
				FROM tvrtka
				INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
				WHERE zaposlenik.korisnik_id = ".$_SESSION['activeUserId'];

		$result=queryDB($connect,$sessionQuery);

		if($result)
		{
			if(mysqli_num_rows($result) != 0)
			{
				list($preostaliOdgovori) = mysqli_fetch_array($result);
				$_SESSION['preostaliOdgovori']=$preostaliOdgovori;
			}
		}
}

		header("Location: detaljiPitanja.php?id=$pid");
	


disconnectDB($connect);
?>