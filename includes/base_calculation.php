<?php

//
//	Full item list
	unset($Item_Names);
	$Item_Names = array(
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
		"Base_Extractor" => "Base_Extractors"//,
		//"Base_Exterminator" => "Base_Exterminators"
	);

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
	if ($Item == "Base") {

	} elseif (isset(${"" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			
			$Total_Size += $Build_Items[$Item . $r]['Size'] * $Build_Items[$Item . $r]['Amount'];
			$Extra_Space += floor(($Build_Original_Items[$Item . $r]['Size'] - $Build_Original_Items[$Item . $r]['Size'] * $Mods[122]['Value']) * $Build_Items[$Item . $r]['Amount']);
			if ($Item == "Base_Weapon") {
				$Extra_Space_Weapon += floor($Build_Original_Items[$Item . $r]['Size'] - $Build_Original_Items[$Item . $r]['Size'] * $Mods[260]['Value']);
			}
			
			if($Build_Original_Items[$Item . $r]['Size'] > 0) {
				$Calculated_Tooltip['Capacity'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Size'],0, "," , " ");
			}
		}
	} else {
		$Total_Size += $Build_Items[$Item]['Size'] * $Build_Items[$Item]['Amount'];
		$Extra_Space += floor(($Build_Original_Items[$Item]['Size'] - $Build_Original_Items[$Item]['Size'] * $Mods[122]['Value']) * $Build_Items[$Item]['Amount']);

		if($Build_Items[$Item]['Size'] > 0) {
			$Calculated_Tooltip['Capacity'][] = $Build_Items[$Item]['Name'] . " = " . number_format($Build_Items[$Item]['Size'],0, "," , " ");
		}
	}
}

$Total_Size += $Builds[$Build_ID]['Extra_Workers'];
$Calculated_Tooltip['Capacity'][] = "Extra workers: " . $Builds[$Build_ID]['Extra_Workers'];

for($r=1; $r <= $Base_Hull_Expander_Slots;$r++) {
	$Extra_Hull_Expander += $Build_Items["Base_Hull_Expander".$r]['Extra_Space'] * $Build_Items["Base_Hull_Expander".$r]['Amount'];
}
$Calculated_Hull_Space = floor($Build_Items["Base"]['Hull'] + $Extra_Hull_Expander) * $Mods[14]['Value'] + floor($Extra_Space) + floor($Extra_Space_Weapon);
$Calculated_Tooltip['Capacity'][0] = "Hull Space: " . $Calculated_Hull_Space . "<br/>";
$Calculated_Tooltip['Capacity'][1] = "Hull Space Calculation: " . "(" . $Build_Items["Base"]['Hull'] . " + " . $Extra_Hull_Expander . ") * " . $Mods[14]['Value'] . " + " . floor($Extra_Space) . " + " . floor($Extra_Space_Weapon) . "<br/>";
$Calculated_Tooltip['Capacity'][2] = "Initial hull space: " . $Build_Items["Base"]['Hull'];
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
	if (isset(${"" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			$Total_Weight += $Build_Items[$Item . $r]['Weight'] * $Build_Items[$Item . $r]['Amount'];

			if($Build_Items[$Item . $r]['Weight'] > 0) {
				$Calculated_Tooltip['Weight'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Weight'],0, "," , " ");
			}
		}
	} else {
		$Total_Weight += $Build_Items[$Item]['Weight'] * $Build_Items[$Item]['Amount'];

		if($Build_Items[$Item]['Weight'] > 0) {
			$Calculated_Tooltip['Weight'][] = $Build_Items[$Item]['Name'] . " = " . number_format($Build_Items[$Item]['Weight'],0, "," , " ");
		}
	}
}
$Calculated_Weight = $Total_Weight * $Mods[38]['Value'];
$Calculated_Tooltip['Weight'][0] = "Mass (Weight): " . $Calculated_Weight . "<br/>";
$Calculated_Tooltip['Weight'][1] = "Mass (Weight) calculation: " . $Total_Weight . " * " . $Mods[38]['Value'] . "<br/>";
$Calculated_Tooltip['Weight'][] = "Weight multiplier: " . $Mods[38]['Value'];
$Calculated_Tooltip['Weight'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Shield Bank
for($r=1; $r <= count($Base_Capacitor_Slots);$r++) {
	$Extra_Capacitor += $Build_Items["Base_Capacitor" . $r]['Shield_Boost'];
}
$Calculated_Shield_Bank = $Build_Items["Base_Shield"]['Bank'] * $Mods[25]['Value'] + $Extra_Capacitor + $Mods[175]['Value'];
$Calculated_Tooltip['Shield'][] = "Shield bank: " . $Calculated_Shield_Bank . "<br/>";
$Calculated_Tooltip['Shield'][] = "Shield bank calculation: " . $Build_Items["Base_Shield"]['Bank'] . " * " . $Mods[25]['Value'] . " + " . $Extra_Capacitor . " + " . $Mods[175]['Value'] . "<br/>";
$Calculated_Tooltip['Shield'][] = "Capacitor boost: " . $Extra_Capacitor;
$Calculated_Tooltip['Shield'][] =  "[Bonus] " . $Mods[175]['Name'] . ": " . $Mods[175]['Value'];
$Calculated_Tooltip['Shield'][] = "Shield multiplier: " . $Mods[25]['Value'];
$Calculated_Tooltip['Shield'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

if ($Extra_Capacitor <> 0) {
	$Mods[175]['Value'] += $Extra_Capacitor;
	$Mods[175]['Calculation_' . ${"175r"}] = "Capacitor boost: +" . $Extra_Capacitor;
}

// Recovery
$Calculated_Recovery = floor((($Build_Items["Base_Shield"]['Regeneration'] + $Build_Items["Base_Shield_Charger"]['Regeneration']) * $Mods[26]['Value']) + $Mods[224]['Value']);
$Calculated_Tooltip['Recovery'][] = "Recovery: " . $Calculated_Recovery . "<br/>";
$Calculated_Tooltip['Recovery'][] = "Recovery calculation: ((" . floor($Build_Items["Base_Shield"]['Regeneration']) . ($Build_Items["Base_Shield_Charger"]['Regeneration']>0 ? " + " . $Build_Items["Base_Shield_Charger"]['Regeneration'] : "") . ") * " . round($Mods[26]['Value'],2) . ") + " . $Mods[224]['Value'] . "<br/>";
$Calculated_Tooltip['Recovery'][] = $Build_Items["Base_Shield"]['Name'] . ": " . floor($Build_Items["Base_Shield"]['Regeneration']);
$Calculated_Tooltip['Recovery'][] = "[Shield Charger] " . $Build_Items["Base_Shield_Charger"]['Name'] . ": " . $Build_Items["Base_Shield_Charger"]['Regeneration'];
$Calculated_Tooltip['Recovery'][] = "Recovery multiplier: " . round($Mods[26]['Value'],2);
$Calculated_Tooltip['Recovery'][] = "Bonus Recovery: " . $Mods[224]['Value'];
$Calculated_Tooltip['Recovery'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Energy Bank
$Extra_Capacitor = 0;
for($r=1; $r <= count($Base_Capacitor_Slots);$r++) {
	$Extra_Capacitor += $Build_Items["Base_Capacitor" . $r]['Energy_Boost'];
}
$Calculated_Energy_Bank = $Build_Items["Base_Energy"]['Energy_Bank'] * $Mods[9]['Value'] + $Extra_Capacitor + $Mods[176]['Value'];
$Calculated_Tooltip['Energy'][0] = "Energy bank: " . $Calculated_Energy_Bank . "<br/>";
$Calculated_Tooltip['Energy'][1] = "Energy bank calculation: (" . $Build_Items["Base_Energy"]['Energy_Bank'] . " * " . $Mods[9]['Value'] . ") + " . $Extra_Capacitor . " + " . $Mods[176]['Value'] . "<br/>";
$Calculated_Tooltip['Energy'][] = $Build_Items["Base_Energy"]['Name'] . ": " . $Build_Items["Base_Energy"]['Energy_Bank'];
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
	if ($Item == "Base_Shield_Charger" or $Item == "Base_Cloak" or $Item == "Base_Scoop" or $Item == "Base_Tractor" or $Item == "Base_Exterminator") {
	} else if ($Item == "Base_Weapon" or $Item == "Base_Solar_Panel") {
	} else if ($Item == "Base_Energy") {
	} else {
		if (isset(${"" . $Item . "_Slots"})) {
				for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
				$Total_Electricity_Usage += $Build_Items[$Item . $r]['Electricity'] * $Build_Items[$Item . $r]['Amount'];

				if($Build_Items[$Item . $r]['Electricity'] > 0) {
					$Calculated_Tooltip['Electricity'][] = $Build_Items[$Item . $r]['Name'] . " = -" . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");
				}
			}
		}
		$Total_Electricity_Usage += $Build_Items[$Item]['Electricity'] * $Build_Items[$Item]['Amount'];

		if($Build_Items[$Item]['Electricity'] > 0) {
			$Calculated_Tooltip['Electricity'][] = $Build_Items[$Item]['Name'] . " = -" . number_format($Build_Items[$Item]['Electricity'],0, "," , " ");
		}
	}
}
$Calculated_Electricity = (($Build_Items["Base_Energy"]['Electricity'] + $Build_Items["Base"]['Inbuilt_Electricity']) * round($Mods[10]['Value'],3)) - $Total_Electricity_Usage;
$Calculated_Tooltip['Electricity'][0] = "Electricity: " . $Calculated_Electricity . "<br/>";
$Calculated_Tooltip['Electricity'][1] = "Electricity calculation: (" . $Build_Items["Base_Energy"]['Electricity'] . " + " . $Build_Items["Base"]['Inbuilt_Electricity'] .  ") * " . round($Mods[10]['Value'],3) . " - (" . $Total_Electricity_Usage . ")" . "<br/>";
$Calculated_Tooltip['Electricity'][] = "Electricity multiplier: " . round($Mods[10]['Value'],2);
$Calculated_Tooltip['Electricity'][] = "Base Inbuilt Electricity: " . $Build_Items["Base"]['Inbuilt_Electricity'];
$Calculated_Tooltip['Electricity'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Electricity when close to a sun
$Calculated_Tooltip['Electricity_w_sun'][0] = "";
$Calculated_Tooltip['Electricity_w_sun'][1] = "";
$Total_Electricity_Usage = 0;
foreach ($Item_Names as $Item => $Items) {
 if ($Item == "Base_Shield_Charger" or $Item == "Base_Cloak" or $Item == "Base_Scoop" or $Item == "Base_Tractor" or $Item == "Base_Exterminator") {
		if($Build_Items[$Item]['Electricity'] > 0) {
			$Calculated_Tooltip['Electricity_w_sun'][] = "[Not included] " . $Build_Items[$Item]['Name'] . " = -" . number_format($Build_Items[$Item]['Electricity'],0, "," , " ");
		}
	} else if ($Item == "Base_Weapon") {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			if($Build_Items[$Item . $r]['Electricity'] > 0) {
				$Calculated_Tooltip['Electricity_w_sun'][] = "[Not included] " . $Build_Items[$Item . $r]['Name'] . " = " . ($Item == "Weapon" ? "-" : "") . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");
			}
		}
	} else if ($Item == "Base_Solar_Panel") {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			$Total_Electricity_Usage -= $Build_Items[$Item . $r]['Electricity'] * $Build_Items[$Item . $r]['Amount'];

			if($Build_Items[$Item . $r]['Electricity'] > 0) {
				$Calculated_Tooltip['Electricity_w_sun'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");

			}
		}
	} else if ($Item == "Base_Energy") {
	} else {
		if (isset(${"" . $Item . "_Slots"})) {
			for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
				$Total_Electricity_Usage += $Build_Items[$Item . $r]['Electricity'] * $Build_Items[$Item . $r]['Amount'];

				if($Build_Items[$Item . $r]['Electricity'] > 0) {
					$Calculated_Tooltip['Electricity_w_sun'][] = $Build_Items[$Item . $r]['Name'] . " = -" . number_format($Build_Items[$Item . $r]['Electricity'],0, "," , " ");
				}
			}
		}
		$Total_Electricity_Usage += $Build_Items[$Item]['Electricity'] * $Build_Items[$Item]['Amount'];

		if($Build_Items[$Item]['Electricity'] > 0) {
			$Calculated_Tooltip['Electricity_w_sun'][] = $Build_Items[$Item]['Name'] . " = -" . number_format($Build_Items[$Item]['Electricity'],0, "," , " ");
		}
	}
}
$Calculated_Electricity_Sun = (($Build_Items["Base_Energy"]['Electricity'] + $Build_Items["Base"]['Inbuilt_Electricity']) * round($Mods[910]['Value'],2)) - $Total_Electricity_Usage;
$Calculated_Tooltip['Electricity_w_sun'][0] = "Electricity: " . floor($Calculated_Electricity) . "<br/>";
$Calculated_Tooltip['Electricity_w_sun'][1] = "Electricity calculation: (" . $Build_Items["Base_Energy"]['Electricity'] . " + " . $Build_Items["Base"]['Inbuilt_Electricity'] .  ") * " . round($Mods[910]['Value'],2) . " - (" . $Total_Electricity_Usage . ")" . "<br/>";
$Calculated_Tooltip['Electricity_w_sun'][] = "Electricity multiplier: " . round($Mods[910]['Value'],2);
$Calculated_Tooltip['Electricity_w_sun'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

// Visibility
$Calculated_Tooltip['Visibility'][0] = "";
$Calculated_Tooltip['Visibility'][1] = "";
foreach ($Item_Names as $Item => $Items) {
	if (isset(${"" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			$Total_Visibility += $Build_Items[$Item . $r]['Visibility'];

			if($Build_Items[$Item . $r]['Visibility'] > 0) {
				$Calculated_Tooltip['Visibility'][] = $Build_Items[$Item . $r]['Name'] . " = " . number_format($Build_Items[$Item . $r]['Visibility'],0, "," , " ");
			}
		}
	} elseif ($Item == "Engine" or $Item == "Base_Weapon") {
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
$Calculated_Vision = $Build_Items["Base_Radar"]['Vision']*$Mods[20]['Value'];
$Calculated_Tooltip['Vision'][] = "Vision: " . $Calculated_Vision;
$Calculated_Tooltip['Vision'][] = "Vision calculation: " . $Build_Items["Base_Radar"]['Vision'] . " * " . $Mods[20]['Value'] . "<br/>";
$Calculated_Tooltip['Vision'][] = $Build_Items["Base_Radar"]['Name'] . ": " . $Build_Items["Base_Radar"]['Vision'] . "<br/>";
$Calculated_Tooltip['Vision'][] = "Radar multiplier: " . $Mods[20]['Value'];
$Calculated_Tooltip['Vision'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Detection
$Calculated_Detection = $Build_Items["Base_Radar"]['Detection'] * $Mods[20]['Value'];
$Calculated_Tooltip['Detection'][] = "Detection: " . $Calculated_Detection;
$Calculated_Tooltip['Detection'][] = "Detection calculation: " . $Build_Items["Base_Radar"]['Detection'] . " * " . $Mods[20]['Value'] . "<br/>";
$Calculated_Tooltip['Detection'][] = $Build_Items["Base_Radar"]['Name'] . ": " . $Build_Items["Base_Radar"]['Detection'] . "<br/>";
$Calculated_Tooltip['Detection'][] = "Radar multiplier: " . $Mods[20]['Value'];
$Calculated_Tooltip['Detection'][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

//	Workers Required
foreach ($Item_Names as $Item => $Items) {
	if (isset(${"" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			$Calculated_Worker_Req += 1 * $Build_Items[$Item . $r]['Amount'];
		}
	} else {
		$Calculated_Worker_Req += 1 * $Build_Items[$Item]['Amount'];
	}
}
$Calculated_Worker_Req += $Builds[$Build_ID]['Extra_Workers'];
$Calculated_Tooltip['Worker_Req'][] = "Worker required: " . $Calculated_Worker_Req;
$Calculated_Tooltip['Worker_Req'][] = "Worker req. calculation: # items" . "<br/><br/>";
for ($r=1;$r<=$Base_Extractor_Slots;$r++) { 
	if ($Build_Items["Base_Extractor" . $r]['Name'] != "") {
		$Calculated_Worker_Req += ($Build_Items["Base_Extractor" . $r]['Workers'] == 0 ? -1 : ($Build_Items["Base_Extractor" . $r]['Workers'] > 1 ? $Build_Items["Base_Extractor" . $r]['Workers'] -1 : 0)) * $Build_Items["Base_Extractor" . $r]['Amount'];
		$Calculated_Tooltip['Worker_Req'][] = $Build_Items["Base_Extractor" . $r]['Name'] . " req. workers: " . $Build_Items["Base_Extractor" . $r]['Workers'] * $Build_Items["Base_Extractor" . $r]['Amount'];
	}
}

//
//
//	Extracted resources Calculation
$Ration_pr_worker = 60 / 3600;
$Calculated_Extracted_Materials["Rations"] -= $Ration_pr_worker * $Calculated_Worker_Req;
$Calculated_Tooltip['Rations'][] = "Rations used by workers: -" . $Ration_pr_worker * $Calculated_Worker_Req . " (" . $Ration_pr_worker . " * " .$Calculated_Worker_Req . ")<br/>";
$Calculated_Tooltip['Rations'][] = "Ration pr. worker: " . $Ration_pr_worker;
$Calculated_Tooltip['Rations'][] = "Number of workers: " . $Calculated_Worker_Req;
	
for ($r=1;$r<=$Base_Extractor_Slots;$r++) { 
	if ($Build_Items["Base_Extractor" . $r]['Name'] != "") {
		$Calculated_Extracted_Materials[$Build_Items["Base_Extractor" . $r]['Commodity']] += $Build_Items["Base_Extractor" . $r]['Extraction_Rate'] * $Mods[267]['Value'] * $Build_Items["Base_Extractor" . $r]['Amount'];
		$Calculated_Tooltip[str_replace(" ","_",str_replace("'","",$Build_Items["Base_Extractor" . $r]['Commodity']))][] = $Build_Items["Base_Extractor" . $r]['Name'] . ": +" . $Build_Items["Base_Extractor" . $r]['Extraction_Rate'] * $Mods[267]['Value'] . " (" . $Build_Items["Base_Extractor" . $r]['Extraction_Rate'] . " * "  . $Mods[267]['Value'] . ")";

		for ($l=1;$l<=5;$l++) {
			if ($Build_Items["Base_Extractor" . $r]['Required Amount ' . $l] <> 0) {
				$Calculated_Extracted_Materials[$Build_Items["Base_Extractor" . $r]['Required Commodity ' . $l]] -= $Build_Items["Base_Extractor" . $r]['Required Amount ' . $l] * $Build_Items["Base_Extractor" . $r]['Amount'];
				$Calculated_Tooltip[str_replace(" ","_",str_replace("'","",$Build_Items["Base_Extractor" . $r]['Required Commodity ' . $l]))][] = $Build_Items["Base_Extractor" . $r]['Name'] . ": -" . $Build_Items["Base_Extractor" . $r]['Required Amount ' . $l];
			}
		}
	}
}

//
//	Weapon Calculation
$m=0;
$deleteditems=0;
unset($Build_Weapons);
for ($r=1;$r<=$Base_Weapon_Slots;$r++) {
	$Build_Items['Base_Weapon' . $r]['Multifire'] = 1; 
	$Build_Weapons['Base_Weapon' . $r] = $Build_Items['Base_Weapon' . $r];
}
for ($r=1;$r<=$Base_Weapon_Slots;$r++) {
	if (isset($Build_Items['Base_Weapon' . $r])) {
		
		for ($l=$r+1+$deleteditems;$l<=$Base_Weapon_Slots;$l++) {
			if (isset($Build_Weapons['Base_Weapon' . $l])) {
				$Duplicate = TRUE;
				
				if ($Build_Items['Base_Weapon' . $r]["Name"] != $Build_Weapons['Base_Weapon' . $l]["Name"]) {
					$Duplicate = FALSE;
				}
				// 17 = Multifire
				if ($Duplicate == TRUE and $Mods[17]['Value'] >= ($Build_Items['Base_Weapon' . $r]['Multifire']+1)) {
					//echo "Unsetting r: " . $r . " - l: " . $l . " -- " . $Build_Items['Weapon' . $r]['Weapon_ID'] . " --> " . $Build_Weapons['Weapon' . $l]['Weapon_ID'] . "<br/>";
					unset($Build_Weapons['Base_Weapon' . $l]);
					if ($l == $r+1+$deleteditems) {
						//$deleteditems++;
					}
					//$N_Multifire++;
					$Build_Items['Base_Weapon' . $r]['Multifire']++;// = $N_Multifire;
				}
				
				$m++;
				if ($m == 2000) {
					break 2;
				}
			}
		}
	}
}

//echo "<pre>";
//print_r($Build_Weapons);
//echo "</pre>";
$N_Weapons = 0;
for ($r=1;$r<=$Base_Weapon_Slots;$r++) {
	if (!isset($Build_Weapons['Base_Weapon' . $r])) {
		unset($Build_Items['Base_Weapon' . $r]);
	}
	if ($Build_Items['Base_Weapon' . $r]['Item_ID'] != "") {
		$N_Weapons++;
	}
}

for ($r=1;$r<=$Base_Weapon_Slots;$r++) { 
	if ($Build_Items["Base_Weapon" . $r]['Name'] != "") {

		//
		// Weapon Damage
		$Weapon_Damage_Calculation = ($Build_Items["Base_Weapon" . $r]['Damage_Max'] + $Build_Items["Base_Weapon" . $r]['Damage_Min']) / 2; 
		$Weapon_Damage_Calculation += ($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type'] . " Damage")]['Value'] : 0); 
		$Weapon_Damage_Calculation *= $Build_Items["Base_Weapon" . $r]['Projectiles'];
		$Weapon_Damage_Calculation *= $Mods[4]['Value'];
		$Weapon_Damage_Calculation *= ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value'] : 1);
		$Weapon_Damage_Calculation *= ($Build_Items["Base_Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1);
		$Weapon_Damage_Calculation *= $Build_Items["Base_Weapon" . $r]['Multifire'];
		$Weapon_Damage_Calculation *= ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference" ? $Mods[188]['Value'] : 1);
 
		
		$Calculated_Damage[$r] = $Weapon_Damage_Calculation;
			//(((($Build_Items["Base_Weapon" . $r]['Damage_Max'] + $Build_Items["Base_Weapon" . $r]['Damage_Min'])/2)+($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type'] . " Damage")]['Value'] : 0))*$Build_Items["Base_Weapon" . $r]['Projectiles']) * $Mods[4]['Value'] * ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value'] : 1) * ($Build_Items["Base_Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1) * $Build_Items["Base_Weapon" . $r]['Multifire'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage: " . round($Calculated_Damage[$r],2) . "";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Critical Damage: " . floor($Calculated_Damage[$r] * (1.5 * $Mods[3]['Value'])) . " (" . floor($Calculated_Damage[$r]) . " * " . (1.5 * $Mods[3]['Value']) . ")";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage pr. projectile: " . round($Calculated_Damage[$r]/$Build_Items["Base_Weapon" . $r]['Projectiles'],0) . "";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage pr. weapon: " . floor($Calculated_Damage[$r]/$Build_Items["Base_Weapon" . $r]['Multifire']) . "<br/>";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Damage calculation: ((" . $Build_Items["Base_Weapon" . $r]['Damage_Max'] . " + " . $Build_Items["Base_Weapon" . $r]['Damage_Min'] . ") / 2) + " . ($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type'] . " Damage")]['Value'] : 0) . ") * " . $Build_Items["Base_Weapon" . $r]['Projectiles'] . ") * " . round($Mods[4]['Value'],2) . ($Mods[array_search_multi($Mods, "Name","Damage ".$Build_Items["Base_Weapon" . $r]['Type'])]['Value']<>0 ? " * " . $Mods[array_search_multi($Mods, "Name","Damage ".$Build_Items["Base_Weapon" . $r]['Type'])]['Value'] : "") . " * " . ($Build_Items["Base_Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1) . ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference" ? " * " . $Mods[188]['Value'] : "") . " * " . $Build_Items["Base_Weapon" . $r]['Multifire'] . "<br/>";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Build_Items["Base_Weapon" . $r]['Name'] . " (Max): " . $Build_Items["Base_Weapon" . $r]['Damage_Max'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Build_Items["Base_Weapon" . $r]['Name'] . " (Min): " . $Build_Items["Base_Weapon" . $r]['Damage_Min'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Bonus projectil damage: " . ($Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type']. " Damage")]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Projectil ". $Build_Items["Base_Weapon" . $r]['Type'] . " Damage")]['Value'] : 0);
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Number of projectiles: " . $Build_Items["Base_Weapon" . $r]['Projectiles'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Mods[4]['Name'] . " multiplier: " . round($Mods[4]['Value'],2);
		$Calculated_Tooltip['Weapon_Damage'.$r][] = $Build_Items["Base_Weapon" . $r]['Type'] . " damage multiplier: " . ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value'] : 1);
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Ethereal damage multiplier: " . ($Build_Items["Base_Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1);
		if ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference") {
			$Calculated_Tooltip['Weapon_Damage'.$r][] = "Transference strength: " . ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference" ? $Mods[188]['Value'] : 1);
		}
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Multifire: " . $Build_Items["Base_Weapon" . $r]['Multifire'];
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

		//
		// Weapon Self Damage
		$Weapon_Damage_Calculation = $Build_Items["Base_Weapon" . $r]['Damage_Self']; 
		$Weapon_Damage_Calculation *= $Mods[4]['Value'];
		$Weapon_Damage_Calculation *= ($Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value']<>0 ? $Mods[array_search_multi($Mods, "Name","Damage ". $Build_Items["Base_Weapon" . $r]['Type'])]['Value'] : 1);
		$Weapon_Damage_Calculation *= ($Build_Items["Base_Weapon" . $r]['Ethereal'] == 1 ? $Mods[125]['Value'] : 1);
		$Weapon_Damage_Calculation *= $Build_Items["Base_Weapon" . $r]['Multifire'];
		$Weapon_Damage_Calculation *= ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference" ? $Mods[188]['Value'] : 1);
		$Weapon_Damage_Calculation *= ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference" ? (1/$Mods[187]['Value']) : 1);
		$Calculated_Self_Damage[$r] = $Weapon_Damage_Calculation;
		
		if ($Build_Items["Base_Weapon" . $r]['Type'] == "Transference") {
			//$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Transference efficiency: " . round((1/$Mods[187]['Value']),2). " (1 / " . $Mods[187]['Value'] . ")";
		}
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "<br/>Self damage: " . round($Weapon_Damage_Calculation,2) . " (" . $Build_Items["Base_Weapon" . $r]['Damage_Self'] . " * Damage increase * " . round((1/$Mods[187]['Value']),2) . ")";
		$Calculated_Tooltip['Weapon_Damage'.$r][] = "Transference Efficiency: " . round((1/$Mods[187]['Value']),2);
		
		//
		//	Weapon Recoil
		$Calculated_Recoil[$r] = max($Build_Items["Base_Weapon" . $r]['Recoil'] / $Mods[23]['Value'],0.1);
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "Recoil: " . round($Calculated_Recoil[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "Recoil calculation: max(" . $Build_Items["Base_Weapon" . $r]['Recoil'] . " / " . round($Mods[23]['Value'],2) . ", 0.1)<br/>";
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = $Build_Items["Base_Weapon" . $r]['Name'] . ": " . $Build_Items["Base_Weapon" . $r]['Recoil'];
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = $Mods[23]['Name'] . " multiplier: " . round($Mods[23]['Value'],2);
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "Lower limit on recoil: 0.1";
		$Calculated_Tooltip['Weapon_Recoil'.$r][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";

		//
		//	Weapon Electricity
		$Calculated_Weapon_Electricity[$r] = ($Build_Items["Base_Weapon" . $r]['Electricity'] * $Mods[8]['Value'] * $Build_Items["Base_Weapon" . $r]['Multifire']);
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Electricity: " . $Calculated_Weapon_Electricity[$r];
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "EPS: " . round($Calculated_Weapon_Electricity[$r]/$Calculated_Recoil[$r],0) . "<br/>";
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Electricity calculation: " . $Build_Items["Base_Weapon" . $r]['Electricity'] . " * " . $Mods[8]['Value'] . " * " . $Build_Items["Base_Weapon" . $r]['Multifire'] . "<br/>";
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = $Build_Items["Base_Weapon" . $r]['Name'] . ": " . $Build_Items["Base_Weapon" . $r]['Electricity'];
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = $Mods[8]['Name'] . " multiplier: " . $Mods[8]['Value'];
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "Multifire: " . $Build_Items["Base_Weapon" . $r]['Multifire'];
		$Calculated_Tooltip['Weapon_Electricity'.$r][] = "<br/>(See permanent bonus tooltip for multiplier calculation.)";
		
		//
		//	Weapon Electricity
		$Calculated_EPS[$r] = round($Build_Items["Base_Weapon" . $r]['Electricity'] * $Mods[8]['Value'] * $Build_Items["Base_Weapon" . $r]['Multifire']/$Calculated_Recoil[$r],0);
		$Calculated_Tooltip['Weapon_EPS'.$r] = $Calculated_Tooltip['Weapon_Electricity'.$r];
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "<br/>EPS: " . round($Calculated_Weapon_Electricity[$r] / $Calculated_Recoil[$r],0) . "<br/>";
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "Weapon EPS: " . number_format($Calculated_Weapon_Electricity[$r],2,","," ") . " / " . number_format($Calculated_Recoil[$r],2,","," ");
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "Weapon Electricity: " . number_format($Calculated_Weapon_Electricity[$r],2,","," ");
		$Calculated_Tooltip['Weapon_EPS'.$r][] = "Weapon Recoil: " . number_format($Calculated_Recoil[$r],2,","," ");
		
		//
		//	Weapon DPS
		$Calculated_DPS[$r] = (floor($Calculated_Damage[$r])) / (floor($Calculated_Recoil[$r]*1000)/1000);
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "DPS: " . round($Calculated_DPS[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "DPS calculation: " . round($Calculated_Damage[$r],2) . " / " . round($Calculated_Recoil[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "Weapon damage: " . round($Calculated_Damage[$r],2);
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "Weapon recoil: " . round($Calculated_Recoil[$r],2);

		//
		//	Weapon Critical DPS
		$Critical_Chance = min($Mods[1]['Value']-1, 0.99);
		// $Calculated_Crit_DPS[$r] = $Calculated_DPS[$r] * (1 + 0.5 * $Mods[3]['Value']) * (1 + (0.01 + ($Mods[1]['Value']-1)) * $Build_Items["Base_Weapon" . $r]['Multifire']);
		$Calculated_Crit_DPS[$r] =  $Calculated_DPS[$r] * (1 + 0.5 * $Mods[3]['Value']) * (0.01 + $Critical_Chance);
		$Calculated_Crit_DPS[$r] += $Calculated_DPS[$r] * (1-(0.01 + $Critical_Chance));
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "<br/><br/>" . "DPS (w/Crit): " . floor($Calculated_Crit_DPS[$r]) . "<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = "DPS (w/Crit) calculation: " . round($Calculated_DPS[$r],2) . " * (1 + 0.5 * " . round($Mods[3]['Value'],2) . ") * (1 + (0.01 + " . round($Critical_Chance,2) . ") + DPS * " . (1-(0.01 + $Critical_Chance)) . ")<br/>";
		$Calculated_Tooltip['Weapon_DPS'.$r][] = $Mods[3]['Name'] . ": " . round($Mods[3]['Value'],2);
		$Calculated_Tooltip['Weapon_DPS'.$r][] = $Mods[1]['Name'] . ": " . round(($Mods[1]['Value']-1),2);
//		$Calculated_Tooltip['Weapon_DPS'.$r][] = "Multifire: " . $Build_Items["Base_Weapon" . $r]['Multifire'];

		//
		//	Weapon DPE
		$Calculated_DPE[$r] = round(floor($Calculated_Damage[$r]) / ($Build_Items["Base_Weapon" . $r]['Electricity'] * $Build_Items["Base_Weapon" . $r]['Multifire']),2);
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "DPE: " . round($Calculated_DPE[$r],2) . "";
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "DPE w/Electric tempering: " . round($Calculated_Damage[$r]/$Calculated_Weapon_Electricity[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "DPE calculation: " . round($Calculated_Damage[$r],2) . " / " . $Build_Items["Base_Weapon" . $r]['Electricity'] . "<br/>";
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "Weapon damage: " . round($Calculated_Damage[$r],2);
		$Calculated_Tooltip['Weapon_DPE'.$r][] = "Weapon electricity: " . ($Build_Items["Base_Weapon" . $r]['Electricity'] * $Build_Items["Base_Weapon" . $r]['Multifire']);

		//
		//	Weapon Range
		$Calculated_Range[$r] = min(($Build_Items["Base_Weapon" . $r]['W_Range']*$Mods[22]['Value'])*2, ($Build_Items["Base_Weapon" . $r]['W_Range']*$Mods[22]['Value'])+$Mods[180]['Value']);
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Range: " . round($Calculated_Range[$r],2) . "<br/>";
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Range calculation: " . "min(" . round((($Build_Items["Base_Weapon" . $r]['W_Range']*$Mods[22]['Value'])*2),2) . ", " . round((($Build_Items["Base_Weapon" . $r]['W_Range']*$Mods[22]['Value'])+$Mods[180]['Value']),2) . ")";
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Range calculation: " . "min(" . $Build_Items["Base_Weapon" . $r]['W_Range'] . " * " . round($Mods[22]['Value'],2) . " * 2, " . $Build_Items["Base_Weapon" . $r]['W_Range'] . " * " . round($Mods[22]['Value'],2) . " + " . $Mods[180]['Value'] . ")" . "<br/>";
		$Calculated_Tooltip['Weapon_Range'.$r][] = $Build_Items["Base_Weapon" . $r]['Name'] . ": " . $Build_Items["Base_Weapon" . $r]['W_Range'];
		$Calculated_Tooltip['Weapon_Range'.$r][] = $Mods[22]['Name'] . ": " . round($Mods[22]['Value'],2);
		$Calculated_Tooltip['Weapon_Range'.$r][] = "Upper limit of ZOFR: *2 ";
		$Calculated_Tooltip['Weapon_Range'.$r][] = $Mods[180]['Name'] . " (ZOFR): " . $Mods[180]['Value'];

		//
		//	Weapon Sustainable
		if (min(($Calculated_Electricity - ($Calculated_Weapon_Electricity[$r] / $Calculated_Recoil[$r])),0) != 0)	{ 
			$Calculated_Sustainable[$r] = -$Calculated_Energy_Bank/($Calculated_Electricity-($Calculated_Weapon_Electricity[$r]/$Calculated_Recoil[$r]));
		} else {
			$Calculated_Sustainable[$r] = "&infin;";
		}
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Sustainable: " . $Calculated_Sustainable[$r] . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Sustainable calculation: -" . $Calculated_Energy_Bank . " / (" . $Calculated_Electricity . " - (" . $Calculated_Weapon_Electricity[$r] . " / " . $Calculated_Recoil[$r] . "))" . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Energy bank: " . $Calculated_Energy_Bank;
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Weapon electricity: " . $Calculated_Weapon_Electricity[$r];
		$Calculated_Tooltip['Weapon_Sustainable'.$r][] = "Weapon recoil: " . $Calculated_Recoil[$r];

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
		
		
// 		//
// 		// Weapon Sustainable DPS
// 		$Calculated_Sustainable_DPS[$r] = min($Calculated_DPS[$r], $Calculated_Damage[$r] / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity));
// 		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "DPS: " . number_format($Calculated_Sustainable_DPS[$r],2,","," ") . "<br/>";
// 		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "DPS calculation: min (" . round($Calculated_DPS[$r],2) . ", " . round($Calculated_Damage[$r],2) . " / (" . round($Calculated_Weapon_Electricity[$r],2) . " / " . round($Calculated_Electricity,2) . "))<br/>";
// 		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Weapon DPS: " . round($Calculated_DPS[$r],2);
// 		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Weapon damage: " . round($Calculated_Damage[$r],2);
// 		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Weapon electricity: " . $Calculated_Weapon_Electricity[$r];
// 		$Calculated_Tooltip['Weapon_Sustainable_DPS'.$r][] = "Electricity: " . $Calculated_Electricity;

		//
		// Weapon Sustainable Critical DPS
		if (min(abs($Calculated_Crit_DPS[$r]), abs(($Calculated_Crit_DPS[$r] * $Calculated_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity))) == abs($Calculated_Crit_DPS[$r])) {
			$Calculated_Sustainable_Crit_DPS[$r] = $Calculated_Crit_DPS[$r];
		} else {
			$Calculated_Sustainable_Crit_DPS[$r] = ($Calculated_Crit_DPS[$r] * $Calculated_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity);
		}
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "DPS: " . number_format($Calculated_Sustainable_DPS[$r],2,","," ") . "<br/>";
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "DPS calculation: min (" . floor(abs($Calculated_Crit_DPS[$r])) . ", " . number_format(abs(($Calculated_Crit_DPS[$r] * $Calculated_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity)),2,","," ") . ")<br/>";
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "Weapon Critical DPS: " . number_format($Calculated_Crit_DPS[$r],2,","," ");
		$Calculated_Tooltip['Weapon_Sustainable_Crit_DPS'.$r][] = "Weapon Sustainable Critical DPS: " . number_format(($Calculated_Crit_DPS[$r] * $Calculated_Recoil[$r]) / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity),2,","," ");		
		
		//
		//	Weapon Sustainable DPS wSun
		$Calculated_Sustainable_DPS_wSun[$r] = min($Calculated_DPS[$r], $Calculated_Damage[$r] / ($Calculated_Weapon_Electricity[$r] / $Calculated_Electricity_Sun));
	}
}

//
//
//	Resistance Calculation
$Damage_Types = array("Physical", "Surgical", "Radiation", "Mining", "Transference", "Heat", "Laser", "Energy");
$Damage_Type_Mods = array(184, 186, 185, 183, 152, 181, 182, 189);
$r=1;
foreach ($Damage_Types as $Damage_Type) {
	for ($l=1;$l<=$Base_Diffuser_Slots;$l++) {
		${"Base_Diffuser_".$Damage_Type} = max(${"Base_Diffuser_".$Damage_Type}, $Build_Items["Base_Diffuser".$l]['Resistance_'.$Damage_Type]);
	}
	$Resistance[$r]['Image'] = "";
	$Resistance[$r]['Name'] = $Damage_Type;
	$Resistance[$r]['Value'] = round(1-((1-$Build_Items["Base"]['Resistance_'.$Damage_Type])*(1-${"Base_Diffuser_".$Damage_Type})/($Mods[24]['Value'])),4) * 100;
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = $Damage_Type . " resistance: " . $Resistance[$r]['Value'] . "</br>";
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = $Damage_Type . " resistance calculation: round(1 - ((1 - " . $Build_Items["Base"]['Resistance_'.$Damage_Type] . ") * (1 - " . ${"Base_Diffuser_".$Damage_Type} . ") / (" . $Mods[24]['Value'] . ")), 4) * 100";
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = "Initial resistance: " . $Build_Items["Base"]['Resistance_'.$Damage_Type];
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = "Diffuser: " . ${"Base_Diffuser_".$Damage_Type};
	$Calculated_Tooltip['Resistance_' . $Damage_Type][] = "Resistance multiplier: " . $Mods[24]['Value'];
	$r++;	
}
?>