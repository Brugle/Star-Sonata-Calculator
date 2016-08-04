<?php
$Output['Start_Time'] = microtime(true);
function array_search_multi($products, $field, $value) { 
	foreach($products as $key=>$product) { 
		if ($product[$field] === $value ) return $key; 
	} 
	return false; 
} 

require_once($_SERVER['DOCUMENT_ROOT'] . '/StarSonata/includes/initiate.php');
$Build_ID = $_REQUEST['Build_ID'];
$With_Mods = (isset($_REQUEST['With_Mods']) ? $_REQUEST['With_Mods'] : 1);
$With_Tooltip = (isset($_REQUEST['With_Tooltip']) ? $_REQUEST['With_Tooltip'] : 1);

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

if ($_SESSION['UserID'] == 1) {
	$Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_SESSION['UserID'] . " or Build_ID = " . $Build_ID;
} else {
	$Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_SESSION['UserID'] . " or (Build_ID = " . $Build_ID . " AND Public = 0)";		
}

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
if (!isset($Builds)) {
	die(json_encode(array("","")));
}

//
//  Fetch items
//
$Tech_Limit = 0;
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
//
//  Fetch Items END
//

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
	$Build_Items[$row['Item_Type']]['Amount'] = $row['Amount'];

	$Build_Original_Items[$row['Item_Type']] = ${$Item."_Information"}[$row['Item_ID']];
	$Build_Original_Items[$row['Item_Type']]['Item_ID'] = $row['Item_ID'];
	$Build_Original_Items[$row['Item_Type']]['Item_Type'] = $row['Item_Type'];
}
$Output['Before_Mod_Calc'] = microtime(true);
require("mod_calculation.php");
$Output['After_Mod_Calc'] = microtime(true);
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

$Output['Before_Calc'] = microtime(true);
require("calculation.php");
$Output['After_Calc'] = microtime(true);
//$Temp = $Output['Start_Time'];
//unset($Output);
//$Output['Start_Time'] = $Temp;
$Output['Weapon_Slots_Cell']['Value'] = "Weapon stats [Weapon slots: " . $N_Weapons . "/" . $Ship_Weapon_Slots . "]";
$Output['Weapon_Slots_Cell']['Format'] = "Text";

$Output['Capacity']['Value'] = number_format($Calculated_Hull_Space, 0, ".", "");
$Output['Capacity']['Format'] = "Number";
$Output['Capacity']['Tooltip'] = $Calculated_Tooltip['Capacity'];
	
$Output['Used_Capacity']['Value'] = number_format($Calculated_Used_Space, 0, ".", "");
$Output['Used_Capacity']['Format'] = "Number";
$Output['Used_Capacity']['Tooltip'] = $Calculated_Tooltip['Capacity'];

$Output['Speed']['Value'] = number_format($Calculated_Speed, 0, ".", " ");
$Output['Speed']['Format'] = "Number";
$Output['Speed']['Tooltip'] = $Calculated_Tooltip['Speed'];

$Output["Weight"]['Value'] = $Calculated_Weight;
$Output["Weight"]['Format'] = "Number";
$Output["Weight"]['Tooltip'] = $Calculated_Tooltip['Weight'];

$Output["Shield_Bank"]['Value'] = $Calculated_Shield_Bank;
$Output["Shield_Bank"]['Format'] = "Number";
$Output["Shield_Bank"]['Tooltip'] = $Calculated_Tooltip['Shield'];

$Output["Recovery"]['Value'] = $Calculated_Recovery;
$Output["Recovery"]['Format'] = "Number";
$Output["Recovery"]['Tooltip'] = $Calculated_Tooltip['Recovery'];

$Output["Energy_Bank"]['Value'] = $Calculated_Energy_Bank;
$Output["Energy_Bank"]['Format'] = "Number";
$Output["Energy_Bank"]['Tooltip'] = $Calculated_Tooltip['Energy'];
	
$Output["Electricity"]['Value'] = $Calculated_Electricity;
$Output["Electricity"]['Format'] = "Number";
$Output["Electricity"]['Tooltip'] = $Calculated_Tooltip['Electricity'];

$Output["Electricity_w_sun"]['Value'] = $Calculated_Electricity_Sun;
$Output["Electricity_w_sun"]['Format'] = "Number";
$Output["Electricity_w_sun"]['Tooltip'] = $Calculated_Tooltip['Electricity_w_sun'];

$Output["Turning"]['Value'] = $Calculated_Turning;
$Output["Turning"]['Format'] = "Number";
$Output["Turning"]['Tooltip'] = $Calculated_Tooltip['Turning'];
	
$Output["Acceleration"]['Value'] = $Calculated_Acceleration;
$Output["Acceleration"]['Format'] = "Number";
$Output["Acceleration"]['Tooltip'] = $Calculated_Tooltip['Acceleration'];

$Output["Visibility"]['Value'] = $Calculated_Visibility;
$Output["Visibility"]['Format'] = "Number";
$Output["Visibility"]['Tooltip'] = $Calculated_Tooltip['Visibility'];

$Output['Reflectivity']['Value'] = $Build_Items["Ship"]['Reflectivity'];
$Output['Reflectivity']['Format'] = "Percentage2";
$Output["Reflectivity"]['Tooltip'] = $Calculated_Tooltip['Reflectivity'];
	
$Output["Vision"]['Value'] = $Calculated_Vision;
$Output["Vision"]['Format'] = "Number";
$Output["Vision"]['Tooltip'] = $Calculated_Tooltip['Vision'];

$Output["Detection"]['Value'] = $Calculated_Detection;
$Output["Detection"]['Format'] = "Number";
$Output["Detection"]['Tooltip'] = $Calculated_Tooltip['Detection'];

$L=1;
unset($Max_Crit_DPS, $Max_Damage_Type);

for ($r=1; $r<=$Ship_Weapon_Slots; $r++) { 
	if ($Calculated_Name[$r] != "") {
		$Output["W_Image".$L]['Value'] = '<img style="width:9px;" src="img/SS_img/' . $Calculated_Type[$r] . '-Damage.png"/>';
		$Output["W_Image".$L]['Format'] = "Text";
		
		$Output['W_Type'.$L]['Value'] = $Calculated_Type[$r];
		$Output['W_Type'.$L]['Format'] = "Text";

		$Output["W_Name".$L]['Value'] = $Calculated_Name[$r];
		$Output["W_Name".$L]['Format'] = "Text";

		$Output["W_Damage".$L]['Value'] = $Calculated_Damage[$r];
		$Output["W_Damage".$L]['Format'] = "Number";
		$Output["W_Damage".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Damage'.$r];

		$Output["W_Self_Damage".$L]['Value'] = $Calculated_Self_Damage[$r];
		$Output["W_Self_Damage".$L]['Format'] = "Number";
		$Output["W_Self_Damage".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Damage'.$r];

		$Output["W_Recoil".$L]['Value'] = $Calculated_Weapon_Recoil[$r];
		$Output["W_Recoil".$L]['Format'] = "Number1";
		$Output["W_Recoil".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Recoil'.$r];

		$Output["W_Electricity".$L]['Value'] = $Calculated_Weapon_Electricity[$r];
		$Output["W_Electricity".$L]['Format'] = "Number";
		$Output["W_Electricity".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Electricity'.$r];

		$Output["W_EPS".$L]['Value'] = $Calculated_Weapon_EPS[$r];
		$Output["W_EPS".$L]['Format'] = "Number1";
		$Output["W_EPS".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_EPS'.$r];

		$Output["W_DPS".$L]['Value'] = $Calculated_DPS[$r];
		$Output["W_DPS".$L]['Format'] = "Number1";
		$Output["W_DPS".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_DPS'.$r];

		$Output["W_Critical_DPS".$L]['Value'] = $Calculated_Crit_DPS[$r];
		$Output["W_Critical_DPS".$L]['Format'] = "Number1";
		$Output["W_Critical_DPS".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_DPS'.$r];
		
		if ($Max_Crit_DPS < abs($Output["W_Critical_DPS".$L]['Value'])) {
			$Max_Crit_DPS = $Output["W_Critical_DPS".$L]['Value'];
			$Max_Damage_Type = $Output['W_Type'.$L]['Value'];
		}

		$Output["W_DPE".$L]['Value'] = $Calculated_DPE[$r];
		$Output["W_DPE".$L]['Format'] = "Number1";
		$Output["W_DPE".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_DPE'.$r];

		$Output["W_Range".$L]['Value'] = $Calculated_Range[$r];
		$Output["W_Range".$L]['Format'] = "Number";
		$Output["W_Range".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Range'.$r];

		
		
		$Output["W_Sustainable".$L]['Value'] = $Calculated_Sustainable[$r];
		if (is_numeric($Calculated_Sustainable[$r])) {
		$Output["W_Sustainable".$L]['Format'] = "Number";
		} else {
		 $Output["W_Sustainable".$L]['Format'] = "Text";
		}
		$Output["W_Sustainable".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Sustainable'.$r];

		
		
		$Output["W_Sustainable_DPS".$L]['Value'] = $Calculated_Sustainable_DPS[$r];
		if (is_numeric($Calculated_Sustainable_DPS[$r])) {
		$Output["W_Sustainable_DPS".$L]['Format'] = "Number";
		} else {
		 $Output["W_Sustainable_DPS".$L]['Format'] = "Text";
		}
		$Output["W_Sustainable_DPS".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Sustainable_DPS'.$r];
		
		
		
		$Output["W_Sustainable_Crit_DPS".$L]['Value'] = $Calculated_Sustainable_Crit_DPS[$r];
		if (is_numeric($Calculated_Sustainable_Crit_DPS[$r])) {
		$Output["W_Sustainable_Crit_DPS".$L]['Format'] = "Number";
		} else {
		 $Output["W_Sustainable_Crit_DPS".$L]['Format'] = "Text";
		}
		$Output["W_Sustainable_Crit_DPS".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r];
		
		

		$Output["W_Sustainable_DPS_with_Sun".$L]['Value'] = $Calculated_Sustainable_DPS_wSun[$r];
		if (is_numeric($Calculated_Sustainable_DPS_wSun[$r])) {
		$Output["W_Sustainable_DPS_with_Sun".$L]['Format'] = "Number";
		} else {
		 $Output["W_Sustainable_DPS_with_Sun".$L]['Format'] = "Text";
		}
		$Output["W_Sustainable_DPS_with_Sun".$L]['Tooltip'] = $Calculated_Tooltip['Weapon_Sustainable_DPS_with_Sun'.$r];
		
		$L+=1;
	}
}
$r=1;
if ($Build_Items["Tractor"]['Name'] != "") {
  $Output["T_Name".$r]['Value'] = $Build_Items["Tractor"]['Name'];
	$Output["T_Name".$r]['Format'] = "Text";
	
  $Output["T_Strength".$r]['Value'] = $Build_Items["Tractor"]['Strength'];
	$Output["T_Strength".$r]['Format'] = "Number";
	
	$Output["T_Density".$r]['Value'] = $Build_Items["Tractor"]['Density'];
	$Output["T_Density".$r]['Format'] = "Number";
	
  $Output["T_Range".$r]['Value'] = $Build_Items["Tractor"]['T_Range'];
	$Output["T_Range".$r]['Format'] = "Number";
	
  $Output["T_Electricity".$r]['Value'] = $Build_Items["Tractor"]['Electricity'];
	$Output["T_Electricity".$r]['Format'] = "Number";
	
	$Output["T_Rest_Length".$r]['Value'] = $Build_Items["Tractor"]['Rest_Length'];
	$Output["T_Rest_Length".$r]['Format'] = "Number";
	
  $Output["T_SPE".$r]['Value'] = $Build_Items["Tractor"]['SPE'];
	$Output["T_SPE".$r]['Format'] = "Number1";
	
  $Output["T_Sustainable".$r]['Value'] = $Build_Items["Tractor"]['Sustainable'];
	if (is_numeric($Build_Items["Tractor"]['Sustainable'])) {
		$Output["T_Sustainable".$r]['Format'] = "Number";	
	} else {
		$Output["T_Sustainable".$r]['Format'] = "Text";
	}
	
}
$r=1;
foreach ($Damage_Types as $Damage_Type) {
	$Output["Resistance_" . $Damage_Type]['Value'] = $Resistance[$r]['Value']/100;
	$Output["Resistance_" . $Damage_Type]['Format'] = "Percentage0";
	$Output["Resistance_" . $Damage_Type]['Tooltip'] = $Calculated_Tooltip['Resistance_' . $Damage_Type];
	$Avg_Resistance += $Output["Resistance_" . $Damage_Type]['Value'];
  $r++;
}
$Avg_Resistance = $Avg_Resistance / $r;
				
if ($With_Mods == 1) {
  foreach ($Mods as $Mod_ID => $Mod_Name) {
    if ($Mods[$Mod_ID]['Value'] <> 1 and $Mods[$Mod_ID]['Value'] <> "") {
			if (strpos($Mods[$Mod_ID]['Name'],"Capital") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Fighter") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Freighter") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Augmenter") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Slave") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Resistance_") === FALSE and $Mod_ID <> 910 and strpos($Mods[$Mod_ID]['Name'],"Neuro") === FALSE  and strpos($Mods[$Mod_ID]['Name'],"Station") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Permanent") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Projectil") === FALSE)  {
				$Tooltip = "";
				$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Img"]['Value'] = '<img style="width:12px;position:relative;top:2px;" src="img/SS_img/' . ($Mods[$Mod_ID]['Img'] != "" ? $Mods[$Mod_ID]['Img'] : "Hullspace") . '.png"/>';
				$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Img"]['Format'] = "Text";
				
				$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Name"]['Value'] = $Mods[$Mod_ID]['Name'];
				$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Name"]['Format'] = "Text";
				
				$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Value"]['Value'] = $Mods[$Mod_ID]['Value'];
				if (strpos($Mods[$Mod_ID]['Name'],"Bonus") === FALSE) {
					$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Value"]['Format'] = "Percentage2_Perm_Mod";
				} else {
					$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Value"]['Format'] = "Number";
				}
				
				$Tooltip = str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . " multiplier: " . number_format($Mods[$Mod_ID]['Value'],3) . "<br/><br/>";
				$Tooltip .= str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . " calculation: " . $Mods[$Mod_ID]['ToT_Calculation'] . "<br/><br/>";
				for ($r=0; $r<= ${$Mod_ID . "r"}; $r++) { 
					$Tooltip .= str_replace("'", "", $Mods[$Mod_ID]['Calculation_'.$r]) . "<br/>";
				}
				$Output["Mod_" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . "_Value"]['Tooltip'] = $Tooltip;
			}
    }
  }
}

for ($r=1; $r<=15; $r++) {
	if ($Calculated_Temp_Bonus[$r]['Value'] <> 0 and $Calculated_Temp_Bonus[$r]['Self'] == "Yes") {
		$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Img"]['Value'] = '<img style="width:12px;position:relative;top:2px;" src="img/SS_img/' . ($Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] != "" ? $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] : "Hullspace") . '.png"/>';
		$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Img"]['Format'] = "Text";
		
		$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Name"]['Value'] = $Calculated_Temp_Bonus[$r]['Name'];
		$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Name"]['Format'] = "Text";
			
		$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Value"]['Value'] += $Calculated_Temp_Bonus[$r]['Value'];
		if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
			$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Value"]['Format'] = "Percentage1";
		} else {
			$Output["Self_Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Value"]['Format'] = "Number";
		}
		
	} else if ($Calculated_Temp_Bonus[$r]['Value'] <> 0 and $Calculated_Temp_Bonus[$r]['Self'] != "Yes") {
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Img"]['Value'] = '<img style="width:12px;position:relative;top:2px;" src="img/SS_img/' . ($Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] != "" ? $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] : "Hullspace") . '.png"/>';
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Img"]['Format'] = "Text";
		
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Name"]['Value'] = $Calculated_Temp_Bonus[$r]['Name'];
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Name"]['Format'] = "Text";
		
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Value"]['Value'] += $Calculated_Temp_Bonus[$r]['Value'];
		if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
			$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Value"]['Format'] = "Percentage1";
		} else {
			$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Value"]['Format'] = "Number";
		}
		
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Targets"]['Value'] = $Calculated_Temp_Bonus[$r]['Targets'];
		$Output["Temp_Mod_" . str_replace(" ", "_", $Calculated_Temp_Bonus[$r]['Name']) . "_Targets"]['Format'] = "Text";
	}
}

if ($Builds[$Build_ID]['User_ID'] == $_SESSION['UserID']) {
	if (!isset($Max_Crit_DPS)) {
		$Max_Crit_DPS =0;
	}
	$Query = "UPDATE User_Builds SET Updated = NULL, Energy_Bank = " . $Output['Energy_Bank']['Value'] . ", Electricity = " . $Output['Electricity']['Value'] . ", Shield_Bank = " . $Output['Shield_Bank']['Value'] . ", Recovery = " . $Output['Recovery']['Value'] . ", DPS = " . $Max_Crit_DPS . ", Damage_Type = '" . $Max_Damage_Type . "', Speed = " . $Output['Speed']['Value'] . ", Avg_Resistance = " . $Avg_Resistance . ", Capacity = " . $Output['Capacity']['Value'] . "  WHERE Build_ID = " . $Builds[$Build_ID]['ID'] . " AND User_ID = " . $Builds[$Build_ID]['User_ID'];
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
}

if ($With_Tooltip == 0) {
	foreach ($Output as $Key => $Value) {
		unset($Output[$Key]['Tooltip']);
	}
}

$AI_Ships['Forgone'] = array(
	'Name' => "Forgone",
	'L' => 0.6,
	'E' => 0.6,
	'H' => 0.6,
	'P' => 0.6,
	'R' => 0.7,
	'S' => 0.2,
	'M' => 0.4
);

$AI_Ships['Big Green'] = array(
	'Name' => "Big Green",
	'L' => 0.75,
	'E' => 0.75,
	'H' => 0.75,
	'P' => 0.75,
	'R' => 0.5,
	'S' => 0.75,
	'M' => 0.65
);

$AI_Ships['Parsley'] = array(
	'Name' => "Parsley",
	'L' => 0.3,
	'E' => 0.6,
	'H' => 0.3,
	'P' => 0.3,
	'R' => 0,
	'S' => 0,
	'M' => 0.3
);

$AI_Ships['Slumberchrome'] = array(
	'Name' => "Slumberchrome",
	'L' => 0.5,
	'E' => 0.5,
	'H' => 0.7,
	'P' => 0.5,
	'R' => 0.8,
	'S' => 0,
	'M' => 0.3
);

for ($L=1; $L<=$Ship_Weapon_Slots; $L++) {
	foreach ($AI_Ships as $AI_Ship) {
		$AI_Ship = $AI_Ship['Name'];
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['Name'] = $AI_Ships[$AI_Ship]['Name'];
		
		switch ($Output["W_Type".$L]['Value']) {
			case 'Laser':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['L']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['L']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['L'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['L']),2);
				}
				break;
			case 'Energy':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['E']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['E']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['E'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['E']),2);
				}
				break;
			case 'Heat':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['H']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['H']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['H'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['H']),2);
				}
				break;
			case 'Physical':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['P']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['P']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['P'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['P']),2);
				}
				break;
			case 'Radiation':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['R']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['R']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['R'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['R']),2);
				}
				break;
			case 'Surgical':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['S']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['S']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['S'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['S']),2);
				}
				break;
			case 'Mining':
				if ($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['M']) > $Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['M']) {
					$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['M'] = round($Output["W_DPS".$L]['Value'] * (1 - $AI_Ships[$AI_Ship]['M']),2);
				}
				break;
		}	
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['L']+=0;
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['E']+=0;
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['H']+=0;
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['P']+=0;
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['R']+=0;
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['S']+=0;
		$Output['AI_Damage'][$AI_Ships[$AI_Ship]['Name']]['M']+=0;
	}
}


if ($_REQUEST['str_output'] == 1) {
	echo "<pre>";
	print_r($Output);
	echo "</pre>";
} else {
	echo json_encode($Output);
}
?>