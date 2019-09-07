<?php
include("baza.php");
include("header.php");

$id=$_GET['id'];
$pid=$_GET['pid'];

$connect=connectDB();
$query ="SELECT pitanje.tvrtka_id,naslov,datum_vrijeme_pitanja,pitanje.tekst,slika,video,pitanje.pitanje_id, tvrtka.tvrtka_id, odgovor.odgovor_id,preostaliOdgovori,odgovor.tekst AS odgTekst 
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
        LEFT JOIN odgovor ON odgovor.pitanje_id = pitanje.pitanje_id
		WHERE pitanje.pitanje_id = '$pid' AND tvrtka.tvrtka_id= '$id'";


$result=queryDB($connect,$query);

if(mysqli_num_rows($result) >= 0)
{ ?>
<div class="container">
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th> Naslov </th>
					<th> Datum i vrijeme</th>
					<th>Tekst</th>
					<th>Slika</th>
					<th>Video</th>
					<th>Odgovor</th>
				</tr>
			</thead>
				<tbody>
	<?php	while($row = mysqli_fetch_array($result))
		{ 
			$vrijeme = date_create($row["datum_vrijeme_pitanja"]);
			?>
			<tr>
			<td><?php echo $row["naslov"] ?></td>
			<td><?php echo date_format( $vrijeme,"d-m-Y H:i:s"); ?></td>
			<td><?php echo $row["tekst"] ?></td>
			<td><img src="<?php echo $row['slika'];?>" height="100" width="100"> </td>
			<td><?php if(empty($row['video']) || $row['video'] == null || $row['video'] == "")
			 			{ 
			 				echo "Nije priložen video."; 
						}
					else { ?>
					<iframe width="200" height="150" 
				src="<?php echo $row['video']; ?>"> </iframe> </td>
              <?php } ?>

			<td><?php echo $row["odgTekst"] ?></td>
			</tr>
	<?php	} ?>
 
				</tbody>
		</table>
	</div>
</div>
<?php } ?>

 <?php
disconnectDB($connect);
include("footer.php") ?>
