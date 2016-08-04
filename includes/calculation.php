<?php

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
//	Added specific item mod bonuses
$Calculated_Tooltip['Energy'][0] = "";
$Calculated_Tooltip['Energy'][1] = "";
foreach ($Calc_Item_Mods as $Item_Mod) {
	$Item = preg_replace('/[0-9]+/', '', $Item_Mod['Item_Type']);
	if ($Item_Mod['Global'] != "X") {
		$Item_Mod_Applied = FALSE;
		$Item_Mod_Applied_2 = FALSE;
		if ($Item == "Shield") {
			if ($Item_Mod['Bonus1_ID'] == 25) {
				$Build_Items[$Item_Mod['Item_Type']]['Bank'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
		}
		if ($Item == "Engine") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 29) {
				$Build_Items[$Item_Mod['Item_Type']]['Thrust'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
		}
		if ($Item == "Capacitor") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Scoop") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Energy") {
			if ($Item_Mod['Bonus1_ID'] == 9) {
				$Build_Items[$Item_Mod['Item_Type']]['Energy_Bank'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
				//$Mods[$Item_Mod['Bonus1_ID']]['Calculation_'.${$Mod_ID."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " = " . ($Item_Mod['Bonus1_Value']+1);
				//${$Mod_ID."r"}++;
			}
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
		}
		if ($Item == "Radar") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 192) {
				$Build_Items[$Item_Mod['Item_Type']]['Ping_Bonus'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 10) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 20) {
				$Build_Items[$Item_Mod['Item_Type']]['Detection'] *= (1+$Item_Mod['Bonus1_Value']);
				$Build_Items[$Item_Mod['Item_Type']]['Vision'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
		}
		if ($Item == "Cloak") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 10) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Hull_Expander") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 14) {
				$Build_Items[$Item_Mod['Item_Type']]['Extra_Space'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
		}
		if ($Item == "Tractor") {
			if ($Item_Mod['Bonus1_ID'] == 56) {
				$Build_Items[$Item_Mod['Item_Type']]['T_Range'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 57) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 31) {
				$Build_Items[$Item_Mod['Item_Type']]['Strength'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
		}
		if ($Item == "Shield_Charger") {
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 26) {
				$Build_Items[$Item_Mod['Item_Type']]['Recovery'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 10) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Weapon") {
			if ($Item_Mod['Bonus1_ID'] == 151) {
				$Build_Items[$Item_Mod['Item_Type']]['Recoil'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 4) {
				$Build_Items[$Item_Mod['Item_Type']]['Damage_Max'] *= (1+$Item_Mod['Bonus1_Value']);
				$Build_Items[$Item_Mod['Item_Type']]['Damage_Min'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 22) {
				$Build_Items[$Item_Mod['Item_Type']]['W_Range'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 10) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus1_ID'] == 30) {
				//$Build_Items[$Item_Mod['Item_Type']]['Electricity'] *= (1+$Item_Mod['Bonus1_Value']);
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 122) {
				$Build_Items[$Item_Mod['Item_Type']]['Size'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 4) {
				$Build_Items[$Item_Mod['Item_Type']]['Damage_Max'] *= (1+$Item_Mod['Bonus2_Value']);
				$Build_Items[$Item_Mod['Item_Type']]['Damage_Min'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
			if ($Item_Mod['Bonus2_ID'] == 38) {
				$Build_Items[$Item_Mod['Item_Type']]['Weight'] *= (1+$Item_Mod['Bonus2_Value']);
				$Item_Mod_Applied_2 = TRUE;
			}
			if ($Item_Mod_Applied == FALSE and $Item_Mod['Bonus1_ID'] <> "") {
				echo "The item mod [" . $Item_Mod['Name'] . " - " . $Item_Mod['Item_Type'] . "] where not applied, please report on SS forum<br/>";
			}
			if ($Item_Mod_Applied_2 == FALSE and $Item_Mod['Bonus2_ID'] <> "") {
				echo "The item mod [" . $Item_Mod['Name'] . " - " . $Item_Mod['Item_Type'] . "] where not applied, please report on SS forum<br/>";
			}
		}
	}
	if ($Item_Mod['Global'] == "X") {
		$Item_Mod_Applied = FALSE;
		if ($Item == "Shield") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Engine") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Capacitor") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Scoop") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Energy") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Radar") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Cloak") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Hull_Expander") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
			if ($Item_Mod['Bonus_Flat_ID'] == 195) {
				$Build_Items[$Item_Mod['Item_Type']]['Electricity'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item == "Weapon") {
			if ($Item_Mod['Bonus_Flat_ID'] == 36) {
				$Build_Items[$Item_Mod['Item_Type']]['Visibility'] += $Item_Mod['Bonus_Flat_Value'];
				$Item_Mod_Applied = TRUE;
			}
		}
		if ($Item_Mod_Applied == FALSE and $Item_Mod['Bonus_Flat_ID'] <> "") {
			echo "The item mod [" . $Item_Mod['Name'] . " - " . $Item_Mod['Item_Type'] . "] where not applied, please report on SS forum<br/>";
		}
	}
}

// Speed
$Calculated_Speed = min(($Build_Items['Ship']['Max_Speed'] * $Mods[27]['Value']) + $Mods[225]['Value'], 1010);
$Calculated_Tooltip['Speed'][] = "Top Speed: " . $Calculated_Speed . "<br/>";
$Calculated_Tooltip['Speed'][] = "Top Speed Calculation: min((" . $Build_Items['Ship']['Max_Speed'] . " * " . $Mods[27]['Value'] . "), 1010)<br/>";
$Calculated_Tooltip['Speed'][] = "Initial speed: " . $Build_Items['Ship']['Max_Speed'];
$Calculated_Tooltip['Speed'][] = "Speed multiplier: " . $Mods[27]['Value'];
$Calculated_Tooltip['Speed'][] = "Temporary Speed Bonus: " . $Mods[225]['Value'];
$Calculated_Tooltip['Speed'][] = "Max Speed: 1010";
$Calculated_Tooltip['Speed'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Hull Space
$Calculated_Tooltip['Capacity'][0] = "";
$Calculated_Tooltip['Capacity'][1] = "";
$Calculated_Tooltip['Capacity'][2] = "";
$Calculated_Tooltip['Capacity'][3] = "";
$Calculated_Tooltip['Capacity'][4] = "";
$Calculated_Tooltip['Capacity'][5] = "";
$Calculated_Tooltip['Capacity'][6] = "";
$Calculated_Tooltip['Capacity'][7] = "";
$Calculated_Tooltip['Capacity'][8] = "";

foreach ($Item_Names as $Item => $Items) {
	if ($Item == "Ship") {

	} elseif ($Item == "Hull_Expander" or $Item == "Weapon" or $Item == "Solar_Panel" or $Item == "Diffuser" or $Item == "Overloader" or $Item == "Capacitor") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			
			$Total_Size += $Build_Items[$Item . $r]['Size'] * $Build_Items[$Item . $r]['Amount'];
			$Extra_Space += floor($Build_Original_Items[$Item . $r]['Size'] - $Build_Original_Items[$Item . $r]['Size'] * $Mods[122]['Value']) * $Build_Items[$Item . $r]['Amount'];
			if ($Item == "Weapon") {
				$Extra_Space_Weapon += floor($Build_Original_Items[$Item . $r]['Size'] - $Build_Original_Items[$Item . $r]['Size'] * $Mods[260]['Value']);
			}
			
			if($Build_Original_Items[$Item . $r]['Size'] > 0) {
				$Calculated_Tooltip['Capacity'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Size'] * $Build_Items[$Item . $r]['Amount'],0, "," , " ");
			}
		}
	} else {
		$Total_Size += $Build_Items[$Item]['Size'] * $Build_Items[$Item]['Amount'];
		$Extra_Space += floor($Build_Original_Items[$Item]['Size'] - $Build_Original_Items[$Item]['Size'] * $Mods[122]['Value']) * $Build_Items[$Item]['Amount'];

		if($Build_Items[$Item]['Size'] > 0) {
			$Calculated_Tooltip['Capacity'][] = $Build_Items[$Item]['Name'] . " = " . number_format($Build_Items[$Item]['Size'] * $Build_Items[$Item]['Amount'],0, "," , " ");
		}
	}
}
for($r=1; $r <= count($Ship_Hull_Expander_Slots);$r++) {
	$Extra_Hull_Expander += $Build_Items["Hull_Expander".$r]['Extra_Space'];
}
$Calculated_Hull_Space = floor($Build_Items["Ship"]['Hull_Space'] + $Extra_Hull_Expander) * $Mods[14]['Value'] + floor($Extra_Space) + floor($Extra_Space_Weapon);
$Calculated_Used_Space = $Total_Size;
$Calculated_Tooltip['Capacity'][0] = "Hull Space: " . $Calculated_Hull_Space . "<br/>";
$Calculated_Tooltip['Capacity'][1] = "Hull Space Calculation: " . "(" . $Build_Items["Ship"]['Hull_Space'] . " + " . $Extra_Hull_Expander . ") * " . $Mods[14]['Value'] . " + " . floor($Extra_Space) . " + " . floor($Extra_Space_Weapon) . "<br/>";
$Calculated_Tooltip['Capacity'][2] = "Initial hull space: " . $Build_Items["Ship"]['Hull_Space'];
$Calculated_Tooltip['Capacity'][3] = "Extra Hull Expander: " . ($Extra_Hull_Expander+0);
$Calculated_Tooltip['Capacity'][4] = "Capacity multiplier: " . $Mods[14]['Value'];
$Calculated_Tooltip['Capacity'][5] = "Item size reduction: +" . floor($Extra_Space);
$Calculated_Tooltip['Capacity'][6] = "Weapon size reduction: +" . floor($Extra_Space_Weapon);
$Calculated_Tooltip['Capacity'][7] = "<br/>";
$Calculated_Tooltip['Capacity'][8] = "Used Space:";
$Calculated_Tooltip['Capacity'][] = "Used Space: " . $Total_Size;
$Calculated_Tooltip['Capacity'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Weight
$Calculated_Tooltip['Weight'][0] = "";
$Calculated_Tooltip['Weight'][1] = "";
foreach ($Item_Names as $Item => $Items) {
	if ($Item == "Hull_Expander" or $Item == "Weapon" or $Item == "Solar_Panel" or $Item == "Diffuser" or $Item == "Augmenter" or $Item == "Overloader" or $Item == "Capacitor") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			$Total_Weight += $Build_Items[$Item . $r]['Weight'] * $Build_Items[$Item . $r]['Amount'];

			if($Build_Items[$Item . $r]['Weight'] > 0) {
				$Calculated_Tooltip['Weight'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Weight'] * $Build_Items[$Item . $r]['Amount'],0, "," , " ");
			}
		}
	} else {
		$Total_Weight += $Build_Items[$Item]['Weight'];

		if($Build_Items[$Item]['Weight'] > 0) {
			$Calculated_Tooltip['Weight'][] = $Build_Items[$Item]['Name'] . " = " . number_format($Build_Items[$Item]['Weight'] * $Build_Items[$Item]['Amount'],0, "," , " ");
		}
	}
}
$Calculated_Weight = $Total_Weight * $Mods[38]['Value'];
$Calculated_Tooltip['Weight'][0] = "Mass (Weight): " . $Calculated_Weight . "<br/>";
$Calculated_Tooltip['Weight'][1] = "Mass (Weight) calculation: " . $Total_Weight . " * " . $Mods[38]['Value'] . "<br/>";
$Calculated_Tooltip['Weight'][] = "Weight multiplier: " . $Mods[38]['Value'];
$Calculated_Tooltip['Weight'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Shield Bank
for($r=1; $r <= count($Ship_Capacitor_Slots);$r++) {
	$Extra_Capacitor += $Build_Items["Capacitor" . $r]['Shield_Boost'];
}
$Calculated_Shield_Bank = $Build_Items["Shield"]['Bank'] * $Mods[25]['Value'] + $Extra_Capacitor + $Mods[175]['Value'];
$Calculated_Tooltip['Shield'][] = "Shield bank: " . $Calculated_Shield_Bank . "<br/>";
$Calculated_Tooltip['Shield'][] = "Shield bank calculation: " . $Build_Items["Shield"]['Bank'] . " * " . $Mods[25]['Value'] . " + " . $Extra_Capacitor . " + " . $Mods[175]['Value'] . "<br/>";
$Calculated_Tooltip['Shield'][] = "Capacitor boost: " . $Extra_Capacitor;
$Calculated_Tooltip['Shield'][] =  "[Bonus] " . $Mods[175]['Name'] . ": " . $Mods[175]['Value'];
$Calculated_Tooltip['Shield'][] = "Shield multiplier: " . $Mods[25]['Value'];
$Calculated_Tooltip['Shield'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

if ($Extra_Capacitor <> 0) {
	$Mods[175]['Value'] += $Extra_Capacitor;
	$Mods[175]['Calculation_' . ${"175r"}] = "Capacitor boost: +" . $Extra_Capacitor;
}

// Recovery
$Calculated_Recovery = floor((($Build_Items["Shield"]['Regeneration'] + $Build_Items["Shield_Charger"]['Regeneration']) * $Mods[26]['Value']) + $Mods[224]['Value']);
$Calculated_Tooltip['Recovery'][] = "Recovery: " . $Calculated_Recovery . "<br/>";
$Calculated_Tooltip['Recovery'][] = "Recovery calculation: ((" . floor($Build_Items["Shield"]['Regeneration']) . ($Build_Items["Shield_Charger"]['Regeneration']>0 ? " + " . $Build_Items["Shield_Charger"]['Regeneration'] : "") . ") * " . round($Mods[26]['Value'],2) . ") + " . $Mods[224]['Value'] . "<br/>";
$Calculated_Tooltip['Recovery'][] = $Build_Items["Shield"]['Name'] . ": " . floor($Build_Items["Shield"]['Regeneration']);
$Calculated_Tooltip['Recovery'][] = "[Shield Charger] " . $Build_Items["Shield_Charger"]['Name'] . ": " . $Build_Items["Shield_Charger"]['Regeneration'];
$Calculated_Tooltip['Recovery'][] = "Recovery multiplier: " . round($Mods[26]['Value'],2);
$Calculated_Tooltip['Recovery'][] = "Bonus Recovery: " . $Mods[224]['Value'];
$Calculated_Tooltip['Recovery'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Energy Bank
$Extra_Capacitor = 0;
for($r=1; $r <= count($Ship_Capacitor_Slots);$r++) {
	$Extra_Capacitor += $Build_Items["Capacitor" . $r]['Energy_Boost'];
}
$Calculated_Energy_Bank = $Build_Items["Energy"]['Energy_Bank'] * $Mods[9]['Value'] + $Extra_Capacitor + $Mods[176]['Value'];
$Calculated_Tooltip['Energy'][0] = "Energy bank: " . $Calculated_Energy_Bank . "<br/>";
$Calculated_Tooltip['Energy'][1] = "Energy bank calculation: (" . $Build_Items["Energy"]['Energy_Bank'] . " * " . $Mods[9]['Value'] . ") + " . $Extra_Capacitor . " + " . $Mods[176]['Value'] . "<br/>";
$Calculated_Tooltip['Energy'][] = $Build_Items["Energy"]['Name'] . ": " . $Build_Items["Energy"]['Energy_Bank'];
$Calculated_Tooltip['Energy'][] = "Capacitor boost: " . $Extra_Capacitor;
$Calculated_Tooltip['Energy'][] = "Bonus: " . $Mods[176]['Value'];
$Calculated_Tooltip['Energy'][] = "Energy multiplier: " . $Mods[9]['Value'];
$Calculated_Tooltip['Energy'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

if ($Extra_Capacitor <> 0) {
	$Mods[176]['Value'] += $Extra_Capacitor;
	$Mods[176]['Calculation_' . ${"176r"}] = "Capacitor boost: +" . $Extra_Capacitor;
}

// Electricity
$Calculated_Tooltip['Electricity'][0] = "";
$Calculated_Tooltip['Electricity'][1] = "";

$Mods[910] = $Mods[10];
${"910r"} = ${"10r"};
$Mods[910]['Name'] = "Electricity_Sun";

foreach ($Item_Names as $Item => $Items) {
	if ($Item == "Hull_Expander" or $Item == "Diffuser" or $Item == "Capacitor" or $Item == "Overloader") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			$Total_Electricity_Usage += $Build_Items[$Item . $r]['Electricity'];

			if($Build_Items[$Item . $r]['Electricity'] > 0) {
				$Calculated_Tooltip['Electricity'][] = $Build_Items[$Item . $r]['Name'] . " = -" . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");
			}
		}
	} else if ($Item == "Shield_Charger" or $Item == "Cloak" or $Item == "Scoop" or $Item == "Tractor" or $Item == "Exterminator") {
	} else if ($Item == "Weapon" or $Item == "Solar_Panel") {
	} else if ($Item == "Energy") {
	} else {
		$Total_Electricity_Usage += $Build_Items[$Item]['Electricity'];

		if($Build_Items[$Item]['Electricity'] > 0) {
			$Calculated_Tooltip['Electricity'][] = $Build_Items[$Item]['Name'] . " = -" . number_format($Build_Items[$Item]['Electricity'],0, "," , " ");
		}
	}
}
$Calculated_Electricity = (($Build_Items["Energy"]['Electricity'] + $Build_Items["Ship"]['Inbuilt_Electricity']) * round($Mods[10]['Value'],3)) - $Total_Electricity_Usage;
$Calculated_Tooltip['Electricity'][0] = "Electricity: " . $Calculated_Electricity . "<br/>";
$Calculated_Tooltip['Electricity'][1] = "Electricity calculation: (" . $Build_Items["Energy"]['Electricity'] . " + " . $Build_Items["Ship"]['Inbuilt_Electricity'] .  ") * " . round($Mods[10]['Value'],3) . " - (" . $Total_Electricity_Usage . ")" . "<br/>";
$Calculated_Tooltip['Electricity'][] = "Electricity multiplier: " . round($Mods[10]['Value'],2);
$Calculated_Tooltip['Electricity'][] = "Ship Inbuilt Electricity: " . $Build_Items["Ship"]['Inbuilt_Electricity'];
$Calculated_Tooltip['Electricity'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Electricity when close to a sun
$Calculated_Tooltip['Electricity_w_sun'][0] = "";
$Calculated_Tooltip['Electricity_w_sun'][1] = "";
$Total_Electricity_Usage = 0;
foreach ($Item_Names as $Item => $Items) {
	if ($Item == "Hull_Expander" or $Item == "Diffuser" or $Item == "Capacitor" or $Item == "Overloader") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			$Total_Electricity_Usage += $Build_Items[$Item . $r]['Electricity'];

			if($Build_Items[$Item . $r]['Electricity'] > 0) {
				$Calculated_Tooltip['Electricity_w_sun'][] = $Build_Items[$Item . $r]['Name'] . " = -" . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");

			}
		}
	} else if ($Item == "Shield_Charger" or $Item == "Cloak" or $Item == "Scoop" or $Item == "Tractor" or $Item == "Exterminator") {
		if($Build_Items[$Item]['Electricity'] > 0) {
			$Calculated_Tooltip['Electricity_w_sun'][] = "[Not included] " . $Build_Items[$Item]['Name'] . " = -" . number_format($Build_Items[$Item]['Electricity'],0, "," , " ");
		}
	} else if ($Item == "Weapon") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			if($Build_Items[$Item . $r]['Electricity'] > 0) {
				$Calculated_Tooltip['Electricity_w_sun'][] = "[Not included] " . $Build_Items[$Item . $r]['Name'] . " = " . ($Item == "Weapon" ? "-" : "") . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");
			}
		}
	} else if ($Item == "Solar_Panel") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			$Total_Electricity_Usage -= $Build_Items[$Item . $r]['Electricity'];

			if($Build_Items[$Item . $r]['Electricity'] > 0) {
				$Calculated_Tooltip['Electricity_w_sun'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");

			}
		}
	} else if ($Item == "Energy") {
	} else {
		$Total_Electricity_Usage += $Build_Items[$Item]['Electricity'];

		if($Build_Items[$Item]['Electricity'] > 0) {
			$Calculated_Tooltip['Electricity_w_sun'][] = $Build_Items[$Item]['Name'] . " = -" . number_format($Build_Items[$Item]['Electricity'],0, "," , " ");
		}
	}
}
$Calculated_Electricity_Sun = (($Build_Items["Energy"]['Electricity'] + $Build_Items["Ship"]['Inbuilt_Electricity']) * round($Mods[910]['Value'],2)) - $Total_Electricity_Usage;
$Calculated_Tooltip['Electricity_w_sun'][0] = "Electricity: " . floor($Calculated_Electricity) . "<br/>";
$Calculated_Tooltip['Electricity_w_sun'][1] = "Electricity calculation: (" . $Build_Items["Energy"]['Electricity'] . " + " . $Build_Items["Ship"]['Inbuilt_Electricity'] .  ") * " . round($Mods[910]['Value'],2) . " - (" . $Total_Electricity_Usage . ")" . "<br/>";
$Calculated_Tooltip['Electricity_w_sun'][] = "Electricity multiplier: " . round($Mods[910]['Value'],2);
$Calculated_Tooltip['Electricity_w_sun'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Turning
if ($Build_Items['Ship']['Ship_Type'] == "Capital Ship") {
	$Weight_Modifier = 1000;
} else {
	$Weight_Modifier = 10000;
}
$Calculated_Turning = min(($Calculated_Weight<>0 ? (152 * ($Build_Items["Engine"]['Turn']+$Mods[179]['Value']) / $Mods[35]['Value']) / ($Calculated_Weight/$Weight_Modifier) : 0),515);
$Calculated_Tooltip['Turning'][] = "Turning: " . $Calculated_Turning . "<br/>"; 
$Calculated_Tooltip['Turning'][] = "Turning calculation: " . " max(((152 * " . $Build_Items["Engine"]['Turn'] . " / " . $Mods[35]['Value'] . " + " . $Mods[179]['Value'] . ") / (" . $Calculated_Weight . "/ " . $Weight_Modifier . ")),515)" . "<br/>";
$Calculated_Tooltip['Turning'][] = "Turning constant: 152";
$Calculated_Tooltip['Turning'][] = $Build_Items["Engine"]['Name'] . ": " . $Build_Items["Engine"]['Turn'];
$Calculated_Tooltip['Turning'][] = "[Bonus] " . $Mods[179]['Name'] . ": " . $Mods[179]['Value'];
$Calculated_Tooltip['Turning'][] = "Weight: " . $Calculated_Weight;
$Calculated_Tooltip['Turning'][] = "Turning multiplier: " . $Mods[35]['Value'];
$Calculated_Tooltip['Turning'][] = "In-game max limit: 515";
$Calculated_Tooltip['Turning'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Acceleration
$Calculated_Acceleration = ($Calculated_Weight <>0 ? ($Build_Items["Engine"]['Thrust'] * $Mods[29]['Value']+$Mods[215]['Value']) / ($Calculated_Weight/10000) : 0);
$Calculated_Tooltip['Acceleration'][] = "Acceleration: " . $Calculated_Acceleration . "<br/>";
$Calculated_Tooltip['Acceleration'][] = "Acceleration calculation: " . " ((" . $Build_Items["Engine"]['Thrust'] . " * " . $Mods[29]['Value'] . " + " . $Mods[215]['Value'] . ") / (" . $Calculated_Weight . " / 10000))" . "<br/>";
$Calculated_Tooltip['Acceleration'][] = $Build_Items["Engine"]['Name'] . ": " . $Build_Items["Engine"]['Thrust'];
$Calculated_Tooltip['Acceleration'][] = "[Bonus] " . $Mods[215]['Name'] . ": " . $Mods[215]['Value'];
$Calculated_Tooltip['Acceleration'][] = "Weight: " . $Calculated_Weight;
$Calculated_Tooltip['Acceleration'][] = "Thrust multiplier: " . $Mods[29]['Value'];
$Calculated_Tooltip['Acceleration'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Visibility
$Calculated_Tooltip['Visibility'][0] = "";
$Calculated_Tooltip['Visibility'][1] = "";
foreach ($Item_Names as $Item => $Items) {
	if ($Item == "Hull_Expander" or $Item == "Solar_Panel" or $Item == "Diffuser" or $Item == "Overloader" or $Item == "Capacitor") {
		for ($r=1; $r <= ${"Ship_" . $Item . "_Slots"};$r++) {
			$Total_Visibility += $Build_Items[$Item . $r]['Visibility'];

			if($Build_Items[$Item . $r]['Visibility'] > 0) {
				$Calculated_Tooltip['Visibility'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Visibility'],0, "," , " ");
			}
		}
	} elseif ($Item == "Engine" or $Item == "Weapon") {
		if($Build_Items[$Item]['Visibility']>0) {
			$Calculated_Tooltip['Visibility'][] = "[Not included] " . $Build_Items[$Item]['Name'] . " = " . number_format($Build_Items[$Item]['Visibility'],0, "," , " ");
		}
	} else {
		$Total_Visibility += $Build_Items[$Item]['Visibility'];

		if($Build_Items[$Item]['Visibility']>0) {
			$Calculated_Tooltip['Visibility'][] = $Build_Items[$Item]['Name'] . " = " . number_format($Build_Items[$Item]['Visibility'],0, "," , " ");
		}
	}
}

$Calculated_Visibility = $Total_Visibility * $Mods[36]['Value'] * ($Build_Items["Cloak"]['Visibility_Cloaking']<>0 ? $Build_Items["Cloak"]['Visibility_Cloaking'] : 1);
$Calculated_Tooltip['Visibility'][0] = "Base visibility: " . $Calculated_Visibility . "<br/>";
$Calculated_Tooltip['Visibility'][1] = "Base visibility calculation: " . $Total_Visibility . " * " . $Mods[36]['Value'] . " * " . ($Build_Items["Cloak"]['Visibility_Cloaking']<>0 ? $Build_Items["Cloak"]['Visibility_Cloaking'] : 1) . "<br/>";
$Calculated_Tooltip['Visibility'][] = "Visibility cloaking: " . ($Build_Items["Cloak"]['Visibility_Cloaking']<>0 ? $Build_Items["Cloak"]['Visibility_Cloaking'] : 1);
$Calculated_Tooltip['Visibility'][] = "Visibility multiplier: " . $Mods[36]['Value'];
$Calculated_Tooltip['Visibility'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Vision
$Calculated_Vision = $Build_Items["Radar"]['Vision']*$Mods[20]['Value'];
$Calculated_Tooltip['Vision'][] = "Vision: " . $Calculated_Vision;
$Calculated_Tooltip['Vision'][] = "Vision calculation: " . $Build_Items["Radar"]['Vision'] . " * " . $Mods[20]['Value'] . "<br/>";
$Calculated_Tooltip['Vision'][] = $Build_Items["Radar"]['Name'] . ": " . $Build_Items["Radar"]['Vision'] . "<br/>";
$Calculated_Tooltip['Vision'][] = "Radar multiplier: " . $Mods[20]['Value'];
$Calculated_Tooltip['Vision'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Detection
$Calculated_Detection = $Build_Items["Radar"]['Detection'] * $Mods[20]['Value'];
$Calculated_Tooltip['Detection'][] = "Detection: " . $Calculated_Detection;
$Calculated_Tooltip['Detection'][] = "Detection calculation: " . $Build_Items["Radar"]['Detection'] . " * " . $Mods[20]['Value'] . "<br/>";
$Calculated_Tooltip['Detection'][] = $Build_Items["Radar"]['Name'] . ": " . $Build_Items["Radar"]['Detection'] . "<br/>";
$Calculated_Tooltip['Detection'][] = "Radar multiplier: " . $Mods[20]['Value'];
$Calculated_Tooltip['Detection'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//
//	Weapon Calculation
$m=0;
$deleteditems=0;
unset($Build_Weapons);
for ($r=1;$r<=$Ship_Weapon_Slots;$r++) {
	$Build_Items['Weapon' . $r]['Multifire'] = 1; 
	$Build_Weapons['Weapon' . $r] = $Build_Items['Weapon' . $r];
}
for ($r=1;$r<=$Ship_Weapon_Slots;$r++) {
	if (isset($Build_Items['Weapon' . $r])) {
		
		for ($l=$r+1+$deleteditems;$l<=$Ship_Weapon_Slots;$l++) {
			if (isset($Build_Weapons['Weapon' . $l])) {
				$Duplicate = TRUE;
				
				if ($Build_Items['Weapon' . $r]["Name"] != $Build_Weapons['Weapon' . $l]["Name"]) {
					$Duplicate = FALSE;
				}
				// 17 = Multifire
				if ($Duplicate == TRUE and $Mods[17]['Value'] >= ($Build_Items['Weapon' . $r]['Multifire']+1)) {
					//echo "Unsetting r: " . $r . " - l: " . $l . " -- " . $Build_Items['Weapon' . $r]['Weapon_ID'] . " --> " . $Build_Weapons['Weapon' . $l]['Weapon_ID'] . "<br/>";
					unset($Build_Weapons['Weapon' . $l]);
					if ($l == $r+1+$deleteditems) {
						//$deleteditems++;
					}
					//$N_Multifire++;
					$Build_Items['Weapon' . $r]['Multifire']++;// = $N_Multifire;
				}
				
				$m++;
				if ($m == 2000) {
					break 2;
				}
			}
		}
	}
}

$N_Weapons = 0;
for ($r=1;$r<=$Ship_Weapon_Slots;$r++) {
	if (!isset($Build_Weapons['Weapon' . $r])) {
		unset($Build_Items['Weapon' . $r]);
	}
	if ($Build_Items['Weapon' . $r]['Item_ID'] != "") {
		$N_Weapons+= $Build_Items["Weapon" . $r]['Multifire'];
	}
}

for ($r=1;$r<=$Ship_Weapon_Slots;$r++) { 
	if ($Build_Items["Weapon" . $r]['Name'] != "") {
		$Calculated_Name[$r] = $Build_Items["Weapon" . $r]['Name'];
		$Calculated_Type[$r] = $Build_Items["Weapon" . $r]['Type'];
		
		if ($Build_Items["Weapon" . $r]['Multifire'] > 1) {
			$Calculated_Name[$r] = "[Multifire x" . $Build_Items["Weapon" . $r]['Multifire'] . "] " . $Build_Items["Weapon" . $r]['Name'];
		}
		//
		// Weapon Damage
		//	Finding the average dmg
		$Weapon_Damage_Calculation = ($Build_Items["Weapon" . $r]['Damage_Max'] + $Build_Items["Weapon" . $r]['Damage_Min']) / 2; 
		$Weapon_Damage_Calculation *= $Build_Items["Weapon" . $r]['Projectiles'];
		$Weapon_Damage_Calculation *= $Mods[4]['Value'];
		$Weapon_Damage_Calculation *= ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value'] : 1);
		$Weapon_Damage_Calculation *= ($Build_Items["Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1);
		$Weapon_Damage_Calculation *= $Build_Items["Weapon" . $r]['Multifire'];
		$Weapon_Damage_Calculation *= ($Build_Items["Weapon" . $r]['Type'] == "Transference" ? $Mods[188]['Value'] : 1);
		//	Adding effect from control skills
		$Weapon_Damage_Calculation += (($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type'] . " Damage")]['Value'] : 0) * $Build_Items["Weapon" . $r]['Projectiles']); 
		$Calculated_Damage[$r] = $Weapon_Damage_Calculation;
		
			//(((($Build_Items["Weapon" . $r]['Damage_Max'] + $Build_Items["Weapon" . $r]['Damage_Min'])/2)+($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type'] . " Damage")]['Value'] : 0))*$Build_Items["Weapon" . $r]['Projectiles']) * $Mods[4]['Value'] * ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value'] : 1) * ($Build_Items["Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1) * $Build_Items["Weapon" . $r]['Multifire'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage: " . round($Calculated_Damage[$r],2) . "";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Critical Damage: " . floor($Calculated_Damage[$r] * (1.5 * $Mods[3]['Value'])) . " (" . floor($Calculated_Damage[$r]) . " * " . (1.5 * $Mods[3]['Value']) . ")";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage pr. projectile: " . round($Calculated_Damage[$r]/$Build_Items["Weapon" . $r]['Projectiles'],0) . "";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage pr. weapon: " . floor($Calculated_Damage[$r]/$Build_Items["Weapon" . $r]['Multifire']) . "<br/>";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage calculation: ((" . $Build_Items["Weapon" . $r]['Damage_Max'] . " + " . $Build_Items["Weapon" . $r]['Damage_Min'] . ") / 2)" . " * " . $Build_Items["Weapon" . $r]['Projectiles'] . ") * " . round($Mods[4]['Value'],2) . ($Mods[array_search_multi($Mods, "Name","Damage ".$Build_Items["Weapon" . $r]['Type'])]['Value']<>0 ? " * " . $Mods[array_search_multi($Mods, "Name","Damage ".$Build_Items["Weapon" . $r]['Type'])]['Value'] : "") . " * " . ($Build_Items["Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1) . ($Build_Items["Weapon" . $r]['Type'] == "Transference" ? " * " . $Mods[188]['Value'] : "") . " * " . $Build_Items["Weapon" . $r]['Multifire'] . " + (" . ($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type'] . " Damage")]['Value'] : 0) . " * " . $Build_Items["Weapon" . $r]['Projectiles'] . ")" . "<br/>";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Build_Items["Weapon" . $r]['Name'] . " (Max): " . $Build_Items["Weapon" . $r]['Damage_Max'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Build_Items["Weapon" . $r]['Name'] . " (Min): " . $Build_Items["Weapon" . $r]['Damage_Min'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Bonus projectil damage: " . ($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Weapon" . $r]['Type'] . " Damage")]['Value'] : 0);
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Number of projectiles: " . $Build_Items["Weapon" . $r]['Projectiles'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Mods[4]['Name'] . " multiplier: " . round($Mods[4]['Value'],2);
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Build_Items["Weapon" . $r]['Type'] . " damage multiplier: " . ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value'] : 1);
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Ethereal damage multiplier: " . ($Build_Items["Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1);
		if ($Build_Items["Weapon" . $r]['Type'] == "Transference") {
			$Calculated_Tooltip['Weapon_Damage'.$r][] = "Transference strength: " . ($Build_Items["Weapon" . $r]['Type'] == "Transference" ? $Mods[188]['Value'] : 1);
		}
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Multifire: " . $Build_Items["Weapon" . $r]['Multifire'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

		//
		// Weapon Self Damage
		$Weapon_Damage_Calculation = $Build_Items["Weapon" . $r]['Damage_Self']; 
		$Weapon_Damage_Calculation *= $Mods[4]['Value'];
		$Weapon_Damage_Calculation *= ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Weapon" . $r]['Type'])]['Value'] : 1);
		$Weapon_Damage_Calculation *= ($Build_Items["Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1);
		$Weapon_Damage_Calculation *= $Build_Items["Weapon" . $r]['Multifire'];
		$Weapon_Damage_Calculation *= ($Build_Items["Weapon" . $r]['Type'] == "Transference" ? $Mods[188]['Value'] : 1);
		$Weapon_Damage_Calculation *= ($Build_Items["Weapon" . $r]['Type'] == "Transference" ? (1/$Mods[187]['Value']) : 1);
		$Calculated_Self_Damage[$r] = $Weapon_Damage_Calculation;
		
		if ($Build_Items["Weapon" . $r]['Type'] == "Transference") {
			//$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Transference efficiency: " . round((1/$Mods[187]['Value']),2). " (1 / " . $Mods[187]['Value'] . ")";
		}
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "<br/>Self damage: " . round($Weapon_Damage_Calculation,2) . " (" . $Build_Items["Weapon" . $r]['Damage_Self'] . " * Damage increase * " . round((1/$Mods[187]['Value']),2) . ")";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Transference Efficiency: " . round((1/$Mods[187]['Value']),2);
		
		//
		//	Weapon Recoil
		$Calculated_Weapon_Recoil[$r] = max($Build_Items["Weapon" . $r]['Recoil'] / $Mods[23]['Value'],0.1);
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "Recoil: " . round($Calculated_Weapon_Recoil[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "Recoil calculation: max(" . $Build_Items["Weapon" . $r]['Recoil'] . " / " . round($Mods[23]['Value'],2) . ", 0.1)<br/>";
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = $Build_Items["Weapon" . $r]['Name'] . ": " . $Build_Items["Weapon" . $r]['Recoil'];
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = $Mods[23]['Name'] . " multiplier: " . round($Mods[23]['Value'],2);
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "Lower limit on recoil: 0.1";
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

		//
		//	Weapon Electricity
		$Calculated_Weapon_Electricity[$r] = ($Build_Items["Weapon" . $r]['Electricity'] * $Mods[8]['Value'] * $Build_Items["Weapon" . $r]['Multifire']);
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Weapon Electricity: " . number_format($Calculated_Weapon_Electricity[$r],2,","," ") . "<br/>";
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Electricity calculation: " . $Build_Items["Weapon" . $r]['Electricity'] . " * " . number_format($Mods[8]['Value'],2,","," ") . " * " . $Build_Items["Weapon" . $r]['Multifire'] . "<br/>";
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = $Build_Items["Weapon" . $r]['Name'] . ": " . $Build_Items["Weapon" . $r]['Electricity'];
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = $Mods[8]['Name'] . " multiplier: " . number_format($Mods[8]['Value'],2,","," ");
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Multifire: " . $Build_Items["Weapon" . $r]['Multifire'];
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";
		
		//
		//	Weapon EPS
		$Calculated_Weapon_EPS[$r] = round($Calculated_Weapon_Electricity[$r] / $Calculated_Weapon_Recoil[$r],0);
		$Calculated_Tooltip['Weapon_EPS'.$r] = $Calculated_Tooltip['Weapon_Electricity'.$r];
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "<br/>EPS: " . round($Calculated_Weapon_Electricity[$r] / $Calculated_Weapon_Recoil[$r],0) . "<br/>";
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "Weapon EPS: " . number_format($Calculated_Weapon_Electricity[$r],2,","," ") . " / " . number_format($Calculated_Weapon_Recoil[$r],2,","," ");
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "Weapon Electricity: " . number_format($Calculated_Weapon_Electricity[$r],2,","," ");
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "Weapon Recoil: " . number_format($Calculated_Weapon_Recoil[$r],2,","," ");
		
		
		//
		//	Weapon DPS
		$Calculated_DPS[$r] = (floor($Calculated_Damage[$r])) / (floor($Calculated_Weapon_Recoil[$r]*1000)/1000);
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "DPS: " . round($Calculated_DPS[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "DPS calculation: " . floor($Calculated_Damage[$r]) . " / " . (floor($Calculated_Weapon_Recoil[$r]*1000)/1000) . "<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "Weapon damage: " . floor($Calculated_Damage[$r]);
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "Weapon recoil: " . (floor($Calculated_Weapon_Recoil[$r]*1000)/1000);

		//
		//	Weapon Critical DPS
		$Critical_Chance = min($Mods[1]['Value']-1, 0.99);
		$Calculated_Crit_DPS[$r] =  $Calculated_DPS[$r] * (1 + 0.5 * $Mods[3]['Value']) * (0.01 + $Critical_Chance);
		$Calculated_Crit_DPS[$r] += $Calculated_DPS[$r] * (1-(0.01 + $Critical_Chance));
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "<br/><br/>" . "DPS (w/Crit): " . floor($Calculated_Crit_DPS[$r]) . "<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "DPS (w/Crit) calculation: " . round($Calculated_DPS[$r],2) . " * (1 + 0.5 * " . round($Mods[3]['Value'],2) . ") * ((0.01 + " . round($Critical_Chance,2) . ")" . " + DPS * " . (1-(0.01 + $Critical_Chance)) . ")<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = $Mods[3]['Name'] . ": " . (1 + 0.5 * $Mods[3]['Value']) . " (1 + 0.5 * " . round($Mods[3]['Value'],2) . ")";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = $Mods[1]['Name'] . ": min(" . round($Critical_Chance+0.01,3) . ", 1)";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "Normal hit chance: " . round((1-(0.01 + $Critical_Chance)),3);

		//
		//	Weapon DPE
		$Calculated_DPE[$r] = round(floor($Calculated_Damage[$r]) / ($Build_Items["Weapon" . $r]['Electricity'] * $Build_Items["Weapon" . $r]['Multifire']),2);
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "DPE: " . round($Calculated_DPE[$r],2) . "";
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "DPE w/Electric tempering: " . round($Calculated_Damage[$r]/$Calculated_Weapon_Electricity[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "DPE calculation: " . round($Calculated_Damage[$r],2) . " / " . $Build_Items["Weapon" . $r]['Electricity'] . "<br/>";
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "Weapon damage: " . round($Calculated_Damage[$r],2);
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "Weapon electricity: " . ($Build_Items["Weapon" . $r]['Electricity'] * $Build_Items["Weapon" . $r]['Multifire']);

		//
		//	Weapon Range
		$Calculated_Range[$r] = min(($Build_Items["Weapon" . $r]['W_Range']*$Mods[22]['Value'])*2, ($Build_Items["Weapon" . $r]['W_Range']*$Mods[22]['Value'])+$Mods[180]['Value']);
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Range: " . round($Calculated_Range[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Range calculation: " . "min(" . round((($Build_Items["Weapon" . $r]['W_Range']*$Mods[22]['Value'])*2),2) . ", " . round((($Build_Items["Weapon" . $r]['W_Range']*$Mods[22]['Value'])+$Mods[180]['Value']),2) . ")";
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Range calculation: " . "min(" . $Build_Items["Weapon" . $r]['W_Range'] . " * " . round($Mods[22]['Value'],2) . " * 2, " . $Build_Items["Weapon" . $r]['W_Range'] . " * " . round($Mods[22]['Value'],2) . " + " . $Mods[180]['Value'] . ")" . "<br/>";
		$Calculated_Tooltip['Weapon_Range'.$r][] = $Build_Items["Weapon" . $r]['Name'] . ": " . $Build_Items["Weapon" . $r]['W_Range'];
		$Calculated_Tooltip['Weapon_Range'.$r][] = $Mods[22]['Name'] . ": " . round($Mods[22]['Value'],2);
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Upper limit of ZOFR: *2 ";
		$Calculated_Tooltip['Weapon_Range'.$r][] = $Mods[180]['Name'] . " (ZOFR): " . $Mods[180]['Value'];

		//
		//	Weapon Sustainable
		if (min(($Calculated_Electricity - ($Calculated_Weapon_Electricity[$r] / $Calculated_Weapon_Recoil[$r])),0) != 0)	{ 
			$Calculated_Sustainable[$r] = -$Calculated_Energy_Bank/($Calculated_Electricity-($Calculated_Weapon_Electricity[$r]/$Calculated_Weapon_Recoil[$r]));
			$Calculated_Sustainable_Tooltip = number_format(-$Calculated_Energy_Bank/($Calculated_Electricity-($Calculated_Weapon_Electricity[$r]/$Calculated_Weapon_Recoil[$r])),2,","," ");
		} else {
			$Calculated_Sustainable[$r] = "&infin;";
		}
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Sustainable: " . $Calculated_Sustainable_Tooltip . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Sustainable calculation: -" . number_format($Calculated_Energy_Bank,2,","," ") . " / (" . number_format($Calculated_Electricity,2,","," ") . " - (" . number_format($Calculated_Weapon_Electricity[$r],2,","," ") . " / " . number_format($Calculated_Weapon_Recoil[$r],2,","," ") . "))" . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Energy bank: " . number_format($Calculated_Energy_Bank,2,","," ");
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Electricity: " . number_format($Calculated_Electricity,2,","," ");
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Weapon electricity: " . number_format($Calculated_Weapon_Electricity[$r],2,","," ");
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Weapon recoil: " . number_format($Calculated_Weapon_Recoil[$r],2,","," ");

		//
		// Weapon Sustainable DPS
		$Calculated_Sustainable_DPS[$r] = min(abs($Calculated_DPS[$r]), abs($Calculated_Damage[$r] / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity)));
		
		if (min(abs($Calculated_DPS[$r]), abs($Calculated_Damage[$r] / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity))) == $Calculated_DPS[$r]) {
			$Calculated_Sustainable_DPS[$r] = $Calculated_DPS[$r];
		} else {
			$Calculated_Sustainable_DPS[$r] = $Calculated_Damage[$r] / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity);
		}
		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "DPS: " . number_format($Calculated_Sustainable_DPS[$r],2,","," ") . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "DPS calculation: min (" . floor(abs($Calculated_DPS[$r])) . ", " . floor(abs($Calculated_Damage[$r])) . " / (" . floor($Calculated_Weapon_Electricity[$r]) . " / " . floor($Calculated_Electricity) . "))<br/>";
		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Weapon DPS: " . $Calculated_DPS[$r];
		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Weapon damage: " . $Calculated_Damage[$r];
		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Weapon electricity: " . $Calculated_Weapon_Electricity[$r];
		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Electricity: " . $Calculated_Electricity;
		
		//
		// Weapon Sustainable Critical DPS
		if (min(abs($Calculated_Crit_DPS[$r]), abs(($Calculated_Crit_DPS[$r] * $Calculated_Weapon_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity))) == abs($Calculated_Crit_DPS[$r])) {
			$Calculated_Sustainable_Crit_DPS[$r] = $Calculated_Crit_DPS[$r];
		} else {
			$Calculated_Sustainable_Crit_DPS[$r] = ($Calculated_Crit_DPS[$r] * $Calculated_Weapon_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity);
		}
		
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "DPS: " . number_format($Calculated_Sustainable_DPS[$r],2,","," ") . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "DPS calculation: min (" . floor(abs($Calculated_Crit_DPS[$r])) . ", " . number_format(abs(($Calculated_Crit_DPS[$r] * $Calculated_Weapon_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity)),2,","," ") . ")<br/>";
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "Weapon Critical DPS: " . number_format($Calculated_Crit_DPS[$r],2,","," ");
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "Weapon Sustainable Critical DPS: " . number_format(($Calculated_Crit_DPS[$r] * $Calculated_Weapon_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity),2,","," ");

		//
		//	Weapon Sustainable DPS wSun
		$Calculated_Sustainable_DPS_wSun[$r] = min($Calculated_DPS[$r], $Calculated_Damage[$r] / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity_Sun));
	}
}

//
//
//	Tractor Calculation
if ($Build_Items["Tractor"]['Name'] != "") {
	$Build_Items["Tractor"]['Strength'] *= (1+ $Mods[31]['Value']);
	$Build_Items["Tractor"]['T_Range'] *= (1+ $Mods[56]['Value']);
	$Build_Items["Tractor"]['T_Range'] = min($Build_Items["Tractor"]['T_Range'] * 2, $Build_Items["Tractor"]['T_Range'] + $Mods[180]['Value']);
	$Build_Items["Tractor"]['Electricity'] *= (1+ $Mods[57]['Value']);
	$Build_Items["Tractor"]['SPE'] = $Build_Items["Tractor"]['Strength'] / $Build_Items["Tractor"]['Electricity'];

	if (min(($Calculated_Electricity-$Build_Items["Tractor"]['Electricity']),0) != 0)	{ 
		$Build_Items["Tractor"]['Sustainable'] = -$Calculated_Energy_Bank/($Calculated_Electricity-$Build_Items["Tractor"]['Electricity']);
	} else {
		$Build_Items["Tractor"]['Sustainable'] = "&infin;";
	}
}

//
//
//	Resistance Calculation
$Damage_Types = array("Physical", "Surgical", "Radiation", "Mining", "Transference", "Heat", "Laser", "Energy");
$Damage_Type_Mods = array(184, 186, 185, 183, 152, 181, 182, 189);
$r=1;
foreach ($Damage_Types as $Damage_Type) {
	for ($l=1;$l<=$Ship_Diffuser_Slots;$l++) {
		${"Diffuser_".$Damage_Type} = max(${"Diffuser_".$Damage_Type}, $Build_Items["Diffuser".$l]['Resistance_'.$Damage_Type]);
	}
	$Resistance[$r]['Image'] = "";
	$Resistance[$r]['Name'] = $Damage_Type;
	$Resistance[$r]['Value'] = round(1-((1-$Build_Items["Ship"]['Resistance_'.$Damage_Type])*(1-${"Diffuser_".$Damage_Type})/($Mods[24]['Value'])),4) * 100;
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = $Damage_Type . " resistance: " . $Resistance[$r]['Value'] . "</br>";
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = $Damage_Type . " resistance calculation: round(1 - ((1 - " . $Build_Items["Ship"]['Resistance_'.$Damage_Type] . ") * (1 - " . ${"Diffuser_".$Damage_Type} . ") / (" . $Mods[24]['Value'] . ")), 4) * 100";
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = "Initial resistance: " . $Build_Items["Ship"]['Resistance_'.$Damage_Type];
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = "Diffuser: " . ${"Diffuser_".$Damage_Type};
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = "Resistance multiplier: " . $Mods[24]['Value'];
	$r++;	
}

?>