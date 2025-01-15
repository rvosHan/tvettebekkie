<?php
// defined in 'variables.env'
$db_host = 'database_server'; // de database server 
$db_name = 'pizzeria';                    // naam van database

// defined in sql-script 'movies.sql'
$db_user    = 'sa';                 // db user
$db_password = 'abc123!@#';  // wachtwoord db user

// Het 'ssl certificate' wordt altijd geaccepteerd (niet overnemen op productie, verder dan altijd "TrustServerCertificate=1"!!!)
$verbinding = new PDO('sqlsrv:Server=' . $db_host . ';Database=' . $db_name . ';ConnectionPooling=0;TrustServerCertificate=1', $db_user, $db_password);

// Bewaar het wachtwoord niet langer onnodig in het geheugen van PHP.
unset($db_password);

// Zorg ervoor dat eventuele fouttoestanden ook echt als fouten (exceptions) gesignaleerd worden door PHP.
$verbinding->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Functie om in andere files toegang te krijgen tot de verbinding.
function maakVerbinding() {
  global $verbinding;
  return $verbinding;
}
$db = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <style>
        
span {
  min-width: 5px;
  display: inline-block;
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.85em;
  letter-spacing: 1.5px;
  color: #FFF;
}

body {
  background: #111;
  position: relative;
}

body, html {
   height: 100%;
}

.text {
  overflow: hidden;
  height: auto;
}
        </style>
</head>
<body>
    <p class="text" data-text="
=========================Invoking Script=========================
    /n /n    #Connecting with Database Server
    /nConnected with localhost. 
    /n      #Selecting Database: Pizzeria
    <?php $db = maakVerbinding();?>
    /nConnected with Pizzeria: database-connection 200-OK.
    /n      #Selecting table dbo.Pizza_Order
    /nTable dbo.Pizza_Order, selected
    /n      #Editing table: ALTER TABLE pizzeria.dbo.Pizza_Order ALTER COLUMN client_name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL; 
    <?php  $db->exec('ALTER TABLE pizzeria.dbo.Pizza_Order ALTER COLUMN personnel_username nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL');?>
    /nTable altered: client_name can be set to Null.
    /n=========================Setting Up New Function=========================
    /n /n function rewriteStatus($status){
    /n      $returnStatus = '';
    /n          switch($status){
    /n              case 1:
    /n                    $returnStatus = 'Bestelling is toegewezen.';
    /n                  break;
    /n              case 2:
    /n                    $returnStatus = 'Bestelling wordt klaargemaakt.';
    /n                  break;
    /n              case 3:
    /n                    $returnStatus = 'Bestelling wordt bezorgd.';
    /n                  break;
    /n              case 4:
    /n                    $returnStatus = 'Bestelling is afgerond';
    /n                  break;
    /n              case 'Bestelling is toegewezen.':
    /n                    $returnStatus = 1;
    /n                  break;
    /n              case 'Bestelling wordt klaargemaakt.':
    /n                    $returnStatus = 2;
    /n                  break;
    /n              case 'Bestelling wordt bezorgd.':
    /n                    $returnStatus = 3;
    /n                  break;
    /n              case 'Bestelling is afgerond':
    /n                    $returnStatus = 4;
    /n                  break;
    /n              default:
    /n                  $returnStatus = 'Null' //Bestelling is nog niet opgepakt.
    /n            }
    /n=========================Done Setting Up New Function=========================
    /nClosing Connection
    /n$this->connection->query('KILL CONNECTION_ID()');
    /n$this->connection = null;
    /n/n=========================Done=========================
    <?php $db = null; ?>
    "></p>
    <script>
        var printText = $('.text').data('text');

var contentArray = printText.split('/n');
$.each(contentArray, function(index, newLine) {
  $('.text').append('<span style="display:block;" id="'+index+'"></span>');
  
  var lineID = index;
  var self = $(this);
    setTimeout(function () {
      $.each(self, function(index, chunk){
          setTimeout(function () {
            $('#'+lineID).append("<span>"+chunk+"</span>");
            $('body, html').scrollTop($(document).height());
          }, index*5);
      });
      
    }, index*100);
});
        </script>
</body>
</html>