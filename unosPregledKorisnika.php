<?php 
include ("baza.php");
include ("header.php");
 ?>

<div class="container" id="loginForm">
		<h2 class="h2"> Unesi novog korisnika </h2>
		<form action=" " method="POST" name="unos">
			<div class="form-group">
			<label> Tip korisnika: </label> 
			<input type="radio" name="tip" value="1" required="required"> Tip 1 
			<input type="radio" name="tip" value="2" required="required"> Tip 2
		    </div>
		    <div class="form-group">
			<label> Korisnicko ime: </label>
			<input type="text" name="korisnickoIme" required="required" class="form-control"> 
			</div>
			<div class="form-group">
			<label> Lozinka: </label>
			<input type="password" name="password" required="required" class="form-control">
		</div>
		<div class="form-group">
			<label> Ime: </label>
			<input type="text" name="imeKorisnika" required="required" class="form-control">
			</div>
			<div class="form-group"> 
			<label> Prezime: </label>
			<input type="text" name="prezimeKorisnika" required="required" class="form-control">
		</div>
		<div class="form-group">
			<label> E-mail: </label>
			<input type="text" name="email" required="required" class="form-control">
		</div>
		<div class="form-group">
			<label> Slika: </label>
			<input type="src" name="slikaKorisnika" class="form-control">
		</div>
			<button type="submit" name="unesi" class="btn btn-primary">Unesi korisnika! </button>
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
		{ ?>

			<div class="container">
			<table class="table table-bordered">
				<thead class="thead-dark">
			<tr>
			<th scope="col">Ime i prezime </th>
			</tr>
			</thead>
			<tbody>

			<?php while(list($korisnik_id,$ime,$prezime)=mysqli_fetch_row($result))
				{ ?>
					<tr>
					<td><a href= <?php echo "detaljiKorisnika.php?kid=$korisnik_id"; ?> > <?php echo "$ime $prezime"; ?></a></td>
					</tr>
			<?php } ?>
				</tbody>
				</table>
	<?php }  ?>
</div>

<?php
disconnectDB($connect);
include("footer.php");

?>


