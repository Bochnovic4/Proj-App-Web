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
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if($_GET['idp'] == '') $strona = 'html/home.html';
if($_GET['idp'] == 'kontakt') $strona = 'html/kontakt.html';
if($_GET['idp'] == 'zasady') $strona = 'html/zasady.html';
if($_GET['idp'] == 'historia') $strona = 'html/historia.html';
if($_GET['idp'] == 'fen') $strona = 'html/fen.html';
if($_GET['idp'] == 'bierki') $strona = 'html/bierki.html';
if($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';
if (file_exists($strona)) {
    include($strona);
}
else {
    echo 'Plik '.$strona.' nie istnieje. <br/><br/>';
}
?>
</body>
<div id=zegarek></div>
<div id=data></div>
<?php
$nr_indeksu = '164344';
$nrGrupy = '1';

echo 'Autor: Łukasz B. '.$nr_indeksu.' grupa '.$nrGrupy.'<br/><br/>';
?>
</html>
