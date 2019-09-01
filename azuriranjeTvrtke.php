<?php 
include("baza.php");
include("header.php");

$id=$_GET['id'];

$connect=connectDB();

$detaljiTvrtke="SELECT korisnik.korisnicko_ime,naziv,opis,broj_zaposlenika,tvrtka_id
				FROM tvrtka
				LEFT JOIN korisnik ON korisnik.korisnik_id = tvrtka.moderator_id
				WHERE tvrtka.tvrtka_id ='$id'";
$result=queryDB($connect,$detaljiTvrtke);

if(mysqli_num_rows($result) >= 0)
		{ ?>
			<div class="container">
			<h2 class="h2"> Popis tvrtki</h2>
			<table class="table table-bordered">
			<thead class="thead-dark">
			<tr>
			<th>Moderator </th>
			<th>Naziv </th>
			<th>Opis </th>
			<th>Broj zaposlenih </th>
			</tr>
			</thead>
			<tbody>

<?php 	  while(list($korisnicko_ime,$naziv,$opis,$broj,$tvrtka_id)=mysqli_fetch_row($result))
				{ ?>
					<tr>
					<td> <?php echo"$korisnicko_ime"; ?> </td>
					<td> <?php echo"$naziv"; ?> </td>
					<td> <?php echo"$opis"; ?> </td>
					<td> <?php echo"$broj"; ?> </td>
					<td> <a href= <?php echo"azuriraj.php?id=$id"; ?> > Izmjeni </a> </td>
					</tr>

		<?php	} ?>
				</tbody>
				</table>
			</div>
<?php } 


disconnectDB($connect);
include("footer.php");
 ?>
