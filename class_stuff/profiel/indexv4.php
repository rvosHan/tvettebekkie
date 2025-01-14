<?php
session_start();

$melding = '';

    if(isset($_GET) &&)

    if(isset($_POST['login'])){ //er is op de knop gedrukt

        $username = $_POST['username']; //gebruikersnaam om mee in te loggen (door user)
        $password = $_POST['password']; //wachtwoord gekoppeld aan de gebruikersnaam. (door user )

        $fouten= [];

        if(strlen($username) < 4){
            $fouten[] = 'De gebruikersnaam is korter dan verwacht';
        }
        if(strlen($password) < 8){
            $fouten[] = 'Het wachtwoord is korter dan verwacht';
        }

        if(count($fouten) > 0) {
            $melding = "Er waren fouten in de invoer.<ul>";
            foreach($fouten as $fout) {
                $melding .= "<li>$fout</li>";
            }
            $melding .= "</ul>";
        } else{
            //de ingevoerde gegevens zijn volgens onze verwachtingen. Probeer te vergelijken met de gegevens uit het database.
            include_once('../db_connectie.php');
            $db = maakVerbinding();

            $sql = "SELECT password FROM users WHERE username = :username";
            //$sql = "SELECT username FROM users WHERE username = :username AND password = :password LIMIT 1"
            $query = $db->prepare($sql);
            $query->execute([':username' => $username]);
            $row = $query->fetch();
            if($row){
                //er is een resultaat
                //controleren van wachtwoord:
                $userpassFromDB = $row['password']; //variabele met hash die is opgeslagen in het database
                if(password_verify($password, $userpassFromDB)){
                    //het wachtwoord komt overeen met de gebruikersnaam
                    $melding = 'combinatie is correct';
                    $_SESSION['username'] = $username;
                    $_SESSION['loggedIn'] = true;

                    header("location: profiel.php");
                    exit(0);
                }
                else{
                    $melding = 'Gebruiker- wachtwoordcombinatie komt niet voor.';
                }
            } else{
                //gebruikersnaam komt niet voor.
                $melding = 'Gebruiker- wachtwoordcombinatie komt niet voor.';
            }

        }



        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inlog Profielpagina</title>
</head>
<body>
<form method="post" action="">
        <table>
        <tr>
                <td><label for="username">Gebruikersnaam</label></td>
                <td><input type="text" id="username" name="username"></td>
            </tr>
            <tr>
                <td><label for="password">Wachtwoord</label></td>
                <td><input type="password" id="password" name="password"></td>
            </tr>
            <tr>
                <td> </td>
                <td><input type="submit" name="login" value="Inloggen"></td>
            </tr>
        </table>
</form>

<?=$melding?>

</body>
</html>