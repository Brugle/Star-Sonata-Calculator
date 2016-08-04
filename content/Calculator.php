<?php
$Builds = array();
if (!isset($_SESSION['UserID'])) {
	$_SESSION['UserID'] = 0;
}
$Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_SESSION['UserID'] . " AND Type != 3";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Builds[$row['Build_ID']]['Name'] = $row['Name'];
	$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
	$Builds[$row['Build_ID']]['Public'] = $row['Public'];
	$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
	$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
	$Builds[$row['Build_ID']]['Type'] = $row['Type'];
	$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
	$Builds[$row['Build_ID']]['Temp_Mods'] = $row['Temp_Mods'];
}

$Query = "SELECT * FROM Characters WHERE User_ID = " . $_SESSION['UserID'] . " ORDER BY Name"; 
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Characters[$row['Character_ID']]['ID'] = $row['Character_ID'];
	$Characters[$row['Character_ID']]['Name'] = $row['Name'];
	$Characters[$row['Character_ID']]['Class'] = $row['Class'];
	$Characters[$row['Character_ID']]['Focus'] = $row['Focus'];
}

//
//	Insert new Build
if ($_POST['NewBuild'] != "" and isset($_POST['CreateBuild'])) {
	$Query = "INSERT INTO User_Builds (User_ID, Name, Public, Character_ID, Disable, Temp_Mods) VALUES (" . $_SESSION['UserID'] . ",'" . $_POST['NewBuild'] . "', 0, 0, 0, 1)";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	
	unset($Builds);
	$Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_SESSION['UserID'] . " AND Type != 3";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Builds[$row['Build_ID']]['Name'] = $row['Name'];
		$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
		$Builds[$row['Build_ID']]['Public'] = $row['Public'];
		$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
		$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
		$Builds[$row['Build_ID']]['Type'] = $row['Type'];
		$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
		$Builds[$row['Build_ID']]['Temp_Mods'] = $row['Temp_Mods'];
	}
}

$Build_ID = $_POST['Build'];
$Tech_Limit = 0;
$Disable = FALSE;

//
//	If deleting a build
if ($_POST['DeleteBuild'] == "Delete Build" and $Build_ID != 0) {
	echo "<br/>" . $_POST['DeleteBuild'] . ": " . $Build_ID . "<br/>";
	//
	//	Delete Build
	$Query = "DELETE FROM User_Builds WHERE Build_ID = $Build_ID;";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
	//
	//	Delete Items
	$Query = "DELETE FROM User_Build_Items WHERE User_Build_ID = $Build_ID;";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
	//
	//	Delete Mods
	$Query = "DELETE FROM User_Build_Item_Mods WHERE Build_ID = $Build_ID;";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");

	echo "Build deleted!<br/>";
	
	unset($Builds);
	$Query = "SELECT * FROM User_Builds WHERE Type != 3 AND User_ID = " . $_SESSION['UserID'];
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Builds[$row['Build_ID']]['Name'] = $row['Name'];
		$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
		$Builds[$row['Build_ID']]['Public'] = $row['Public'];
		$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
		$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
		$Builds[$row['Build_ID']]['Type'] = $row['Type'];
		$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
	}
	$Build_ID = 0;
	unset($_POST['Build']);
}
	
if ($_POST['Build_ID'] == 0 and isset($_REQUEST['Build_ID'])) {
	$Build_ID = $_REQUEST['Build_ID'];
	
	unset($Builds);
	$Query = "SELECT * FROM User_Builds WHERE Build_ID = " . $Build_ID . " or User_ID = " . $_SESSION['UserID'] . " AND Type != 3";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Builds[$row['Build_ID']]['Name'] = $row['Name'];
		$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
		$Builds[$row['Build_ID']]['Public'] = $row['Public'];
		$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
		$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
		$Builds[$row['Build_ID']]['Type'] = $row['Type'];
		$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
		$Builds[$row['Build_ID']]['Temp_Mods'] = $row['Temp_Mods'];
	}
	if ($Builds[$Build_ID]['Public'] == 1 and $_SESSION['UserID'] != 1) {
		die("<br/><br/><br/>Nice try, but when mom said no you can't go to dad and ask for permission!!");
	}
	unset($Characters);
	$Query = "SELECT * FROM Characters WHERE User_ID = " . $Builds[$Build_ID]['User_ID'] . " ORDER BY Name"; 
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Characters[$row['Character_ID']]['ID'] = $row['Character_ID'];
		$Characters[$row['Character_ID']]['Name'] = $row['Name'];
		$Characters[$row['Character_ID']]['Class'] = $row['Class'];
		$Characters[$row['Character_ID']]['Focus'] = $row['Focus'];
	}
	$Disable = TRUE;
} 


//
//	Full item list
	unset($Item_Names);
	$Item_Names = array(
		"Ship" => "Ships", 
		"Diffuser" => "Diffusers", 
		"Solar_Panel" => "Solar_Panels", 
		"Shield_Charger" => "Shield_Chargers", 
		"Overloader" => "Overloaders", 
		"Capacitor" => "Capacitors", 
		"Cloak" => "Cloaks", 
		"Hull_Expander" => "Hull_Expanders", 
		"Scoop" => "Scoops", 
		"Radar" => "Radars", 
		"Engine" => "Engines", 
		"Shield" => "Shields",
		"Energy" => "Energies",
		"Weapon" => "Weapons",
		"Tractor" => "Tractors",
		"Aura_Generator" => "Aura_Generators",
		"Augmenter" => "Augmenters",
		"Controlbot" => "Controlbots",
		"Exterminator" => "Exterminators",
		"Homing_Beacon" => "Homing_Beacons"
);

//
//
//	Fetch information on all items and mods
$Query = "SELECT * FROM Ships WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Ships[$row['Ship_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Ship_Information[$row['Ship_ID']][$field_name] = $field_value;
	}
}
$Query = "SELECT * FROM Exterminators WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Exterminators[$row['Exterminator_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Exterminator_Information[$row['Exterminator_ID']][$field_name] = $field_value;
	}
}
$Query = "SELECT * FROM Shields WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Shields[$row['Shield_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Shield_Information[$row['Shield_ID']][$field_name] = $field_value;
	}
}
$Query = "SELECT * FROM Energies WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Energies[$row['Energy_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Energy_Information[$row['Energy_ID']][$field_name] = $field_value;
	}
}
$Query = "SELECT * FROM Engines WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Engines[$row['Engine_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Engine_Information[$row['Engine_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Radars WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Radars[$row['Radar_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Radar_Information[$row['Radar_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Weapons WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Weapons[$row['Weapon_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Weapon_Information[$row['Weapon_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Aura_Generators WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Aura_Generators[$row['Aura_Generator_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Aura_Generator_Information[$row['Aura_Generator_ID']][$field_name] = $field_value ;
	}
}
$Aura_Generators_Ally = $Aura_Generators;
$Aura_Generator_Ally_Information = $Aura_Generator_Information;

$Aura_Generators_Ally_2 = $Aura_Generators;
$Aura_Generator_Ally_2_Information = $Aura_Generator_Information;
	
$Query = "SELECT * FROM Augmenters WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Augmenters[$row['Augmenter_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Augmenter_Information[$row['Augmenter_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Overloaders WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Overloaders[$row['Overloader_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Overloader_Information[$row['Overloader_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Scoops WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Scoops[$row['Scoop_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Scoop_Information[$row['Scoop_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Hull_Expanders WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Hull_Expanders[$row['Hull_Expander_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Hull_Expander_Information[$row['Hull_Expander_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Escape_Pods WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Escape_Pods[$row['Escape_Pod_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Escape_Pod_Information[$row['Escape_Pod_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Tractors WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Tractors[$row['Tractor_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Tractor_Information[$row['Tractor_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Capacitors WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Capacitors[$row['Capacitor_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Capacitor_Information[$row['Capacitor_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Cloaks WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Cloaks[$row['Cloak_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Cloak_Information[$row['Cloak_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Shield_Chargers WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Shield_Chargers[$row['Shield_Charger_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Shield_Charger_Information[$row['Shield_Charger_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Diffusers WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Diffusers[$row['Diffuser_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Diffuser_Information[$row['Diffuser_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Solar_Panels WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Solar_Panels[$row['Solar_Panel_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Solar_Panel_Information[$row['Solar_Panel_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Controlbots WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Controlbots[$row['Controlbot_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Controlbot_Information[$row['Controlbot_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Homing_Beacons WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    $Homing_Beacons[$row['Homing_Beacon_ID']] = $row['Name'];
	foreach ($row as $field_name => $field_value) {
		$Homing_Beacon_Information[$row['Homing_Beacon_ID']][$field_name] = $field_value ;
	}
}
$Query = "SELECT * FROM Mods ORDER BY Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Mods[$row['Excel_Mod_ID']]['ID'] = $row['Excel_Mod_ID'];
	$Mods[$row['Excel_Mod_ID']]['Name'] = $row['Name'];
	$Mods[$row['Excel_Mod_ID']]['Value'] = 0;
	$Mods[$row['Excel_Mod_ID']]['Img'] = $row['Img'];
	$Mods[$row['Excel_Mod_ID']]['ToT_Calculation'] = "((";
	${$row['Excel_Mod_ID']."r"} = 1;
}

$Query = "SELECT * FROM Item_Mods ORDER BY Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
  $Item_Mods[$row['Item']][$row['Item_Mods_ID']] = $row['Name'];
	$Item_Dropdown_Mods[$row['Item']][$row['Item_Mods_ID']] = $row['Name'] . " (" . ($row['Mod1_Initial']>0 ? "+" : "-") . $Mods[$row['Mod1_ID']]['Name'] . ($Mods[$row['Mod2_ID']]['Name']!="" ? ", " . ($row['Mod2_Initial']>0 ? "+" : "-") . $Mods[$row['Mod2_ID']]['Name'] : "") . ($Mods[$row['Mod3_ID']]['Name']!="" ? ", " . ($row['Mod3_Initial']>0 ? "+" : "-") . $Mods[$row['Mod3_ID']]['Name'] : "") . ")";
	foreach ($row as $field_name => $field_value) {
		$Item_Mods[$row['Item_Mods_ID']][$field_name] = $field_value ;
	}
}

//	Will get the items for the chosen build
if ($Build_ID != 0) {
	//
	//
	//	Store item IDs
	$Build_Original_Items = array();
	$Query = "SELECT * FROM User_Build_Items WHERE User_Build_ID = " . $Build_ID;
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		if ($row['Item_Type'] != "Aura_Generator_Ally_2") {
			$Item = preg_replace('/[0-9]+/', '', $row['Item_Type']);
		} else {
			$Item = "Aura_Generator_Ally_2";
		}
		$Build_Items[$row['Item_Type']] = ${$Item."_Information"}[$row['Item_ID']];
		$Build_Items[$row['Item_Type']]['Item_ID'] = $row['Item_ID'];
		
		$Build_Original_Items[$row['Item_Type']] = ${$Item."_Information"}[$row['Item_ID']];
		$Build_Original_Items[$row['Item_Type']]['Item_ID'] = $row['Item_ID'];
		$Build_Original_Items[$row['Item_Type']]['Item_Type'] = $row['Item_Type'];
	}
	
	// Store item bonuses
	foreach ($Build_Original_Items as $Item_ID) {
		if ($Item_ID['Item_Type'] == "Aura_Generator_Ally" or $Item_ID['Item_Type'] == "Aura_Generator_Ally_2") {
		} else {
			$Item = preg_replace('/[0-9]+/', '', $Item_ID['Item_Type']);
			$r=0;
			
			$Query = "SELECT * FROM " . $Item . "_Mods WHERE " . $Item . "_ID = " . $Item_ID['Item_ID'];
			$Result = mysql_query($Query, $conn) or die(mysql_error());
			$Last_Row = mysql_num_rows($Result);
			while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
				$Build_Original_Items[$Item_ID['Item_Type']]["[Bonus] " . $Mods[$row['Excel_Mod_ID']]['Name']] = $row['Value'];
				$r++;
			}
		}
	}
	
	//
	//
	//	Store Mod IDs	
	$Query = "SELECT * FROM User_Build_Item_Mods WHERE Build_ID = " . $Build_ID;
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Build_Items[$row['Item_Type']]['Mods'][] = $row['Item_Mod_ID'];
		//$Build_Original_Items[$row['Item_Type']]['[Item Mod] ' . $row['Item_Mod_ID']] = "Value: "; // . $Mods[$Item_Mods[$row['Item_Mod_ID']]['Mod1_ID']]['Name'];
		$Build_Original_Items[$row['Item_Type']]['Mods'][] = $row['Item_Mod_ID'];
		
		//$Build_Original_Items[$row['Item_Type']]['[Item Mod] ' . $Item_Mods[$row['Item_Mod_ID']]['Name']] = $Item_Mods[$row['Item_Mod_ID']]['Mod1_Initial'] + $Item_Mods[$row['Item_Mod_ID']]['Mod1_Tech'] * $Build_Original_Items[$row['Item_Type']]['Tech'];
		//$Build_Original_Items[$row['Item_Type']]['[Item Mod] ' . $Item_Mods[$row['Item_Mod_ID']]['Name']] = $Item_Mods[$row['Item_Mod_ID']]['Mod2_Initial'] + $Item_Mods[$row['Item_Mod_ID']]['Mod2_Tech'] * $Build_Original_Items[$row['Item_Type']]['Tech'];
		//$Build_Original_Items[$row['Item_Type']]['[Item Mod] ' . $Item_Mods[$row['Item_Mod_ID']]['Name']] = $Item_Mods[$row['Item_Mod_ID']]['Mod3_Initial'] + $Item_Mods[$row['Item_Mod_ID']]['Mod3_Tech'] * $Build_Original_Items[$row['Item_Type']]['Tech'];
		//$Build_Original_Items[$row['Item_Type']]['[Item Mod] ' . $Item_Mods[$row['Item_Mod_ID']]['Name']] = $Item_Mods[$row['Item_Mod_ID']]['Mod_Flat_Initial'] + $Item_Mods[$row['Item_Mod_ID']]['Mod_Flat_Tech'] * $Build_Original_Items[$row['Item_Type']]['Tech'];
	}
	
	foreach ($Item_Names as $Item => $Items) {
		if ($Item == "Capacitor" or $Item == "Weapon" or $Item == "Overloader" or $Item == "Hull_Expander") {
			for ($r=1; $r<=50; $r++) {
				if (!isset($Build_Items[$Item . $r]['Mods'])) {
					$Build_Items[$Item . $r]['Mods'] = array();
					$Build_Original_Items[$Item . $r]['Mods'] = array();
				}
			}
		} else {
			if (!isset($Build_Items[$Item]['Mods'])) {
				$Build_Items[$Item]['Mods'] = array();
				$Build_Original_Items[$Item]['Mods'] = array();
			}
		}
	}
	
	// Calculate Mods
	require("includes/mod_calculation.php");
	
	// Fetch number of slots, if a ship is chosen
	// 37 = Weapon hold
	$Ship_Weapon_Slots = floor($Build_Items['Ship']['Weapon_Slots'] * $Mods[37]['Value'] + ($Build_Items['Ship']['Weapon_Slots']<>0 ? $Mods[261]['Value'] : 0));
	$Ship_Augmenter_Slots = $Build_Items['Ship']['Aug_Slots'];
	$Ship_Solar_Panel_Slots = floor($Build_Items['Ship']['Size']/10);
	$Ship_Diffuser_Slots = 3;
	// 227 Capacitor Slot
	$Ship_Capacitor_Slots = 1 * ($Mods[227]['Value'] <> 0 ? $Mods[227]['Value'] : 1);
	$Ship_Overloader_Slots = 1;
	// 228 Hull Expander Slot
	$Ship_Hull_Expander_Slots = 1 * ($Mods[228]['Value'] <> 0 ? $Mods[228]['Value'] : 1);
	
	// Calculate
}

?>
<table style="float:left;" id="MainTable">
	<tr>
        <td>
            <form action="?module=skills&amp;content=Calculator" method="post" id="formBuild">
                <fieldset>
                    <table id="Build_Table">
                        <tr>
                            <td>
                                <p>
																	<input class="input" id="NewBuild" type="text" name="NewBuild" placeholder="Name on new build" value="" />
                                </p>
                            </td>
							<td>
								<input class="submit" style="padding:3.5px 15px;" id="SubmitForm" type="submit" name="CreateBuild" value="Create Build" />
							</td>
						</tr>
						<tr>
                      <td>
								<p>
                                    <select id="Build" class="select" style="width:310px;" data-placeholder="Please select a Build..." name="Build">
                                        <option value=""></option>
                                        <?php
                                        foreach ($Builds as $Build) {
											if ($Build_ID == $Build['ID']) {
												?>
												<option selected="selected" value="<?php echo $Build['ID']; ?>"><?php echo $Build['Name'] . " (" . $Build['ID'] . ")"; ?></option>
												<?php
											} else {
												?>
												<option value="<?php echo $Build['ID']; ?>"><?php echo $Build['Name'] . " (" . $Build['ID'] . ")"; ?></option>
												<?php
											}
                                        }
                                        ?>
                                    </select>
								</p>
                            </td>
							<td>
								<input class="submit" style="padding:3.5px 15px;" id="SubmitForm" type="submit" name="OpenBuild" value="Select Build" />
							</td>
						</tr>
					</table>
				</fieldset>
			</form>
		</td>
	</tr>
	<?php if ($Build_ID > 0) { ?>
		<tr>
        <td>
            <form action="?module=skills&amp;content=Calculator" method="post" id="formID">
                <fieldset>
                    <table class="innerTable" id="Build_Details_Table">
											<tr>
												<td style="background-image:none;background-color:#FFF;" colspan="4">
													<input class="savebutton" style="width: 310px; margin-left: 16px; height: 25px;" type="button" name="submit" value="Save [Calculate]" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?> />
													<br/>
												<input style="width: 310px; margin-left: 16px; height: 25px;" type="submit" name="DeleteBuild" value="Delete Build"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
													<br/><br/>
												</td>
											</tr>
                        <tr>
							<td style="background-image:none;background-color:#FFF;"></td>
                            <td colspan="3">
                                <p>
									<input class="input" id="BuildName" type="text" name="BuildName" placeholder="Build name" value="<?php echo $Builds[$Build_ID]['Name']; ?>"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
                                </p>
                            </td>
						</tr>
                        <tr>
							<td style="background-image:none;background-color:#FFF;"></td>
                            <td colspan="3">
                                <p>
                                    <select id="Public_Build" class="select" style="width:310px;" data-placeholder="Please select a build type..." name="Public_Build" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
										<option <?php echo ($Builds[$Build_ID]['Public'] != 0 ? 'selected="selected"' : "") ?> value="1">Private</option>
										<option <?php echo ($Builds[$Build_ID]['Public'] == 0 ? 'selected="selected"' : "") ?> value="0">Public</option>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
							<td style="background-image:none;background-color:#FFF;"></td>
                            <td colspan="3">
                                <p>
                                    <select id="Disable" class="select" style="width:310px;" data-placeholder="Toggle for filtering unusable items..." name="Disable" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
																			<option <?php echo ($Builds[$Build_ID]['Disable'] == 0 ? 'selected="selected"' : "") ?> value="0">Disable none usable items</option>
																			<option <?php echo ($Builds[$Build_ID]['Disable'] != 0 ? 'selected="selected"' : "") ?> value="1">Allow all items</option>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
							<td style="background-image:none;background-color:#FFF;"></td>
                            <td colspan="3">
                                <p>
                                    <select id="Temp_Mods" class="select" style="width:310px;" data-placeholder="Toggle for including temporary bonuses in calculation..." name="Temp_Mods" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
																			<option <?php echo ($Builds[$Build_ID]['Temp_Mods'] == 0 ? 'selected="selected"' : "") ?> value="0">Exclude temporary bonuses</option>
																			<option <?php echo ($Builds[$Build_ID]['Temp_Mods'] != 0 ? 'selected="selected"' : "") ?> value="1">Include temporary bonuses</option>
                                    </select>
                                </p>
                            </td>
                        </tr>
												<tr>
													<td style="background-image:none;background-color:#FFF;"></td>
													<td colspan="3" style="text-align:left;">
														<p>
															<select id="Character" class="select" style="width:310px;" data-placeholder="Please select a character..." name="Character" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
																<?php
																foreach ($Characters as $Character) {
																?>
																<option <?php echo ($Character['ID']==$Builds[$Build_ID]['Character_ID'] ? 'selected="selected"' : "") ?> value="<?php echo $Character['ID']; ?>"><?php echo $Character['Name']; ?></option>
																<?php	
																}
																?>
															</select>
														</p>
													</td>
												</tr>
												<tr>
													<td style="background-image:none;background-color:#FFF;"></td>
													<td colspan="3" style="text-align:left;">
														<p>
															<select id="Type" class="select" style="width:310px;" data-placeholder="Please select type..." name="Type" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
																<option <?php echo ($Builds[$Build_ID]['Type']==0 ? 'selected="selected"' : "") ?> value="0">Main ship</option>
																<option <?php echo ($Builds[$Build_ID]['Type']==1 ? 'selected="selected"' : "") ?> value="1">Combat Slave</option>
																<option <?php echo ($Builds[$Build_ID]['Type']==2 ? 'selected="selected"' : "") ?> value="2">Trade Slave</option>
															</select>
														</p>
													</td>
												</tr>
                        <tr>
														<td style="" title="Ship"><img src="img/SS_img/Ship.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Ship" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Ship..." name="Ship" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
										<?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Ship']['Item_ID'] . '">' . $Build_Original_Items['Ship']['Name'] . '</option>';
											?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php 
													if ($Build_Original_Items['Ship']['Item_ID'] == "") {
														?><tr><td style="background-image:none;background-color:white;" colspan="3">Please save after chosen a ship.</td></tr><?php
													} else {
														
														if ($Builds[$Build_ID]['Type'] == 1 or $Builds[$Build_ID]['Type'] == 2) { ?>
                        <tr>
							<td title="Controlbot"><img src="img/SS_img/Control-Bot.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Controlbot" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Controlbot..." name="Controlbot" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Controlbot']['Item_ID'] . '">' . $Build_Original_Items['Controlbot']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php } ?>
                        <tr>
							<td title="Shield"><img src="img/SS_img/Shield.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Shield" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Shield..." name="Shield" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Shield']['Item_ID'] . '">' . $Build_Original_Items['Shield']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Shield_Mods" class="multiselect" class="multiselect" data-placeholder="Please select Shield Mods..." name="Shield_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Shield'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Shield']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
                        <tr>
							<td title="Energy"><img src="img/SS_img/Energy.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Energy" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select an Energy source..." name="Energy" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Energy']['Item_ID'] . '">' . $Build_Original_Items['Energy']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Energy_Mods" class="multiselect" data-placeholder="Please select Energy Mods..." name="Energy_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Energy'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Energy']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
                        <tr>
							<td title="Engine"><img src="img/SS_img/Engine.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Engine" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select an Engine..." name="Engine" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Engine']['Item_ID'] . '">' . $Build_Original_Items['Engine']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Engine_Mods" class="multiselect" data-placeholder="Please select Engine Mods..." name="Engine_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Engine'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Engine']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
                        <tr>
							<td title="Radar"><img src="img/SS_img/Radar.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Radar" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Radar..." name="Radar" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Radar']['Item_ID'] . '">' . $Build_Original_Items['Radar']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Radar_Mods" class="multiselect" data-placeholder="Please select Radar Mods..." name="Radar_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Radar'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Radar']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
                        <tr>
							<td title="Scoop"><img src="img/SS_img/Scoop.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Scoop" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Scoop..." name="Scoop" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Scoop']['Item_ID'] . '">' . $Build_Original_Items['Scoop']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Scoop_Mods" class="multiselect" data-placeholder="Please select Scoop mods..." name="Scoop_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Scoop'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Scoop']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
						<?php
							for ($r=1; $r <= $Ship_Hull_Expander_Slots; $r++) {
								?>
                        <tr>
							<td title="Hull_Expander<?php echo $r; ?>"><img src="img/SS_img/Hull-Expansions.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Hull_Expander<?php echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Hull Expander..." name="Hull_Expander<?php echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Hull_Expander'.$r]['Item_ID'] . '">' . $Build_Original_Items['Hull_Expander'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Hull_Expander<?php echo $r; ?>_Mods" class="multiselect" class="multiselect" data-placeholder="Please select Hull Expander Mods..." name="Hull_Expander<?php echo $r; ?>_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Hull_Expander'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Hull_Expander'.$r]['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
						<?php } ?>
                        <tr>
							<td title="Cloak"><img src="img/SS_img/Cloak.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Cloak" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Cloak..." name="Cloak" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Cloak']['Item_ID'] . '">' . $Build_Original_Items['Cloak']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Cloak_Mods" class="multiselect" class="multiselect" data-placeholder="Please select Cloak Mods..." name="Cloak_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Cloak'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Cloak']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
						<?php
							for ($r=1; $r <= $Ship_Capacitor_Slots; $r++) {
								?>
                        <tr>
							<td title="Capacitor<?php echo $r; ?>"><img src="img/SS_img/Capacitors.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Capacitor<?php echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Capacitor..." name="Capacitor<?php echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Capacitor'.$r]['Item_ID'] . '">' . $Build_Original_Items['Capacitor'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td <?php if ($r == $Ship_Capacitor_Slots) { echo 'style="border-bottom:1px solid #ccc;"';} ?>>
                                <p>
                                    <select id="Capacitor<?php echo $r; ?>_Mods" <?php if ($r == $Ship_Capacitor_Slots) { echo 'style="border-bottom:1px solid #ccc;"';} ?> class="multiselect" data-placeholder="Please select Capacitor Mods..." name="Capacitor<?php echo $r; ?>_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Capacitor'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Capacitor'.$r]['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
						<?php
							 }
							for ($r=1; $r <= $Ship_Overloader_Slots; $r++) {
								?>
                        <tr>
							<td title="Overloader<?php echo $r; ?>"><img src="img/SS_img/Overloaders.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Overloader<?php echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select an Overloader..." name="Overloader<?php echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Overloader'.$r]['Item_ID'] . '">' . $Build_Original_Items['Overloader'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php } ?>
                        <tr>
							<td title="Shield_Charger"><img src="img/SS_img/Shield-Chargers.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Shield_Charger" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Shield Charger..." name="Shield_Charger" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Shield_Charger']['Item_ID'] . '">' . $Build_Original_Items['Shield_Charger']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
                                <p>
                                    <select id="Shield_Charger_Mods" class="multiselect" class="multiselect" data-placeholder="Please select Shield Charger Mods..." name="Shield_Charger_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Shield_Charger'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Shield_Charger']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
                        <tr>
							<td title="Tractor"><img src="img/SS_img/Tractor.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Tractor" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Tractor..." name="Tractor" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Tractor']['Item_ID'] . '">' . $Build_Original_Items['Tractor']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td style="border-bottom:1px solid #ccc;">
                                <p>
                                    <select id="Tractor_Mods" class="multiselect" class="multiselect" data-placeholder="Please select Tractor Mods..." name="Tractor_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Tractor'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Tractor']['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
                        <tr>
							<td title="Exterminator"><img src="img/SS_img/Exterminators.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Exterminator" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Exterminator..." name="Exterminator" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Exterminator']['Item_ID'] . '">' . $Build_Original_Items['Exterminator']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
							<td title="Homing_Beacon"><img src="img/SS_img/Lighthouse.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Homing_Beacon" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Homing beacon..." name="Homing_Beacon" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Homing_Beacon']['Item_ID'] . '">' . $Build_Original_Items['Homing_Beacon']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
							<td title="Aura_Generator"><img src="img/SS_img/Aura-Generators.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Aura_Generator" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Aura Generator..." name="Aura_Generator" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Aura_Generator']['Item_ID'] . '">' . $Build_Original_Items['Aura_Generator']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php 
							if ($Build_Original_Items['Ship']['Item_ID'] == "") {
								?>
						<tr><td style="background-image:none;background-color:white;" colspan="3">[Weapon slots] -> Save after chosen a ship.</td></tr>
						<?php
							} else {
							for ($r=1; $r <= $Ship_Weapon_Slots; $r++) {
								?>
                        <tr>
							<td title="Weapon<?php echo $r; ?>"><img src="img/SS_img/Weapon.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Weapon<? echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Weapon..." name="Weapon<? echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Weapon'.$r]['Item_ID'] . '">' . $Build_Original_Items['Weapon'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td <?php echo ($r == $Ship_Weapon_Slots ? 'style="border-bottom:1px solid #ccc;"' : ""); ?>>
                                <p>
                                    <select id="Weapon<?php echo $r;?>_Mods" class="multiselect" data-placeholder="Please select Weapon Mods..." name="Weapon<?php echo $r;?>_Mods[]" multiple <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <?php
                                        foreach ($Item_Dropdown_Mods['Weapon'] as $Item_Mod_ID => $Item_Mod_Name) {
                                            ?>
                                            <option <?php echo (in_array($Item_Mod_ID, $Build_Original_Items['Weapon'.$r]['Mods']) ? 'selected="selected"' : "") ?> value="<?php echo $Item_Mod_ID; ?>"><?php echo $Item_Mod_Name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>
							</td>
                        </tr>
						<?php 
							}
							} ?>
						<?php 
							if ($Build_Original_Items['Ship']['Item_ID'] == "") {
								?>
						<tr><td style="background-image:none;background-color:white;" colspan="3">[Augmenter slots] -> Save after chosen a ship.</td></tr>
						<?php
							} else {
							for ($r=1; $r <= $Ship_Augmenter_Slots; $r++) {
								?>
                        <tr>
							<td title="Augmenter<?php echo $r; ?>"><img src="img/SS_img/Augmenters.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Augmenter<? echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select an Augmenter..." name="Augmenter<? echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Augmenter'.$r]['Item_ID'] . '">' . $Build_Original_Items['Augmenter'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php }} ?>
						<?php
							for ($r=1; $r <= $Ship_Diffuser_Slots; $r++) {
								?>
                        <tr>
												<td title="Diffuser<?php echo $r; ?>"><img src="img/SS_img/Diffusers.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Diffuser<? echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Diffuser..." name="Diffuser<? echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Diffuser'.$r]['Item_ID'] . '">' . $Build_Original_Items['Diffuser'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
													<td <?php echo ($r == $Ship_Diffuser_Slots ? 'style="border-bottom:1px solid #ccc;"' : ""); ?>>
														<input class="input-small" id="Diffuser<?php echo $r; ?>_Amount" type="text" name="Diffuser<?php echo $r; ?>_Amount" placeholder="Amount" value="<?php echo $Build_Original_Items['Diffuser'.$r]['Amount']; ?>" autocomplete="off" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
													</td>
                        </tr>
						<?php } ?>
						<?php 
							if ($Build_Original_Items['Ship']['Item_ID'] == "") {
								?>
						<tr><td style="background-image:none;background-color:white;" colspan="3">[Solar Panel slots] -> Save after chosen a ship.</td></tr>
						<?php
							} else {
							for ($r=1; $r <= $Ship_Solar_Panel_Slots; $r++) {
								?>
                        <tr>
							<td title="Solar_Panel<?php echo $r; ?>"><img src="img/SS_img/Solar-Panels.png"/></td>
                            <td colspan="2" <?php echo ($r == $Ship_Solar_Panel_Slots && 1==2 ? 'style="border-bottom:1px solid #ccc;"' : ""); ?>>
                                <p>
                                    <select id="Solar_Panel<? echo $r; ?>" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Solar Panel..." name="Solar_Panel<? echo $r; ?>" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Solar_Panel'.$r]['Item_ID'] . '">' . $Build_Original_Items['Solar_Panel'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
													<td></td>
                        </tr>
						<?php 
							}
							}
														?>
											     <tr>
							<td title="Aura_Generator_Ally"><img src="img/SS_img/Aura-Generators.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Aura_Generator_Ally" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Allied Aura Generator..." name="Aura_Generator_Ally" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Aura_Generator_Ally']['Item_ID'] . '">' . $Build_Original_Items['Aura_Generator_Ally']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
														 <td></td>
                        </tr>
											     <tr>
							<td title="Aura_Generator_Ally_2"><img src="img/SS_img/Aura-Generators.png"/></td>
                            <td colspan="2" style="border-bottom:1px solid #ccc;">
                                <p>
                                    <select id="Aura_Generator_Ally_2" class="select ajaxChosen" style="width:310px;" data-placeholder="Please select a Allied Aura Generator..." name="Aura_Generator_Ally_2" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Aura_Generator_Ally_2']['Item_ID'] . '">' . $Build_Original_Items['Aura_Generator_Ally_2']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
														 <td></td>
                        </tr>
											<?php
													} ?>
                    </table>
                </fieldset>
				<br/>
				<input class="savebutton" style="width: 310px; margin-left: 16px; height: 25px;" type="submit" name="submit" value="Save [Calculate]"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
				<br/>
				<input style="width: 310px; margin-left: 16px; height: 25px;" type="submit" name="DeleteBuild" value="Delete Build"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
				<br/><br/>
				<br/>
				<br/>
				<table style="display:none;">
					<tr>
						<td>Weapon slots:</td>
						<td><input class="input" id="Weapon_Slots" type="input" name="Weapon_Slots" value="<?php echo $Ship_Weapon_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Augmenter slots:</td>
						<td><input class="input" id="Augmenter_Slots" type="input" name="Augmenter_Slots" value="<?php echo $Ship_Augmenter_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Solar Panel slots:</td>
						<td><input class="input" id="Solar_Panel_Slots" type="input" name="Solar_Panel_Slots" value="<?php echo $Ship_Solar_Panel_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Diffuser slots:</td>
						<td><input class="input" id="Diffuser_Slots" type="input" name="Diffuser_Slots" value="<?php echo $Ship_Diffuser_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Capacitor slots:</td>
						<td><input class="input" id="Capacitor_Slots" type="input" name="Capacitor_Slots" value="<?php echo $Ship_Capacitor_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Overloader slots:</td>
						<td><input class="input" id="Overloader_Slots" type="input" name="Overloader_Slots" value="<?php echo $Ship_Overloader_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Hull Expander slots:</td>
						<td><input class="input" id="Hull_Expander_Slots" type="input" name="Hull_Expander_Slots" value="<?php echo $Ship_Hull_Expander_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Build ID:</td>
						<td><input class="input" id="Build" type="input" name="Build" value="<?php echo $Build_ID; ?>" /></td>
					</tr>
					<tr>
						<td>User_ID:</td>
						<td><input class="input" id="User_ID" type="input" name="User_ID" value="<?php echo $_SESSION['UserID']; ?>" /></td>
					</tr>
				</table>
            </form>
        </td>
    </tr>
	<?php } ?>
</table>
<?php require("includes/ship_window_clean.php"); ?>
<script type="text/javascript">

	var cloneCntr = 1;
	function calculate_stats () { 
		$.ajax({
		url: "includes/Calculation_Output.php?Build_ID=<?php echo $Build_ID; ?>&With_Mods=1",
		cache: false,
		dataType:"json",
		success: function( response ) {
			//console.log("Ajax call was a success.");
			$('div#Ship_Window td[title]').qtip('destroy', true);
			var $ShipWindow = $("#Ship_Window").clone();
			$("#Ship_Window_LastUpdate").remove();
			$ShipWindow.prop('id', "Ship_Window_LastUpdate");			
			$("#Ship_Window").css("display","");
			var timestamp = new Date().getUTCMilliseconds();
			//console.log(timestamp);
			//
			//	Permanent Bonus
			//
			$('#Permanent_Bonus_Table tr:gt(3)').addClass("remove");
			for (var stat in response) {
				if (stat.substring(0, 4) == "Mod_" && stat.substring(stat.length-4, stat.length) == "_Img" && $('#' + stat.substring(0, stat.length-4) + '_Img').length == 0) {
					var row = $("#Permanent_Bonus_Template").clone();
					row.attr("id","").css("display","").appendTo($("#Permanent_Bonus_Table"));
					$('#Permanent_Bonus_Table tr:last-child td:eq(0)').attr("id", stat.substring(0, stat.length-4) + '_Img');
					$('#Permanent_Bonus_Table tr:last-child td:eq(1)').attr("id", stat.substring(0, stat.length-4) + '_Name');
					$('#Permanent_Bonus_Table tr:last-child td:eq(2)').attr("id", stat.substring(0, stat.length-4) + '_Value');
					
					$('#Permanent_Bonus_Table tr:last-child td:eq(2)').attr("title", stat.substring(0, stat.length-4) + '_Value' + timestamp);
				} else if (stat.substring(0, 4) == "Mod_" && stat.substring(stat.length-4, stat.length) == "_Img" && $('#' + stat.substring(0, stat.length-4) + '_Img').length != 0) {
					$('#' + stat.substring(0, stat.length-4) + '_Img').closest("tr").removeClass("remove");
				}
			}
			//
			//	Temporary Self Bonus
			//
			$('#Temporary_Table tr:gt(3)').addClass("remove");
			for (var stat in response) {
				if (stat.substring(0, 14) == "Self_Temp_Mod_" && stat.substring(stat.length-4, stat.length) == "_Img" && $('#' + stat.substring(0, stat.length-4) + '_Img').length == 0) {
					var row = $("#Temporary_Self_Bonus_Template").clone();
					row.attr("id","").css("display","").appendTo($("#Temporary_Table"));
					$('#Temporary_Table tr:last-child td:eq(0)').attr("id", stat.substring(0, stat.length-4) + '_Img');
					$('#Temporary_Table tr:last-child td:eq(1)').attr("id", stat.substring(0, stat.length-4) + '_Name');
					$('#Temporary_Table tr:last-child td:eq(2)').attr("id", stat.substring(0, stat.length-4) + '_Value');
				} else if (stat.substring(0, 14) == "Self_Temp_Mod_" && stat.substring(stat.length-4, stat.length) == "_Img" && $('#' + stat.substring(0, stat.length-4) + '_Img').length != 0) {
					$('#' + stat.substring(0, stat.length-4) + '_Img').closest("tr").removeClass("remove");
				}
			}
			//
			//	Temporary Bonus
			//
			$('#Field_Generation_Table tr:gt(3)').addClass("remove");
			for (var stat in response) {
				if (stat.substring(0, 9) == "Temp_Mod_" && stat.substring(stat.length-4, stat.length) == "_Img" && $('#' + stat.substring(0, stat.length-4) + '_Img').length == 0) {
					var row = $("#Temporary_Bonus_Template").clone();
					row.attr("id","").css("display","").appendTo($("#Field_Generation_Table"));
					$('#Field_Generation_Table tr:last-child td:eq(0)').attr("id", stat.substring(0, stat.length-4) + '_Img');
					$('#Field_Generation_Table tr:last-child td:eq(1)').attr("id", stat.substring(0, stat.length-4) + '_Name');
					$('#Field_Generation_Table tr:last-child td:eq(2)').attr("id", stat.substring(0, stat.length-4) + '_Value');
					$('#Field_Generation_Table tr:last-child td:eq(3)').attr("id", stat.substring(0, stat.length-4) + '_Targets');
				} else if (stat.substring(0, 9) == "Temp_Mod_" && stat.substring(stat.length-4, stat.length) == "_Img" && $('#' + stat.substring(0, stat.length-4) + '_Img').length != 0) {
					$('#' + stat.substring(0, stat.length-4) + '_Img').closest("tr").removeClass("remove");
				}
			}
			$('.remove').remove();
			//
			//	Temporary Bonus
			//
			$('#Weapons_Table tr:gt(2)').remove();
			for (var stat in response) {
				if (stat.substring(0, 6) == "W_Name") {
					var row = $("#Weapon_Table_Row_Template").clone();
					row.attr("id","").css("display","").appendTo($("#Weapons_Table"));
					$('#Weapons_Table tr:last-child td:eq(0)').attr("id", 'W_Image' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(1)').attr("id", 'W_Name' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(2)').attr("id", 'W_Sustainable_Crit_DPS' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(3)').attr("id", 'W_Critical_DPS' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(4)').attr("id", 'W_EPS' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(5)').attr("id", 'W_DPE' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(6)').attr("id", 'W_Damage' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(7)').attr("id", 'W_Recoil' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(8)').attr("id", 'W_Range' + stat.substring(stat.length-1));
					$('#Weapons_Table tr:last-child td:eq(9)').attr("id", 'W_Sustainable' + stat.substring(stat.length-1));
					
					$('#Weapons_Table tr:last-child td:eq(2)').attr("title", 'W_Sustainable_Crit_DPS' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(3)').attr("title", 'W_Critical_DPS' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(4)').attr("title", 'W_EPS' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(5)').attr("title", 'W_DPE' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(6)').attr("title", 'W_Damage' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(7)').attr("title", 'W_Recoil' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(8)').attr("title", 'W_Range' + stat.substring(stat.length-1) + timestamp);
					$('#Weapons_Table tr:last-child td:eq(9)').attr("title", 'W_Sustainable' + stat.substring(stat.length-1) + timestamp);
				}
			}
			//$('.remove').remove();
			//
			//	All other stats
			//
			$("#Weapons_Table").css("display","none");
			$("#Tractor_Table").css("display","none");
			for (var stat in response) {
				if (typeof response["W_Name1"] === 'undefined') {} else {
					$("#Weapons_Table").css("display","");
				}
				
				if (typeof response["T_Name1"] === 'undefined') {} else {
					$("#Tractor_Table").css("display","");
				}
				
				if (response[stat]['Format'] == "Text") {
				} else if (response[stat]['Format'] == "Number") {
					response[stat]['Value'] = numberWithCommas(Math.floor(response[stat]['Value']));
				} else if (response[stat]['Format'] == "Number1") {
					response[stat]['Value'] = numberWithCommas(Math.floor(response[stat]['Value']*100)/100);
				} else if (response[stat]['Format'] == "Percentage0") {
					response[stat]['Value'] = Math.floor((response[stat]['Value'])*100*1)/1 + " %";
				} else if (response[stat]['Format'] == "Percentage1") {
					response[stat]['Value'] = Math.round((response[stat]['Value'])*100*10)/10 + " %";
				} else if (response[stat]['Format'] == "Percentage2") {
					response[stat]['Value'] = Math.round((response[stat]['Value'])*100*100)/100 + " %";
				} else if (response[stat]['Format'] == "Percentage2_Perm_Mod") {
					response[stat]['Value'] = Math.round((response[stat]['Value']-1)*100*100)/100 + " %";
				} else if (response[stat]['Format'] == "Percentage3") {
					response[stat]['Value'] = response[stat]['Value']*100 + " %";
				}
				if ($("#" + stat).html() != response[stat]['Value'] && response[stat]['Format'] != "Text") {
					$("#" + stat).addClass('backgroundAnimated');
				}
				$("#" + stat).html(response[stat]['Value']);
				$('#' + stat).attr("title", stat + timestamp);
				
				var tooltip_text = "";
				for (var line in response[stat]['Tooltip']) {
					if (stat.substring(0,4) == "Mod_") {
						tooltip_text = tooltip_text + response[stat]['Tooltip'][line];	
					} else {
						tooltip_text = tooltip_text + response[stat]['Tooltip'][line] + "<br/>";
					}
				}
				$('div#Ship_Window td[title=' + stat + timestamp + ']').qtip({
					content: {
						overwrite: true,
						text: tooltip_text,
						title: stat.replace("_"," ") + ' calculation!'
					},
					position: {
						my: 'top right',  // Position my top left...
						at: 'top left', // at the bottom right of...
						target: $('#Ship_Window td[title=' + stat + timestamp + ']') // my target
					}
				});
			}
			
			//
			//	AI_Damage table update
			$('#AI_Damage_Body').empty();
			for (var ship in response['AI_Damage']) {
				$('#AI_Damage > tbody:last-child').append('<tr><td>' + response['AI_Damage'][ship]['Name'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['L'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['E'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['H'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['P'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['R'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['S'] + '</td><td style="text-align:right">' + response['AI_Damage'][ship]['M'] + '</td></tr>');
			}
			window.setTimeout(remove_effect_class, 5000);
			$ShipWindow.insertAfter("#Ship_Window");
		},
		error: function (request, status, error) {
			console.log("Ajax call failed.");
		 }
	});
															}
	
	function remove_effect_class() {
		$(".backgroundAnimated").removeClass("backgroundAnimated");
	}
	
	function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return parts.join(".");
}
	
	function fixIds(elem, cntr) {
    $(elem).find("[id]").add(elem).each(function() {
			var CurrentID = this.id;
			this.id = CurrentID.replace(/\d+$/, "") + cntr;
			this.title = "Calculated_" + CurrentID.replace(/\d+$/, "") + cntr + "_Tooltip";
    })
}
	
</script>
<?php 

function array_search_multi($products, $field, $value) { 
	foreach($products as $key=>$product) { 
		if ($product[$field] === $value ) return $key; 
	} 
	return false; 
} 
?>
<script type="text/javascript">
	<?php 
	unset($Item_Names);
	$Item_Names = array(
		"Ship" => "Ships", 
		"Diffuser" => "Diffusers", 
		"Solar_Panel" => "Solar_Panels", 
		"Shield_Charger" => "Shield_Chargers", 
		"Overloader" => "Overloaders", 
		"Capacitor" => "Capacitors", 
		"Cloak" => "Cloaks", 
		"Hull_Expander" => "Hull_Expanders", 
		"Scoop" => "Scoops", 
		"Radar" => "Radars", 
		"Engine" => "Engines", 
		"Shield" => "Shields",
		"Energy" => "Energies",
		"Weapon" => "Weapons",
		"Tractor" => "Tractors",
		"Aura_Generator" => "Aura_Generators",
		"Augmenter" => "Augmenters",
		"Controlbot" => "Controlbots",
		"Exterminator" => "Exterminators",
		"Homing_Beacon" => "Homing_Beacons",
		"Aura_Generator_Ally" => "Aura_Generators_Ally",
		"Aura_Generator_Ally_2" => "Aura_Generators_Ally_2"
);
	
foreach ($Item_Names as $Item => $Items) { 
	if (isset(${"Ship_" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			if ($Build_Original_Items[$Item.$r]['Item_ID'] != "") {
		?>
	$('td[title=<?php echo $Item.$r; ?>]').qtip({
		content: {
			text:'<table><?php if (isset($Build_Original_Items[$Item.$r])) {foreach ($Build_Original_Items[$Item.$r] as $Field => $ID) {if ($Field == "Mods"){echo '<tr><td>Mods:</td><td colspan="2"></td></tr>';foreach ($Build_Original_Items[$Item.$r][$Field] as $Mod_ID){echo '<tr><td colspan="3" style="text-align:left">' . $Item_Dropdown_Mods[$Item][$Mod_ID] . "</td></tr>";}} else { if ($Build_Original_Items[$Item.$r][$Field] != "" and $Build_Original_Items[$Item.$r][$Field] != 0) {echo '<tr>'; echo'<td style="font-weight: bold;">' . str_replace("_"," ",$Field) . ':</td><td>&nbsp;</td><td style="text-align:left;">' . str_replace("'","",$Build_Original_Items[$Item.$r][$Field]) . "</td>"; echo "</tr>";}}}} ?></table>',
			title: '<?php echo str_replace("_"," ",$Item.$r); ?> information!'
		},
		position: {
			my: 'top left',  // Position my top left...
			at: 'top right', // at the bottom right of...
			target: $('td[title=<?php echo $Item.$r; ?>') // my target
		}
	});
<?php 
			}
		}
	} else {
		if ($Build_Original_Items[$Item]['Item_ID'] != "") {
		?>
	$('td[title=<?php echo $Item; ?>]').qtip({
		content: {
			text:'<table><?php if (isset($Build_Original_Items[$Item])) {foreach ($Build_Original_Items[$Item] as $Field => $ID) {if ($Field == "Mods"){echo '<tr><td>Mods:</td><td colspan="2"></td></tr>';foreach ($Build_Original_Items[$Item][$Field] as $Mod_ID){echo '<tr><td colspan="3" style="text-align:left">' . $Item_Dropdown_Mods[$Item][$Mod_ID] . "</td></tr>";}} else { if ($Build_Original_Items[$Item][$Field] != "" and $Build_Original_Items[$Item][$Field] != 0) {echo '<tr>'; echo'<td style="font-weight: bold;">' . str_replace("_"," ",$Field) . ':</td><td>&nbsp;</td><td style="text-align:left;">' . str_replace("'","",$Build_Original_Items[$Item][$Field]) . "</td>"; echo "</tr>";}}}} ?></table>',
			title: '<?php echo str_replace("_"," ",$Item); ?> information!'
		},
		position: {
			my: 'top left',  // Position my top left...
			at: 'top right', // at the bottom right of...
			target: $('td[title=<?php echo $Item; ?>') // my target
		}
	});
<?php 
		}
	} 
}
	?>

</script>

<script type="text/javascript">
    $(function() {
        //Ber browsere ikke fylle inn informasjon automatisk
        $("input").attr("autocomplete", "off");

        // Jquery UI på skjema knappene
        $("input[type=submit], input[type=button]").button();

        $(".select").chosen({
        	allow_single_deselect: true,
					inherit_select_classes: true
        });
        //$("#Temp_Bonus_State").button();
        //$(".multiselect").chosen({allow_single_deselect: true});
        $(".multiselect").multiselect({
        	selectedList: 4 // 0-based index
        });

        $("#formID").on("click", ".savebutton", function(event) {
					event.preventDefault();
					//console.log( $( this ).serialize() );
					$.ajax({
						url: "includes/save_build.php?submit=1&" + $("#formID").serialize(),
						cache: false,
						dataType: "json",
						success: function(response) {
							$('<div id="FormFeedback" class="ui-state-highlight ui-corner-all" style="margin:0;font-size:10px;float:none;margin-left:auto;margin-right:auto;"><span class="ui-icon ui-icon-info" style="float:left; margin-right: .3em;"></span>Build was saved!</div>').insertBefore("#MainTable");
							calculate_stats();
							setTimeout(function() {
								if ($('#FormFeedback').length > 0) {
									$('#FormFeedback').remove();
								}
							}, 2000);
							if ($('#Ship').val() != '' && $('#Shield').length === 0) {
								window.location.reload(true); 
								//console.log($('#Ship').val() + " - " + $('#Shield').length + " - " + ($('#Ship').val() != '' && $('#Shield').length === 0));
							}
						},
						error: function(response) {
							console.log("Saving the build didn't work");
						}
					});
        });
				
        if (1 == <?php echo ($Disable == TRUE ? '1' : "0"); ?>) {
        	calculate_stats();
        }

        });		var running = false;
		var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		  };
		})();
		
    function reloadwindow() {
        var r = confirm("Are you sure you want to reset the form?\n\n All changes will be lost.");
        if (r == true)
        {
            window.location.reload();
        }
    }
</script>
<script>
	$(function() {
		$('#show_AI_damage').on('click', function(){
			$('#AI_Damage').toggle();
		});
		
		//setup before functions
		var typingTimer; //timer identifier
		var doneTypingInterval = 250; //time in ms, 5 second for example
		var $chosen = $('.ajaxChosen.chosen-container');
		var $chosen_element;

		//on keyup, start the countdown
		$chosen.on('keyup', function(e) {
			var regex = new RegExp("^[a-zA-Z0-9]+$");
			var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
			if (regex.test(str) || str == "%") {
				regex = new RegExp("^[^a-zA-Z0-9+%]+$");
				$(this).find('input').val($(this).find('input').val().replace(regex, ''));
				if ($(this).find('input').val().length > 0) {
					$chosen_element = this;
					clearTimeout(typingTimer);
					typingTimer = setTimeout(doneTyping, doneTypingInterval);
				}
			}
		});

		//on keydown, clear the countdown 
		$chosen.on('keydown', function() {
			$(this).find('option').empty();
			clearTimeout(typingTimer);
		});

		//user is "finished typing," do something
		function doneTyping() {
			var searchparam = $($chosen_element).find('input').val();
			var select = $($chosen_element).closest('p').find('select');
			var currenttext = $(select).find("option:selected").text();
			var currentvalue = $(select).val();
			var object = $(select).get(0);
			var chosen_type = $(object).attr('id');
			chosen_type = chosen_type.replace(/[0-9]/g, '');
			$(select).empty();
			$(select).append($("<option></option>").val(0).html(""));
			$(select).append($("<option value=" + currentvalue + " selected></option>").html(currenttext));
			$.getJSON("includes/dropdown_option_fetcher.php?build=<?=$Build_ID?>&searchparam=" + searchparam + "&type=" + chosen_type, {}, function(data) {
				$.each(data, function(i, obj) {
					if (obj["ID"] != currentvalue) {
						if (obj["Disabled"] == "FALSE") {
							$(object).append($("<option></option>").val(obj["ID"]).html(obj["Name"]));
						} else {
							$(object).append($('<option disabled="disabled"></option>').val(obj["ID"]).html(obj["Name"]));
						}
					}
				}), searchparam = $($chosen_element).find('input').val(), $(object).trigger("chosen:updated"), $($chosen_element).find('input').val(searchparam), running = false;
			});
		}
	});
</script>