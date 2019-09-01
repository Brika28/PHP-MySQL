<?php 
include("header.php");
include("baza.php");


$id=$_GET['id'];

$connect=connectDB();


$query ="SELECT tvrtka.tvrtka_id,naziv,pitanje.pitanje_id AS tudePitanje, pitanje.tvrtka_id,naslov,pitanje.tekst AS pitanje , pitanje.datum_vrijeme_pitanja, odgovor.pitanje_id,ISNULL(odgovor.tekst) AS status 
			FROM pitanje  
			INNER JOIN tvrtka ON pitanje.tvrtka_id = tvrtka.tvrtka_id
            LEFT JOIN odgovor ON pitanje.pitanje_id = odgovor.pitanje_id 
            WHERE tvrtka.tvrtka_id  = '$id'
            GROUP BY pitanje 
            ORDER BY datum_vrijeme_pitanja DESC";


$result = queryDB($connect,$query);
?>
<div class="container-fluid">
<?php 
if(mysqli_num_rows($result) >= 0) { ?>

	<table class="table table-bordered">
  			<thead class="thead-dark">
    			<tr>
      				<th scope="col">Naziv tvrtke</th>
      				<th scope="col">Naslov pitanja</th>
      				<th scope="col">Pitanje</th>
      				<th scope="col">Datum i vrijeme pitanja</th>
      				<th scope="col">Odgovor</th>
    			</tr>
  			</thead>
  				<tbody>
		<?php while($row = mysqli_fetch_array($result))
		{ 
			$datum_vrijeme_pitanja =date_create ($row["datum_vrijeme_pitanja"]);
			$pid=$row['tudePitanje'];
			?>
			<tr>
			<td><?php echo $row["naziv"] ?></td>
			<td><?php echo $row["naslov"] ?></td>
			<td><?php 
			if(isset($_SESSION['activeUserType']) &&  $_SESSION['activeUserType'] == 2)
			{
				if(isset($_SESSION['tvrtka_id'])  && $_SESSION['tvrtka_id'] == $id)
				{
					echo $row["pitanje"];
				}
				else
				{ ?>
					<a href=<?php  echo "prikazPitanjaTvrtke.php?id=$id&pid=$pid";  ?> > <?php echo $row["pitanje"] ?></a>
		  <?php } 
		 	}
		 	elseif(isset($_SESSION['activeUserType']) == 1)
		 	{
		 		if(isset($_SESSION['tvrtkaId']) && $_SESSION['tvrtkaId'] == $id)
		 		{ 
		 			echo $row['pitanje'];
		 		}
		 		else
				{ ?>
					<a href=<?php  echo "prikazPitanjaTvrtke.php?id=$id&pid=$pid";  ?> > <?php echo $row["pitanje"] ?></a>
					
	    <?php	} 
		 	}
		 	elseif($activeUserType == -1) 
		 	{ ?>
		 		 <?php echo $row["pitanje"] ?> 
		<?php } ?>  </td>
			<td><?php echo date_format($datum_vrijeme_pitanja,"d.m.Y H:i:s"); ?></td>
			<?php if($row['status'] == 0) 
				{ ?>
					<td>Ima odgovor</td>
			<?php	} 
			else 
				{ ?>
					<td>Nema odgovor</td>
			<?php	} ?>
			</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
<?php } ?>

	  	 <h2 class="h2"> Postavite vlastito pitanje </h2> <br>
	<div class="container" id="questionForm">
		<form action="postaviPitanje.php" method="POST">
			<div class="form-group">
			<label> NASLOV: </label>
			<input type="text" name="naslov" required="required" class="form-control">
			</div>
			<div class="form-group">
			<label> PITANJE: </label>
			<textarea rows="4" cols="50" name="pitanje" required="required" class="form-control"></textarea>
			</div>
			<div class="form-group">
			<label> SLIKA: </label>
			<input type="src" name="slika" class="form-control">
			</div>
			<div class="form-group">
			<label> VIDEO: </label>
			<input type="src" name="video" class="form-control">
			</div>

			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
			<input type="hidden" name="naziv" value="<?php echo $notFound; ?>">
			<input type="submit"  class="btn btn-primary" name="Postavi" value="Postavi pitanje!">
		</form>
	</div>
 <?php
 var_dump($_SESSION['zaposlenik_id']);
 var_dump($_SESSION['activeUserId']);
disconnectDB($connect);
include("footer.php") ?>

