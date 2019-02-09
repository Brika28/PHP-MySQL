<?php
session_start();
include("header.php");
?>






	<h3 class="title">Anonimni/neregistrirani korisnik</h3>
	<p>Anonimni/neregistrirani korisnik može vidjeti popis tvrtki i odabirom tvrtke vide naslove postojećih pitanja sa statusom (ima ili nema odgovor) sortirano silazno po datumu i vremenu.</p>

	<h3 class="title">Registrirani korisnik</h3>
	<p>Registrirani korisnik uz svoje funkcionalnosti ima i sve funkcionalnosti kao i neprijavljeni korisnik. Vidi popis pitanja za svoju tvrtku, a u zaglavlju stranice vidi broj preostalih odgovora za tvrtku.</p>

	<h3 class="title"> Moderator </h3>
	<p>Moderator uz svoje funkcionalnosti ima i sve funkcionalnosti kao i registrirani korisnik te uz to unositi i pregledati zaposlenike svoje tvrtke. Unos novog zaposlenika (dozvoljen je samo ako ima slobodnog mjesta za zaposlenje) obavlja se odabirom iz popisa korisnika koji još nisu zaposlenici u drugim tvrtkama i nisu moderatori. Vidi popis pitanja svoje tvrtke i u kojem su trenutnom statusu. Za svoju tvrtku može poslati zahtjev za pravo na dodatnih 10 odgovora (što se evidentira u tablici tvrtka za stupac zahtjev kao vrijednost 1) ukoliko nema prethodnog zahtjeva koji čeka na odobrenje (u tablici tvrtka stupac zahtjev vrijednost 0). Može vidjeti statistiku broja odgovora po zaposleniku svoje tvrtke za odabrano vremensko razdoblje davanja pitanja. Razdoblje se definira datumom i vremenom od i do. </p>

	<h3 class="title">Administrator </h3>
	<p>Administrator uz svoje funkcionalnosti ima i sve funkcionalnosti kao i moderator. Unosi, ažurira i pregledava korisnike sustava te definira i ažurira njihove tipove. Unosi, pregledava i ažurira tvrtke (npr. Styria, NTH, Diverto, …) i bira moderatora za pojedinu tvrtku između korisnika koji imaju tip moderator. Jedan moderator može biti zadužen samo za jednu tvrtku. Kod unosa tvrtke mora unijeti naziv, opcionalno opis i broj zaposlenika tvrtke te iste može ažurirati. Inicijalno stupci zahtjev i preostaliOdgovori imaju vrijednost 0. Vidi popis tvrtki za koje postoji zahtjev za povećanjem broja pitanja. Zahtjev se odobrava tako da se u tablici tvrtka stupac zahtjev postavi na 0 a stupac preostaliOdgovori se poveća za 10. Može vidjeti statistiku broja pitanja i broja odgovora po tvrtkama. Može vidjeti rang listu zaposlenika (i iz koje je tvrtke) koji su dali najviše odgovora. </p>


<a href="o_autoru.html"> O autoru aplikacije </a>
