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
?>