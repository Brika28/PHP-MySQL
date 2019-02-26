<?php
include ("baza.php");
include ("header.php");


if(isset($_POST['userName']))
{
	$userName=$_POST['userName'];
	$pass=$_POST['pass'];
	$activeUserId = "";

	if(!empty($userName) && !empty($pass))
	{
		$connect=connectDB();
		$query= "SELECT korisnik.korisnik_id,korisnik.tip_id, ime, prezime, tip_korisnika.naziv
					FROM korisnik 
					INNER JOIN tip_korisnika ON korisnik.tip_id = tip_korisnika.tip_id
					WHERE korisnicko_ime='$userName' AND lozinka='$pass'";
		
		$result=queryDB($connect,$query);
		if($result)
		{
			if(mysqli_num_rows($result)!=0)
			{
				list($id,$type,$name,$surname,$naziv)=mysqli_fetch_array($result);
				$_SESSION['activeUser']=$userName;
				$_SESSION['activeUserName']=$name ." ".$surname;
				$_SESSION['activeUserId']=$id;
				$_SESSION['activeUserType']=$type;
				$_SESSION['tipNaziv']=$naziv;
			}
		}
		if($_SESSION['activeUserType']==2)
		{
			$query1= "SELECT preostaliOdgovori 
				FROM tvrtka
				INNER JOIN zaposlenik ON tvrtka.tvrtka_id = zaposlenik.tvrtka_id
				WHERE zaposlenik.korisnik_id = ".$_SESSION['activeUserId'];

			$result1=queryDB($connect,$query1);

				if($result1)
					{
						if(mysqli_num_rows($result1) != 0)
							{
								list($preostaliOdgovori) = mysqli_fetch_array($result1);
								$_SESSION['preostaliOdgovori']=$preostaliOdgovori;
							}
					}
		}
		else if($_SESSION['activeUserType']==1)
		{
			$query2= "SELECT preostaliOdgovori 
				FROM tvrtka
				WHERE tvrtka.moderator_id =".$_SESSION['activeUserId'];

			$result2=queryDB($connect,$query2);

				if($result2)
					{
						if(mysqli_num_rows($result2) != 0)
							{
								list($preostaliOdgovori) = mysqli_fetch_array($result2);
								$_SESSION['preostaliOdgovori']=$preostaliOdgovori;
							}
					}
		}
		
		disconnectDB($connect);
	}
	header("Location: index.php");
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Prijava</title>
	<meta charset="utf-8"/>
</head>
<body>

	<form action="loginkorisnika.php" method="POST">
		<div class="form_settings">
            <p><span>Korisničko ime :</span><input class="contact" type="text" name="userName" id="userName" value="" /></p>
            <p><span>Šifra :</span><input class="contact" type="password" name="pass" id="pass" value="" /></p>
<p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="submit" value="submit" /></p>

</body>
</html>