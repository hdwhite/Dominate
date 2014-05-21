<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<STYLE TYPE="text/css">
			@import url("../harry.css");
		</STYLE>
		<title>A Guide for Writing Orders</title>
	</head>
	<body>
		<div id=container>
			<div id=header>
				<h2>So we'll understand you</h2>
				<?php $sel=5; include("../header.php"); ?>
			</div>
			<div id=content>
				<div class=entry>
					<h4>Note</h4>
					<p>You don't have to follow these, but otherwise the orders will become very unwieldly and long. There are varitions on this, so bear with me here.</p>
				</div>
				<div class=entry>
					<h4>Moving from province to province:</h4>
					<p>[Attacking province] - [Destination province
					<br>Examples: Lon - NTH; Kie - Den</p>
				</div>
				<div class=entry>
					<h4>Holding your army</h4>
					<p>[Province] h OR
					<br>[province] stands OR
					<br>Just write nothing or complete gibberish!
					<br>Examples: Spa h; Mos stands; BLA c Par - Swi (This move is illegal, so the fleet will hold)</p>
				</div>
				<div class=entry>
					<h4>Supporting provinces</h4>
					<p>[Supporting province] s [Supported province]
					<br>[Supporting province] s [Attacking province] - [Destination Province]
					<br>Examples: Tri s Vie; Ser s Alb - Gre</p>
				</div>
				<div class=entry>
					<h4>Convoying armies</h4>
					<p>[Fleet 1] c [Army] - [Destination]
					<br>[Fleet 2] c [Army] - [Destination] etc.
					<br>[Army] - [Destination]
					<br>Examples: ION c Nap - Tun, Nap - Tun; ENG c Lon - Bel, NTH c Lon - Bel, Lon - Bel
					<br><b>Note:</b> It's a good idea (but not absolutely necessary) to have both the fleet convoy orders and the army move order written down. Also, in the second example, the convoy will suceed as long as there is one open route.</p>
				</div>
				<div class=entry>
					<h4>Region Abbreviations</h4>
					<p>Albania - Alb
					<br>Ankara - Ank
					<br>Apuila - Apu
					<br>Armenia - Arm
					<br>Belgium - Bel
					<br>Berlin - Ber
					<br>Bohemia - Boh
					<br>Brest - Bre
					<br>Budapest - Bud
					<br>Bulgeria - Bul
					<br>Burgendy - Bur
					<br>Clyde - Cly
					<br>Constantinople - Con
					<br>Denmark - Den
					<br>Edinburgh - Edi
					<br>Finland - Fin
					<br>Galacia - Gal
					<br>Gascony - Gas
					<br>Greece - Gre
					<br>Holland - Hol
					<br>Kiel - Kie
					<br>Liverpool - Lvp OR Lpl
					<br>Livonia - Lvn
					<br>London - Lon
					<br>Marseilles - Mar
					<br>Moscow - Mos
					<br>Munich - Mun
					<br>Naples - Nap
					<br>North Africa - NAf
					<br>Norway - Nor OR Nwy
					<br>Paris - Par
					<br>Picardy - Pic
					<br>Piedmont - Pie
					<br>Portugal - Por
					<br>Prussia - Pru
					<br>Ruhr - Ruh
					<br>Rome - Rom
					<br>Rumania - Rum
					<br>St. Petersburg - StP
					<br>Serbia - Ser
					<br>Sevastpol - Sev
					<br>Silesia - Sil
					<br>Smyrna - Smy
					<br>Spain - Spa
					<br>Sweden - Swe
					<br>Syria - Syr
					<br>Trieste - Tri
					<br>Tunis - Tun
					<br>Tuscany - Tus
					<br>Tyrolia - Tyr
					<br>Ukraine - Ukr
					<br>Venice - Ven
					<br>Vienna - Vie
					<br>Wales - Wal
					<br>Warsaw - War
					<br>Yorkshire - Yor
					<br>
					<br>Adriatic Sea - ADR
					<br>Aegean Sea - AEG
					<br>Baltic Sea - BAL
					<br>Barents Sea - BAR
					<br>Black Sea - BLA
					<br>Eastern Mediterranean Sea - EMS or EAS
					<br>English Channel - ENG
					<br>Gulf of Bothnia - GoB or BOT
					<br>Gulf of Lyons - GoL or LYO
					<br>Heligoland Bight - HEL
					<br>Ionian Sea - ION
					<br>Irish Sea - IRI
					<br>Mid Atlantc Ocean - MAO or MID or MAt
					<br>North Atlantic Ocean - NAO or NAt
					<br>North Sea - NTH
					<br>Norwegian Sea - NWG or NWS
					<br>Skagerrak - SKA
					<br>Tyrrhenian Sea - TYR or TYS
					<br>Western Mediterranean Sea - WMS or WES
				</div>
				<?php include("../footer.php"); ?>
			</div>
		</div>
	</body>
</html>
