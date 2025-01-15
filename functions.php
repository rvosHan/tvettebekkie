<?php
function getProductInformatie($productnaam, $db){
    $productInformatie = '';
    $sql = "SELECT price FROM Product WHERE name = :productnaam;";
    $query=$db->prepare($sql);
    $query->execute([':productnaam' => $productnaam]);
    $productInformatie = $query->fetch();
    //nog geen foutafhandeling
    return $productInformatie;
}

function moneyformat($value){
    $newValue = '&euro; ' . number_format($value, 2, ',', ' ' );
    return $newValue;
}

function printArrayOnLocation($arrayToBePrinted){
    
    echo '<div style="position: absolute; top: 10px; border: 2px solid black; right: 0px; background-color: hsla(24, 100%, 59%, 0.306);"><pre>';
    print_r($arrayToBePrinted);
    echo '<pre></div>';
}
?>