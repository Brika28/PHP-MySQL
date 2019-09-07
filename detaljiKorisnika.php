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
		{ ?>
			<h2 class="h2">Podcai o korisniku </h2>
			<div class="container">
			<table class="table table-bordered">
			<thead class="thead-dark">
			<tr>
			<th scope="col">Tip korisnika</th>
			<th scope="col">Korisnicko ime</th>
			<th scope="col">Lozinka</th>
			<th scope="col">Ime</th>
			<th scope="col">Prezime</th>
			<th scope="col">E-mail</th>
			<th scope="col">Slika</th>
			</tr>
			</thead>
			<tbody>

		<?php while(list($tip_id,$korisnicko_ime,$lozinka,$ime,$prezime,$email,$slika)=mysqli_fetch_row($result))
				{ ?>
					<tr>
					<td> <?php echo "$tip_id"; ?> </td>
					<td> <?php echo "$korisnicko_ime"; ?> </td>
					<td> <?php echo "$lozinka"; ?> </td>
					<td> <?php echo "$ime"; ?> </td>
					<td> <?php echo "$prezime"; ?> </td>
					<td> <?php echo "$email"; ?> </td>
					<td> <img src="<?php echo "$slika"; ?>" height="100" width="100"> </td>
					</tr>
		<?php } ?>
			</tbody>
			</table>
		</div>
	<?php } ?>
 	<div class="container" id="loginForm">
 		<h2 class="h2"> Promjena tipa korisnika <h2>
 			<h6> Odaberite novi tip koji želite dodjeliti korisniku. </h6>
 <form action=" " method="POST">
 	<div class="form-group">
 	<input type="radio" name="tip" value="0"> Tip 0 - Admin
 </div>
 <div class="form-group">
 	<input type="radio" name="tip" value="1"> Tip 1 - Voditelj
 </div>
 <div class="form-group">
 	<input type="radio" name="tip" value="2"> Tip 2 - Korisnik
 </div> 
 	<button type="submit"  name="promjeni" class="btn btn-primary"> Promijeni tip! </button>
 </form>
</div>
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
 $userEdit = "SELECT korisnik.korisnicko_ime,korisnik.ime,korisnik.prezime,korisnik.email,korisnik.lozinka,korisnik.slika
				FROM korisnik 
				WHERE korisnik.korisnik_id ='$idUser'";
$result=queryDB($connect,$userEdit);
while($row=mysqli_fetch_array($result))
{
	$korisnickoIme=$row['korisnicko_ime'];
	$ime=$row['ime'];
	$prezime=$row['prezime'];
	$email=$row['email'];
	$lozinka=$row['lozinka'];
	$slika=$row['slika'];

}
if(isset($_POST['submit1']))
{
	$korImeNovo=$_POST['korisnickoIme'];
	$imeNovo=$_POST['ime'];
	$prezimeNovo=$_POST['prezime'];
	$emailNovo=$_POST['email'];
	$slikaNovo=$_POST['slika'];
	$lozinkaNovo=$_POST['lozinka'];


	$newData = "UPDATE korisnik
					SET korisnicko_ime = '$korImeNovo', ime='$imeNovo', prezime = '$prezimeNovo', email='$emailNovo',lozinka='$lozinkaNovo',slika='$slikaNovo'
					WHERE korisnik.korisnik_id = '$idUser'";
	$result=queryDB($connect,$newData);
	header("location:detaljiKorisnika.php?kid=$idUser");
}
	 ?>
<div class="container" id="loginForm">
<h2> Izmjena korisnickih podataka </h2>
<form action="" method="POST">
	<div class="form-group">
	<label> Korisnicko ime </label>
	<input type="text" name="korisnickoIme" value="<?php echo $korisnickoIme; ?>" class="form-control">
</div>
<div class="form-group">
	<label> Lozinka </label>
	<input type="text" name="lozinka" value="<?php echo $lozinka; ?>" class="form-control"> 
</div>
<div class="form-group">
	<label> Ime </label>
	<input type="text" name="ime" value="<?php echo $ime; ?>" class="form-control">
</div>
<div class="form-group">
	<label> Prezime</label>
	<input type="tex" name="prezime" value="<?php echo $prezime; ?>" class="form-control">
</div>
<div class="form-group">
	<label> Email </label>
	<input type="text" name="email" value="<?php echo $email; ?>" class="form-control"> 
</div>

<div class="form-group">
	<label> Slika </label>
	<input type="text" name="slika" value="<?php echo $slika; ?>" class="form-control"> 
</div>
	<button type="submit" name="submit1" class="btn btn-primary">Ažuriraj! </button>
<?php 
disconnectDB($connect);
include("footer.php"); ?>
