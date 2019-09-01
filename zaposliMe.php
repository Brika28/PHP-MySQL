<?php  
include ("baza.php");
include ("header.php");


$uid=$_GET['uid'];
$tvrtkaId=$_SESSION['tvrtkaId'];

$connect=connectDB();

$checkSpace = "SELECT tvrtka.broj_zaposlenika
				FROM tvrtka 
				WHERE tvrtka.tvrtka_id =".$_SESSION['tvrtkaId'];

$resultCheck = queryDB($connect,$checkSpace);

if($resultCheck)
{
	if(mysqli_num_rows($resultCheck) != 0)
	{
		list($broj_zaposlenika)=mysqli_fetch_array($resultCheck);
		$_SESSION['broj_zaposlenika']=$broj_zaposlenika;

	}
}

$countSpace = "SELECT COUNT(korisnik_id) AS BrojZaposelnika 
				FROM zaposlenik
				WHERE zaposlenik.tvrtka_id =".$_SESSION['tvrtkaId'];

$resultCount = queryDB($connect,$countSpace);

if($resultCount)
{
	if(mysqli_num_rows($resultCount) != 0)
	{
		list($BrojZaposelnika)=mysqli_fetch_array($resultCount);
		$_SESSION['BrojZaposelnika']=$BrojZaposelnika;

	}
}


if($_SESSION['BrojZaposelnika'] < $_SESSION['broj_zaposlenika'] )
	{
		$addEmployee="INSERT INTO zaposlenik (korisnik_id,tvrtka_id)
				VALUES ('$uid','$tvrtkaId')";
		
		$resultAdd = queryDB($connect,$addEmployee);
		header("location:mojaTvrtka.php");
	}
	else
	{
		echo "Nemate više mijesta za zaposlenje";
		echo( " <button onclick=\"location.href='mojaTvrtka.php'\">Nazad</button>") ;
	}
disconnectDB($connect);
include("footer.php");
 ?>



