<?php
session_start();
session_destroy();
session_start();
$bericht = '';
require_once('db_connectie.php');
require_once('functions.php');
$db = '';

if(isset($_POST['cmdBestel'])){
    vernieuwWinkelmand();
}

//opbouw van tijdelijke sessiegegevens.
$_SESSION['winkelmandje']['Coca Cola'] = 4;
$_SESSION['winkelmandje']['Sprite'] = 3;
$_SESSION['winkelmandje']['Knoflookbrood'] = 1;


/*
Winkelmandje uit sessie op basis van string

$winkelmandje = "Coca Cola-4,Sprite-3,Knofloopbrood-1";
$producten = explode(",", $winkelmandje);

$productenlijst = '<table>';
foreach($producten as $product){
    $productInformatie = explode("-", $product);
    $productenlijst .= '<tr>';
    $productenlijst .= '<td>';  
    $productenlijst .= $productInformatie[0];
    $productenlijst .= '</td>';
    $productenlijst .= '<td>';   
    $productenlijst .=     $productInformatie[1];
    $productenlijst .= '</td>';
    $productenlijst .= '</tr>';
}
$productenlijst .= '</table>';
*/


/*
Winkelmandje uit sessie op basis van Non-Associatief Array
$winkelmandje = [['Coca Cola', 4],['Sprite', 3],['Knoflookbrood', 1]];

$productenlijst = '<table>';
foreach($winkelmandje as $product){
    $productenlijst .= '<tr>';
    $productenlijst .= '<td>';  
    $productenlijst .= $productInformatie[0];
    $productenlijst .= '</td>';
    $productenlijst .= '<td>';   
    $productenlijst .=     $productInformatie[1];
    $productenlijst .= '</td>';
    $productenlijst .= '</tr>';
}
$productenlijst .= '</table>';
*/

if(isset($_SESSION['winkelmandje'])){
//hier komt code als er wel iets in het winkelmandje staat.
$db = maakVerbinding();

$viewWinkelmand = '';
$viewWinkelmandItems = '';
$totaalPrijs = 0;

$dataWinkelmandje = $_SESSION['winkelmandje'];
foreach($dataWinkelmandje as $productnaam => $aantal){
    $productInformatie = getProductInformatie($productnaam, $db);
    $subtotaal = ($aantal * $productInformatie['price']);
    $totaalPrijs = $totaalPrijs + $subtotaal;
    $viewWinkelmandItems .= '
            <tr>
                <td><img src="https://placehold.co/200x180/png" alt="'.$productnaam.'"> Informatie en iets over de '.$productnaam.'</td>
                <td><input type="number" name="[winkelmandje]['.$productnaam.']" value="'.$aantal.'"></td>
                <td>'.moneyformat($productInformatie['price']).'</td>
                <td>'.moneyformat($subtotaal).'</td>
            </tr>
    ';
    
}

$btw = ($totaalPrijs /100 * 9);
$viewWinkelmand .= '
<form action="winkelwagen.php" method="post">
        <table>
            <tr>
                <th>Product</th>
                <th>Aantal</th>
                <th>Prijs</th>
                <th>Subtotaal</th>
            </tr>
            '.$viewWinkelmandItems.'
            <tr><td colspan="4" style="background-color: #D32F2F;"></td></tr>
            <tr>
                <td colspan="3" style="text-align: right;">Subtotaal : </td>
                <td>'.moneyformat(($totaalPrijs - $btw)).'</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">BTW : </td>
                <td>'.moneyformat($btw).'</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Totaal : </td>
                <td style="background-color: #D32F2F;  font-weight: 700;">'.moneyformat($totaalPrijs).'</td>
            </tr>
            <tr><td colspan="4" style="background-color: #D32F2F;"></td></tr>

        </table>

                <input type="text" name="voornaam" placeholder="Voornaam">
        <input type="text" name="adres" placeholder="6511JB, 11">
        <input type="submit" name="cmdBestel" value="Plaats bestelling">
       </form>
';

$bericht = $viewWinkelmand;
}
else{
//hier is de winkelmand leeg.
$bericht = 'De winkelmand is leeg.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eetcafé 't Vette Bekkie</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/huisstijlVetteBekkie2025.css">
</head>
<body>
    <div class="container">
        <div class="header"><h1>Welkom bij Eetcafé <span class="media_uiting aandacht">'t Vette Bekkie</span></h1></div>
        <div class="menu">
            <ul>
                <li><a href="#">Aanbiedingen</a></li>
                <li><a href="#">Over Ons</a></li>
                <li>
                    <a href="#">Menukaart</a>
                    <ul>
                        <li><a href="#">Eten</a></li>
                        <li><a href="#">Drinken</a></li>
                    </ul>
                </li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Inloggen</a></li>
            </ul>
        </div>
        <div class="reclame_links reclame">
            <img src="img/side_burger_1.gif" alt="aanbieding 1">
            <img src="img/side_burger_2.gif" alt="aanbieding 2">
            <img src="img/side_burger_3.gif" alt="aanbieding 3">
        </div>
        <div class="main"><section><h1>Winkelwagen</h1>
        <article><h2>Uw bestelling</h2><p>Hier vindt u informatie over uw bestelling. De aantallen en producten kunt u wijzigen zolang de bestelling nog niet doorgestuurd is.</p>
     
<?=$bericht?>

        </article>

        </section></div>
        <div class="reclame_rechts reclame">
          <div class="media_uiting">
            <section><h3>Reclame</h3><article>Hier wat geschreven reclame blaat blaat blaat.</article></section>
          </div>

          <div class="media_uiting">
            <section><h3>Reclame 2</h3><article>Hier wat geschreven reclame blaat blaat blaat.</article></section>
          </div>

        </div>
        <div class="footer">'t Vette Bekkie 2025&copy;&nbsp; | &nbsp; Lees onze <a href='#'>verwerkingsovereenkomst</a></div>
    </div>
</body>
</html>
