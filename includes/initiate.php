<?php
  //
  //  Setter variabler, kobler opp databasen og sjekker login.
  //
  $debug = true;
  $root_folder = $_SERVER['DOCUMENT_ROOT'] . '/StarSonata';
  $root_folder_html = "";
  // Må endres i følgende filer 

  if ($debug === true) {
      ini_set('display_errors', 'On');
  }

  //
  //  Åpner databasen
  //
  require_once ($root_folder . '/includes/database/opendb.php');

  //
  //  Autentiserer hvis man ikke er sperret ute.
  //
  if ($_REQUEST['content'] == "403forbidden") {

  } else {
      require_once ($root_folder . '/includes/authentication/authenticate.php');
  }
?>