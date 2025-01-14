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
        
        $username = "evisscher";
    $userProfileInfo = getUserProfileInfo($username, $db);
    if($userProfileInfo){
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
                    <td>".$userProfileInfo['first_name']."</td>
                </tr>
                <tr>
                    <td>Achternaam: </td>
                    <td>".$userProfileInfo['last_name']."</td>
                </tr>
                <tr>
                    <td>Adres: </td>
                    <td>".$userProfileInfo['address']."</td>
                </tr>
                <tr>
                    <td>Functie</td>
                    <td>".$userProfileInfo['role']."</td>
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