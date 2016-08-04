<?php 

if(!empty($_GET['searchparam']) and !empty($_GET['type']))
{
	
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
		"Shield" => "Shields",
		"Engine" => "Engines", 
		"Energy" => "Energies",
		"Weapon" => "Weapons",
		"Tractor" => "Tractors",
		"Aura_Generator" => "Aura_Generators",
		"Augmenter" => "Augmenters",
		"Controlbot" => "Controlbots",
		"Exterminator" => "Exterminators",
		"Homing_Beacon" => "Homing_Beacons",
		"Aura_Generator_Ally" => "Aura_Generators_Ally",
		"Aura_Generator_Ally_2" => "Aura_Generators_Ally_2",
		// Bases
		"Base" => "Bases", 
		"Base_Diffuser" => "Base_Diffusers", 
		"Base_Solar_Panel" => "Base_Solar_Panels", 
		"Base_Shield_Charger" => "Base_Shield_Chargers", 
		"Base_Overloader" => "Base_Overloaders", 
		"Base_Capacitor" => "Base_Capacitors", 
		"Base_Hull_Expander" => "Base_Hull_Expanders", 
		"Base_Radar" => "Base_Radars", 
		"Base_Shield" => "Base_Shields",
		"Base_Energy" => "Base_Energies",
		"Base_Weapon" => "Base_Weapons",
		"Base_Aura_Generator" => "Base_Aura_Generators",
		"Base_Augmenter" => "Base_Augmenters",
		"Base_Overcharger" => "Base_Overchargers",
		"Base_Extractor" => "Base_Extractors"
);
	
	if (empty($_GET['types'])) {
		$_GET['types'] = $Item_Names[$_GET['type']];
	}
	
	$root_folder = $_SERVER['DOCUMENT_ROOT'] . '/StarSonata';
	require_once ($root_folder . '/includes/database/opendb.php');

	$Search_Build = mysql_real_escape_string($_GET['build']);
	$Search_For = mysql_real_escape_string($_GET['searchparam']);
	$Search_Type = mysql_real_escape_string($_GET['type']);
	$Search_Types = mysql_real_escape_string($_GET['types']);
	
	if ($Search_Type == "Aura_Generator_Ally" or $Search_Type == "Aura_Generator_Ally_2") {
		$Search_Type = "Aura_Generator";
		$Search_Types = "Aura_Generators";
	}
	

		$Query = "SELECT * FROM User_Builds WHERE Build_ID = " . $Search_Build;
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Character_ID = $row['Character_ID'];
			$User_ID = $row['User_ID'];
			$Disable = $row['Disable'];
		}

		$Query = "SELECT * FROM User_Skills WHERE User_ID = " . $User_ID . " and Character_ID = " . $Character_ID;
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Skills[$row['Skill_ID']]['Level'] = $row['Skill_Level'];
		}
	
	if ($Search_Type != "Ship" and substr($Search_Type,0,4) != "Base") {
		$Query = "SELECT Item_ID FROM User_Build_Items WHERE User_Build_ID = " . $Search_Build . ' AND Item_Type = "Ship"';
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Ship_ID = $row['Item_ID'];
		}

		$Query = "SELECT Ship_Type, Tech FROM Ships WHERE Ship_ID = " . $Ship_ID;
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Ship_Tech = $row['Tech'];	
			if ($row['Ship_Type'] == "Industrial Freighter" or $row['Ship_Type'] == "Support Freighter") {
				$Ship_Type = "Freighter";	
			} else {
				$Ship_Type = $row['Ship_Type'];
			}
		}
	}
	if ($Search_Type == "Augmenter" or $Search_Type == "Shield" or $Search_Type == "Overloader" or $Search_Type == "Diffuser" or $Search_Type == "Energy" or $Search_Type == "Hull_Expander") {
		$Query = "Select " . $Search_Type . "_ID as ID, Name as Name, Tech, Require_Skill_ID, Require_Skill_Level, Require_Ship From $Search_Types WHERE Name LIKE '%$Search_For%' ORDER BY Tech, Name";
	} else if ($Search_Type == "Ship") {
		$Query = "Select " . $Search_Type . "_ID as ID, Name as Name, Tech, Require_Skill_ID, Require_Skill_Level From $Search_Types WHERE Name LIKE '%$Search_For%' ORDER BY Tech, Name";
	} else {
		$Query = "Select " . $Search_Type . "_ID as ID, Name as Name, Tech From $Search_Types WHERE Name LIKE '%$Search_For%' ORDER BY Tech, Name";
	}
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 

	$rows = array();
	$r=0;
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$rows[$r]['ID'] = $row['ID'];
		$rows[$r]['Name'] = $row['Name'];
		if ($row['Require_Ship'] == "Industrial Freighter" or $row['Require_Ship'] == "Support Freighter") {
				$row['Require_Ship'] = "Freighter";	
		}
		$rows[$r]['Disabled'] = "FALSE";

		if (($row['Require_Skill_ID'] != 0 and $Skills[$row['Require_Skill_ID']]['Level'] < $row['Require_Skill_Level'])) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Req. higher skill)";
		}
		if($row['Require_Ship'] != "" and $row['Require_Ship'] != $Ship_Type) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Req. " . $row['Require_Ship'] . ")";
		}
		if ($Search_Type == "Augmenter" and $row['Tech'] > $Ship_Tech) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Req. ship tech >" . $row['Tech'] . ")";
		} 
		if ($Search_Type == "Ship" and $row['Tech'] > $Skills[1]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Piloting)";
		} 
		if ($Search_Type == "Weapon" and $row['Tech'] > $Skills[2]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Weaponry)";
		} 
		if ($Search_Type == "Shield" and $row['Tech'] > $Skills[3]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Shielding)";
		} 
		if ($Search_Type == "Engine" and $row['Tech'] > $Skills[4]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Engine skill)";
		} 
		if ($Search_Type == "Radar" and $row['Tech'] > $Skills[5]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Radar skill)";
		} 
		if ($Search_Type == "Cloak" and $row['Tech'] > $Skills[6]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Cloak skill)";
		} 
		if ($Search_Type == "Energy" and $row['Tech'] > $Skills[7]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Electrical engineering)";
		} 
		if ($Search_Type == "Tractor" and $row['Tech'] > $Skills[8]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Tractoring)";
		} 
		if ($Search_Type == "Diffuser" and $row['Tech'] > $Skills[9]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Equipment skill)";
		}
		if ($Search_Type == "Capacitor" and $row['Tech'] > $Skills[9]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Equipment skill)";
		}
		if ($Search_Type == "Scoop" and $row['Tech'] > $Skills[9]['Level']) {
			$rows[$r]['Disabled'] = "TRUE";
			$rows[$r]['Name'] = $row['Name'] . " (Need " . $row['Tech'] . " Equipment skill)";
		}
		
		if ($Disable == 1) {
			$rows[$r]['Disabled'] = "FALSE";
		}

		$r++;
	}
	mysql_free_result($Result);
	echo json_encode($rows);
}
?>