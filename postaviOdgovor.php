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
if(isset($_SESSION['tvrtkaId']))
{
	$tvrtkaId = $_SESSION['tvrtkaId'];
}

$tekst=$_POST["tekstOdgovora"];
$datum=date('Y-m-d H:i:s');

$connect=connectDB();

		if(empty($_SESSION['employedMod']) && $_SESSION['activeUserType'] == 1)
		{
			$selectMod = "SELECT zaposlenik.korisnik_id 
					FROM zaposlenik
					INNER JOIN tvrtka ON zaposlenik.korisnik_id = tvrtka.moderator_id
					WHERE tvrtka.moderator_id =".$_SESSION['activeUserId'];

			$resultSelect = queryDB($connect,$selectMod);

			if(mysqli_num_rows($resultSelect) <= 0)
			{
				$insertMod = "INSERT INTO zaposlenik 
								(korisnik_id,tvrtka_id)
								VALUES ('$modId',$tvrtkaId)";

				$resultInsert=queryDB($connect,$insertMod);
			}
		}

		$checkEmployee="SELECT zaposlenik_id
					FROM zaposlenik
					INNER JOIN korisnik ON zaposlenik.korisnik_id = korisnik.korisnik_id
					WHERE zaposlenik.korisnik_id =".$_SESSION['activeUserId'];

		$resultEmployee=queryDb($connect,$checkEmployee);

		if(mysqli_num_rows($resultEmployee) != 0)
			{
				list($zaposlenMod) = mysqli_fetch_array($resultEmployee);
				$_SESSION['zaposlenMod']=$zaposlenMod;
			}
		

if($_SESSION['activeUserType'] == 1)
{	

	if(isset($_SESSION['zaposlenMod']))
	{
		$zaposlenMod = $_SESSION['zaposlenMod'];
	}	


	$query1="SELECT pitanje_id,odgovor.zaposlenik_id
			FROM odgovor
			INNER JOIN zaposlenik
			ON odgovor.zaposlenik_id=zaposlenik.zaposlenik_id
			WHERE odgovor.zaposlenik_id= '{$_SESSION['zaposlenMod']}' AND odgovor.pitanje_id='$pid'";

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
						VALUES ('$pid','$zaposlenMod','$tekst','$datum')";

			$result=queryDB($connect,$insertQuery);

			$checkQuery="SELECT tvrtka.preostaliOdgovori
						FROM tvrtka
						INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
						WHERE zaposlenik.korisnik_id=".$_SESSION['activeUserId'];

			$checkResult=queryDB($connect,$checkQuery);

			if($checkResult > 0)
			{
				$updateQuery="UPDATE tvrtka 
								INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
								SET preostaliOdgovori = preostaliOdgovori-1
								WHERE zaposlenik.korisnik_id =".$_SESSION['activeUserId'];

				$result=queryDB($connect,$updateQuery);
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

if(isset($_SESSION['zaposlenik_id']) && $_SESSION['activeUserType'] == 2) 
{


			$query1="SELECT pitanje_id,odgovor.zaposlenik_id
			FROM odgovor
			INNER JOIN zaposlenik
			ON odgovor.zaposlenik_id=zaposlenik.zaposlenik_id
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
						WHERE zaposlenik.korisnik_id=".$_SESSION['activeUserId'];

			$checkResult=queryDB($connect,$checkQuery);

			if($checkResult > 0)
			{
				$updateQuery="UPDATE tvrtka 
								INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
								SET preostaliOdgovori = preostaliOdgovori-1
								WHERE zaposlenik.korisnik_id =".$_SESSION['activeUserId'];

				$result=queryDB($connect,$updateQuery);
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