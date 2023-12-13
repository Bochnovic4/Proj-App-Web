<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Łukasz Bochno" />
    <title>Moje hobby to szachy.</title>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="js/kolorujtlo.js"></script>
    <script src='js/jquery.js'></script>
</head>
<body onload="startClock()">
<table>
    <tr>
        <td><a href="index.php?idp=">Menu</a></td>
        <td><a href="index.php?idp=kontakt">Kontakt</a></td>
        <td><a href="index.php?idp=bierki">Bierki</a></td>
        <td><a href="index.php?idp=zasady">Zasady</a></td>
        <td><a href="index.php?idp=historia">Historia szachów</a></td>
        <td><a href="index.php?idp=fen">Notacja Fen</a></td>
        <td><a href="index.php?idp=filmy">filmy</a></td>
    </tr>
</table>

<?php
include 'cfg.php';
$conn = OpenCon();
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include 'showpage.php';
if($_GET['idp'] == '') $id = 1;
if($_GET['idp'] == 'kontakt') $id = 2;
if($_GET['idp'] == 'zasady') $id = 7;
if($_GET['idp'] == 'historia') $id = 6;
if($_GET['idp'] == 'fen') $id = 4;
if($_GET['idp'] == 'bierki') $id = 3;
if($_GET['idp'] == 'filmy') $id = 5;
echo PokazPdostrone($id, $conn);
?>
</body>
<div id=zegarek></div>
<div id=data></div>
<?php
$nr_indeksu = '164344';
$nrGrupy = '1';
$wersja = 'v1.6';
echo 'Autor: Łukasz B. '.$nr_indeksu.' grupa '.$nrGrupy.' wersja '.$wersja.' <br/><br/>';
?>
</html>
