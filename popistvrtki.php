<?php
include ("baza.php");
include ("header.php");


$connect = connectDB();
$query = "SELECT tvrtka_id,naziv,opis FROM tvrtka";


$result= queryDB($connect,$query);
?>
<div class="container" id="tvrtkeListe">
<?php
if($result){ ?>
		<table class="table table-bordered">
  			<thead class="thead-dark">
    			<tr>
      				<th scope="col">Naziv</th>
      				<th scope="col">Opis</th>
    			</tr>
  			</thead>
  				<tbody>
  				<?php
				while(list($tvrtkaId,$naziv,$opis)=mysqli_fetch_array($result)) { ?>
					<tr>
					<td><a href=<?php echo "detalji.php?id=$tvrtkaId&naziv=$naziv" ?> ><?php echo"$naziv" ?></a></td>
					<td> <?php echo "$opis" ?> </td>
					</tr>
					<?php }
			} ?>
				</tbody>
		</table>
</div>
<?php
disconnectDB($connect);
include ("footer.php");
?>