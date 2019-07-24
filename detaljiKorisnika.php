<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<?php 
include ("baza.php");
include ("header.php");

$idUser=$_GET['kid'];
$_SESSION['idUser']=$idUser;



$connect=connectDB();
$query="SELECT tip_id,korisnicko_ime,lozinka,ime,prezime,email,slika 
			FROM korisnik WHERE korisnik_id='$idUser'";

$result=queryDB($connect,$query);

if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis svih korisnika</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Tip korisnika</th>";
			echo "<th>Korisnicko ime</th>";
			echo "<th>Lozinka</th>";
			echo "<th>Ime</th>";
			echo "<th>Prezime</th>";
			echo "<th>E-mail</th>";
			echo "<th>Slika</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($tip_id,$korisnicko_ime,$lozinka,$ime,$prezime,$email,$slika)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td>".$tip_id."</td>";
					echo "<td>".$korisnicko_ime."</td>";
					echo "<td>".$lozinka."</td>";
					echo "<td>".$ime."</td>";
					echo "<td>".$prezime."</td>";
					echo "<td>".$email."</td>";
					echo "<td>".$slika."</td>";
					echo "</tr>";
				}
			echo "</tbody>";
			echo "</table>";
		}


 ?>
 	<h2> Promjena tipa korisniku <h2>
 		<h4> Odaberite novi tip koji želite dodjeliti korisniku. </h4>
 <form action=" " method="POST">
 	<input type="radio" name="tip" value="0"> Tip 0 - Admin <br>
 	<input type="radio" name="tip" value="1"> Tip 1 - Voditelj <br>
 	<input type="radio" name="tip" value="2"> Tip 2 - Korisnik <br>
 	<input type="submit" value="Promjeni tip!" name="promjeni">
 </form>
	<?php
	if(isset($_POST['tip']))
	{
		$tip=intval($_POST['tip']);
	}

	if(isset($_POST['promjeni']))
	{

		$setQuery="UPDATE korisnik
					SET tip_id = '$tip'
					WHERE korisnik.korisnik_id ='{$_SESSION['idUser']}'";
		$result=queryDB($connect,$setQuery);
		echo"Tip promijenjen!";
		header("Location: detaljiKorisnika.php?kid=$idUser");

	}
 $userEdit = "SELECT korisnik.korisnicko_ime,korisnik.ime,korisnik.prezime,korisnik.email
				FROM korisnik 
				WHERE korisnik.korisnik_id ='$idUser'";
$result=queryDB($connect,$userEdit);
while($row=mysqli_fetch_array($result))
{
	$korisnickoIme=$row['korisnicko_ime'];
	$ime=$row['ime'];
	$prezime=$row['prezime'];
	$email=$row['email'];
}
if(isset($_POST['submit1']))
{
	$korImeNovo=$_POST['korisnickoIme'];
	$imeNovo=$_POST['ime'];
	$prezimeNovo=$_POST['prezime'];
	$emailNovo=$_POST['email'];

	$newData = "UPDATE korisnik
					SET korisnicko_ime = '$korImeNovo', ime='$imeNovo', prezime = '$prezimeNovo', email='$emailNovo'
					WHERE korisnik.korisnik_id = '$idUser'";
	$result=queryDB($connect,$newData);
	header("location:detaljiKorisnika.php?kid=$idUser");
}
disconnectDB($connect);
	 ?>
<h2> Izmjena korisnickih podataka </h2>
<form action="" method="POST">
	<label> Korisnicko ime </label>
	<input type="text" name="korisnickoIme" value="<?php echo $korisnickoIme; ?>"> <br>
	<label> Ime </label>
	<input type="text" name="ime" value="<?php echo $ime; ?>">  <br>
	<label> Prezime</label>
	<input type="tex" name="prezime" value="<?php echo $prezime; ?>">  <br>
	<label> Email </label>
	<input type="text" name="email" value="<?php echo $email; ?>"> 
	<input type="submit" name="submit1" value="Ažuriraj">

</body>
</html>
