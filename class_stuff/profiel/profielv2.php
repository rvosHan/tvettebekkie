<?php
session_start();
require_once('functions.php');
$profieldata = '';

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){
    //er is ingelogd en de gegevens in de sessie komen overeen met wat er verwacht wordt.
    $username = $_SESSION['username'];
    $uidSession = $_SESSION['uid'];

    include_once('../db_connectie.php');
    $db = maakVerbinding();
    if(ingelogd($username, $uidSession, $db)){
        
    $sql = "SELECT password, first_name, last_name, address, role FROM users WHERE username = :username";
    //$sql = "SELECT username FROM users WHERE username = :username AND password = :password LIMIT 1"
    $query = $db->prepare($sql);
    $query->execute([':username' => $username]);
    $row = $query->fetch();
    if($row){
        //invulling van profielgegevens
        $profieldata = "
            <table>
                <th>
                    <td colspan=2>
                        Persoonsgegevens
                    </td>
                </th>
                <tr>
                    <td>Gebruikersnaam: </td>
                    <td>$username</td>
                </tr>
                <tr>
                    <td>Voornaam: </td>
                    <td>".$row['first_name']."</td>
                </tr>
                <tr>
                    <td>Achternaam: </td>
                    <td>".$row['last_name']."</td>
                </tr>
                <tr>
                    <td>Adres: </td>
                    <td>".$row['address']."</td>
                </tr>
                <tr>
                    <td>Functie</td>
                    <td>".$row['role']."</td>
                </tr>
            </table>
        ";
    }else{
        //sessiongegevens komen toch niet overeen met het database.
        cleanSession();
    }
    }
}
else{
    cleanSession();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profielpagina</title>
</head>
<body>
    <h1>Welkom, <?=$username?></h1>
    <?=$profieldata?>

    <div class="uitloggen">
        <a href='index.php?logout'>Uitloggen</a>
    </div>
</body>
</html>