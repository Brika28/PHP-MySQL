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
                LEFT OUTER JOIN tvrtka
        		ON tvrtka.moderator_id=korisnik.korisnik_id 
				WHERE korisnik.tip_id = 1 AND tvrtka.moderator_id IS null ";
	$result=queryDB($connect,$modUsers);

?>

		<h2> Unesi novu tvrtku </h2>
		<form action=" " method="POST" name="unos">
			<label> Izaberite moderatora: </label> <br>
			<?php 
			if($result)
			{
				while($row = mysqli_fetch_array($result))
				{
					$razmak =" ";
					$modID=$row['korisnik_id'];
					$ime=$row['ime'];
					$prezime=$row['prezime'];
					echo '<input type="radio" name="slobodniMod" value="'.$modID.'" />'.$ime .$razmak .$prezime; 
				}
			}
			elseif($result == 0)
			{
				echo "Nema slobodnih moderatora za kreiranje tvrtke!";
			}
			?> <br>
			<label> Naziv tvrtke: </label>
			<input type="text" name="nazivTvrtke" required="required"> <br>
			<label> Opis tvrtke: </label>
			<input type="text" name="opis" required="required"> <br>
			<label> Broj zaposlenika: </label>
			<input type="text" name="brojZaposlenika" required="required"> <br>
			<input type="submit" name="unesi" value="Unesi tvrtku">
		</form>
	</div>
<?php  
if(isset($_POST['unesi']))
		{
			$modTvrtke=$_POST['slobodniMod'];
			$naziv=$_POST['nazivTvrtke'];
			$opis=$_POST['opis'];
			$brojZaposlenika=$_POST['brojZaposlenika'];

			$unosTvrtke= "INSERT INTO tvrtka
							(moderator_id,naziv,opis,broj_zaposlenika,preostaliOdgovori,zahtjev)
		         			VALUES ('$modTvrtke','$naziv','$opis','$brojZaposlenika',0,0)";

			$result= queryDB($connect,$unosTvrtke);
			header("location:unosPregledTvrtki.php");
		}


$prikazTvrtki="SELECT tvrtka_id,naziv
				FROM tvrtka";
$result=queryDB($connect,$prikazTvrtki);

if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis tvrtki</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Naziv </th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($tvrtka_id,$naziv)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='azuriranjeTvrtke.php?id=$tvrtka_id'>".$naziv."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}
disconnectDB($connect);
?>
</body>
</html>