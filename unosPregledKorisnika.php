<?php 
include ("baza.php");
include ("header.php");
 ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<div>
		<h2> Unesi novog korisnika </h2>
		<form action=" " method="POST" name="unos">
			<label> Tip korisnika: </label> <br>
			<input type="radio" name="tip" value="1" required="required"> Tip korisnika 1 <br>
			<input type="radio" name="tip" value="2" required="required"> Tip korisnika 2<br>
			<label> Korisnicko ime: </label>
			<input type="text" name="korisnickoIme" required="required"> <br>
			<label> Lozinka: </label>
			<input type="password" name="password" required="required"> <br>
			<label> Ime: </label>
			<input type="text" name="imeKorisnika" required="required"> <br>
			<label> Prezime: </label>
			<input type="text" name="prezimeKorisnika" required="required"> <br>
			<label> E-mail: </label>
			<input type="text" name="email" required="required"> <br>
			<label> Slika: </label>
			<input type="src" name="slikaKorisnika"> <br>
			<input type="submit" name="unesi" value="Unesi korisnika">
		</form>
	</div>
<?php 
$connect=connectDB();

if(isset($_POST['unesi']))
		{
			$tip=$_POST['tip'];
			$korisnickoIme=$_POST['korisnickoIme'];
			$lozinka=$_POST['password'];
			$imeKorisnika=$_POST['imeKorisnika'];
			$prezimeKorisnika=$_POST['prezimeKorisnika'];
			$email=$_POST['email'];
			$slikaKorisnika=$_POST['slikaKorisnika'];

			$unosKorisnika= "INSERT INTO korisnik
							(tip_id,korisnicko_ime,lozinka,ime,prezime,email,slika)
		         			VALUES ('$tip','$korisnickoIme','$lozinka','$imeKorisnika','$prezimeKorisnika','$email','$slikaKorisnika')";

			$result= queryDB($connect,$unosKorisnika);
		}

$prikazKorisnika = "SELECT korisnik_id,ime,prezime
						FROM korisnik";

$result=queryDB($connect,$prikazKorisnika);
if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis svih korisnika</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Ime i prezime </th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($korisnik_id,$ime,$prezime)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='detaljiKorisnika.php?kid=$korisnik_id'>".$ime." ".$prezime."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}



disconnectDB($connect);

?>

</body>
</html>


