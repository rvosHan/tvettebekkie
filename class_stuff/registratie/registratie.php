<?php

$melding = '';  // nog niks te melden

// check voor de knop
if(isset($_POST['registeren'])) {
    $fouten = [];
    // 1. inlezen gegevens uit form
    $naam       = $_POST['naam'];
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoordcontrole = $_POST['wachtwoordControle'];
    $first_name = $_POST['voornaam'];
    $last_name = $_POST['achternaam'];
    $address = $_POST['adres'];
    $role = $_POST['role'];

    // 2. controleren van de gegevens
    if($wachtwoord != $wachtwoordcontrole){
        $fouten[] = 'De wachtwoorden komen niet overeen.';
    }

    if(strlen($naam) < 4) {
        $fouten[] = 'Gebruikersnaam minstens 4 karakters.';
    }

    if(strlen($wachtwoord) < 8) {
        $fouten[] = 'Wachtwoord minstens 8 karakters.';
    }

    // 3. opslaan van de gegevens
    if(count($fouten) > 0) {
        $melding = "Er waren fouten in de invoer.<ul>";
        foreach($fouten as $fout) {
            $melding .= "<li>$fout</li>";
        }
        $melding .= "</ul>";

    } else {
        $melding = "Geen fouten, nu nog de gegevens opslaan.";
        require_once("db_connectie.php");
        $db = maakVerbinding();

        $passwordhash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        
       // $melding = "password plaintext: $wachtwoord | password hash: $passwordhash";
        $sql = "INSERT INTO Users(username, password, first_name, last_name, address, role) values (:naam, :passwordhash, :first_name, :last_name, :address, :role)";
        $query = $db->prepare($sql);
        $data_array = [
            ':naam' => $naam,
            ':passwordhash' => $passwordhash,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':address' => $address,
            ':role' => $role
        ];
        $succes = $query->execute($data_array);
        if($succes){
            $melding .= "De gebruiker $naam is toegevoegd aan het database";
        }else{
            $melding .= "De gebruiker $naam is niet toegevoegd aan het database";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
</head>
<body>
    <form method="post" action="">
        <table>
        <tr>
                <td><label for="voornaam">Voornaam</label></td>
                <td><input type="text" id="voornaam" name="voornaam"></td>
            </tr>
            <tr>
                <td><label for="achternaam">Achternaam</label></td>
                <td><input type="text" id="achternaam" name="achternaam"></td>
            </tr>
            <tr>
                <td><label for="adres">Adres</label></td>
                <td><input type="text" id="adres" name="adres"></td>
            </tr>
            <tr>
                <td><label for="naam">Gebruikersnaam</label></td>
                <td><input type="text" id="naam" name="naam"></td>
            </tr>
            <tr>
                <td><label for="wachtwoord">wachtwoord</label></td>
                <td><input type="password" id="wachtwoord" name="wachtwoord"></td>
            </tr>
            <tr>
                <td><label for="wachtwoordControle">Wachtwoordcontrole</label></td>
                <td><input type="password" id="wachtwoordControle" name="wachtwoordControle"></td>
            </tr>
         
            <tr>
               <td><label for="role">Rol</label></td>
               <td> 
                    <select name="role" autocomplete="on"> 
                        <option value="">-- Selecteer de Rol van de nieuwe gebruiker --</option> 
                        <option value="Client">Client</option> 
                        <option value="Personnel">Personnel</option> 
                    </select> 
                </td>
            </tr>
            
            <tr>
                <td> </td>
                <td><input type="submit" name="registeren" value="registreren"></td>
            </tr>
        </table>
    </form>
    <?=$melding?>
</body>
</html>