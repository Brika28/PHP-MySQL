<?php
include ("baza.php");
include ("header.php");

if(isset($_POST['userName']))
{
	$userName=$_POST['userName'];
	$pass=$_POST['pass'];

	if(!empty($userName) && !empty($pass))
	{
		$connect=connectDB();
		$query= "SELECT korisnik_id,korisnik.tip_id, ime, prezime, tip_korisnika.naziv 
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