<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/StarSonata/includes/initiate.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Star Sonata Calculator</title>
				<link rel="shortcut icon" href="http://www.andsimo.com/StarSonata/img/favicon.ico"/>
        <!-- Stylesheets -->
        <link type="text/css" rel="stylesheet" href="/StarSonata/css/reset.css" />
        <link type="text/css" rel="stylesheet" href="/StarSonata/css/main.css" />
        <link type="text/css" rel="stylesheet" href="/StarSonata/html_elements/Meny/Meny v2.css" />
        <link type="text/css" rel="stylesheet" href="/StarSonata/css/jquery-ui.theme.css" />
      
        <link type="text/css" rel="stylesheet" href="/StarSonata/css/jquery-plugins/jquery.qtip.css" />
        <link type="text/css" rel="stylesheet" href="/StarSonata/css/jquery-plugins/chosen.css" />
        <link type="text/css" rel="stylesheet" href="/StarSonata/css/jquery-plugins/jquery.multiselect.css" />
				<link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />
				<link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css" />
			  <link type="text/css" rel="stylesheet" href="/StarSonata/css/SS_Calculator.css" />

        <!-- Scripts -->
        <script type="text/javascript" src="/StarSonata/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="/StarSonata/js/jquery-ui.min.js"></script>
				<script type="text/javascript" src="/StarSonata/js/plugins/chosen/chosen.jquery.js"></script>
				<script type="text/javascript" src="/StarSonata/js/plugins/multiselect/jquery.multiselect.js"></script>
        <script type="text/javascript" src="/StarSonata/js/plugins/qtip2/jquery.qtip.js"></script>
				<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
				<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.min.js"></script>
				<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
				<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
    </head>
    <body>
        <?php
        if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === false) and $_REQUEST['content'] != "item_viewer" and !($_REQUEST['content'] == "Calculator" && isset($_REQUEST['Build_ID']))) {
            ?>
            <div id="Login-Header">
                <h1>Login</h1>
            </div>
            <?php
        } else {
            ?>
            <?php require_once ($root_folder . '/html_elements/Meny/Meny v2.php'); ?>
            <?php
        }
        ?>
        <div class="wrapper">
            <?php
          if ($_REQUEST['content'] == "Calculator" && isset($_REQUEST['Build_ID']) ) {
						require ("content/" . $_REQUEST['content'] . ".php");
					} else if (!$_SESSION['logged_in'] && $_REQUEST['content'] != "register" && $_REQUEST['content'] != "403forbidden" && $_REQUEST['content'] != "item_viewer") {
							require ("content/login.php");
					} else if (isset($_REQUEST['content'])) {
							require ("content/" . $_REQUEST['content'] . ".php");
					} else {
							require ("content/Forside.php");
					}
            ?>
        </div>
        <?php require_once ($root_folder . '/includes/database/closedb.php'); ?>
    </body>
</html>
