<?php
//niet echo'en.

function cleanSession(){
    session_unset();
    session_destroy();
    forward();
    exit(0);
}


//functie voor het doorsturen van gebruikers naar een gewenste locatie ($location parameter met index als standaard) middels header
function forward($location = 'index.php'){
    header("location: $location");
}

function ingelogd($username, $uidSession, $db){
    $ingelogd = false;
    $sql = "SELECT password FROM users WHERE username = :username";
    //$sql = "SELECT username FROM users WHERE username = :username AND password = :password LIMIT 1"
    $query = $db->prepare($sql);
    $query->execute([':username' => $username]);
    $row = $query->fetch();
    if($row){
        if(password_verify(($username . $row['password']), $uidSession))
        {
            $ingelogd = true;
        } else {
            cleanSession();
        }
    }
    else{
        cleanSession();
    }
    return $ingelogd;
}
?>