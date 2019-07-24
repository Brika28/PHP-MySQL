<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php 
include ("baza.php");
include ("header.php");

$id = $_GET['id'];

$connect=connectDB();
$editQuery="SELECT tvrtka.naziv,tvrtka.opis,tvrtka.broj_zaposlenika 
				FROM tvrtka 
				WHERE tvrtka.tvrtka_id ='$id'";
$result=queryDB($connect,$editQuery);
while($row=mysqli_fetch_array($result))
{
	$naziv=$row['naziv'];
	$opis = $row['opis'];
	$broj_zaposlenika = $row['broj_zaposlenika'];
}
$maxEmployees = "SELECT COUNT(zaposlenik_id) 
				AS BrojDjelatnika 
				FROM zaposlenik
				WHERE zaposlenik.tvrtka_id = '$id'";

$result1=queryDB($connect,$maxEmployees);
while($row=mysqli_fetch_array($result1))
{
	$BrojDjelatnika= $row['BrojDjelatnika'];
}


if(isset($_POST['editForm']))
{
	$nazivNovi = $_POST['naziv'];
	$opisNovi = $_POST['opis'];
	$brojNovi = $_POST['broj_zaposlenika'];

	if($BrojDjelatnika > $brojNovi)
	{
		echo "Potrebno je otpustiti djelatnika za smanjenje broja zaposlenih!";
		echo "<button> <a href='azuriranjeTvrtke.php?id=$id'> Nazad </a> </button>";
		exit();
		 
	}
	else
	{
		$newData = "UPDATE tvrtka 
					SET naziv = '$nazivNovi', opis = '$opisNovi', broj_zaposlenika = '$brojNovi'
					WHERE tvrtka_id = '$id'";
		$result=queryDB($connect,$newData);
		header("location:azuriranjeTvrtke.php?id=$id");


	}
}
?>
<br>
<form action="" method="POST">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<label> Naziv: </label>
<input type="text" name="naziv" value="<?php echo $naziv; ?>"> <br>
<label> Opis: </label>
<input type="text" name="opis"  value="<?php echo $opis; ?>"> <br>
<label> Broj zaposlenih: </label>
<input type="text" name="broj_zaposlenika" value="<?php echo $broj_zaposlenika; ?>">
<input type="submit" name="editForm" value="Izmjeni!">
</form>




</body>
</html>

