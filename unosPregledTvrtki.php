<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php 
include("baza.php");
include("header.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<div>
	<?php 

	$connect=connectDB();

	$modUsers="SELECT korisnik_id,ime,prezime
				FROM korisnik
				WHERE korisnik.tip_id = 1";
	$result=queryDB($connect,$modUsers);




	 ?>
		<h2> Unesi novou tvrtku </h2>
		<form action="unosPregledTvrtki.php" method="POST" name="unos">
			<label> Izaberite moderatora: </label> <br>
			<?php 
			while($row = mysqli_fetch_array($result))
			{
				$razmak =" ";
				$modID=$row['korisnik_id'];
				$ime=$row['ime'];
				$prezime=$row['prezime'];
				echo '<input type="radio" name="slobodniMod" value="'.$modID.'" />'.$ime .$razmak .$prezime; 
			}
			?> <br>
			<label> Korisnicko ime: </label>
			<input type="text" name="korisnickoIme" required="required"> <br>
			<label> Lozinka: </label>
			<input type="password" name="password" required="required"> <br>
			<label> Ime: </label>
			<input type="text" name="imeKorisnika" required="required"> <br>
			<label> Prezime: </label>
			<input type="text" name="prezimeKorisnika" required="required"> <br>
			<label> E-mail: </label>
			<input type="email" name="email" required="required"> <br>
			<label> Slika: </label>
			<input type="src" name="slikaKorisnika"> <br>
			<input type="submit" name="unesi" value="Unesi korisnika">
		</form>
	</div>





</body>
</html>