<?php
$Calc_Item_Mods = array();
$r=0;
$Query = "SELECT * FROM User_Build_Item_Mods LEFT JOIN Item_Mods ON User_Build_Item_Mods.Item_Mod_ID = Item_Mods.Item_Mods_ID LEFT JOIN User_Build_Items ON User_Build_Items.User_Build_ID = User_Build_Item_Mods.Build_ID and User_Build_Items.Item_Type = User_Build_Item_Mods.Item_Type WHERE User_Build_Item_Mods.Build_ID = " . $Build_ID;
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['ID'] = $row['Build_Item_Mod_ID'];
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Name'] = $row['Name'];
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Item_Type'] = $row['Item_Type'];
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Item_ID'] = $row['Item_ID'];
	
	// Enable iteration pr. item
	$Calc_Items[$row['Item_Type']]['ID'] = $row['Item_ID'];
	$Calc_Items[$row['Item_Type']]['Item'] = $row['Item_Type'];
	$Calc_Items[$row['Item_Type']]['Item_Type'] = preg_replace('/[0-9]+/', '', $row['Item_Type']);
	$Calc_Items[$row['Item_Type']]['Tech'] = $Build_Items[$row['Item_Type']]['Tech'];
	$Calc_Items[$row['Item_Type']]['Item_Mods'][] = $row['Item_Mod_ID'];
	$Calc_Items[$row['Item_Type']]['Disable'] = FALSE;
	$Calc_Items[$row['Item_Type']]['N_Identical'] = 1;
	

	if ($row['Mod1_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus1_ID'] = $row['Mod1_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus1_Value'] = $row['Mod1_Initial'] + $row['Mod1_Tech']*$Build_Items[$row['Item_Type']]['Tech'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus1_Calc'] = $row['Mod1_Initial'] . " + " . $row['Mod1_Tech'] . " * " . $Build_Items[$row['Item_Type']]['Tech'];
	}
	if ($row['Mod2_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus2_ID'] = $row['Mod2_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus2_Value'] = $row['Mod2_Initial'] + $row['Mod2_Tech'] * $Build_Items[$row['Item_Type']]['Tech'];
	}
	if ($row['Mod3_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus3_ID'] = $row['Mod3_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus3_Value'] = $row['Mod3_Initial'] + $row['Mod3_Tech'] * $Build_Items[$row['Item_Type']]['Tech'];
	}
	if ($row['Mod_Flat_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus_Flat_ID'] = $row['Mod_Flat_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus_Flat_Value'] = $row['Mod_Flat_Initial'] + ($row['Mod_Flat_Tech']<>0 ? $row['Mod_Flat_Tech'] * $Build_Items[$row['Item_Type']]['Tech'] : 0);
	}

	if ($row['Mod_Specific'] == "X") {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Global'] = "";	
	} else {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Global'] = "X";
	}	
	$r++;
}

foreach ($Mods as $Mod_ID => $Mod_Name) {
	$Skill_Mods[$Mod_ID]['Name'] = $Mods[$Mod_ID]['Name'];
}

//
//	Calculate skill bonuses together
foreach ($Mods as $Mod_ID => $Mod_Name) {
	$r=0;
	if ($Skill_Mods[$Mod_ID]['ToT_Calculation'] == "") {
		$Skill_Mods[$Mod_ID]['Value'] = 1;
		if ($Mod_ID != 17 and $Mod_ID != 1) {
			$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= "((1";
		} else {
			$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= "1";
		}
	}
	// Indicate a start of the addition
	$Current_Mod_ID = 0;
	$Query = "SELECT * FROM Skill_Mods LEFT JOIN Skills ON Skills.Excel_Skill_ID = Skill_Mods.Skill_ID LEFT JOIN User_Skills ON User_Skills.Skill_ID = Skills.Excel_Skill_ID WHERE Skill_Mods.Excel_Mod_ID = " . $Mod_ID . " AND User_Skills.Skill_Level > 0 AND User_Skills.User_ID = " . $Builds[$Build_ID]['User_ID'] . " AND User_Skills.Character_ID = " . $Builds[$Build_ID]['Character_ID'];
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/>Query: " . $Query);
	$Last_Row = mysql_num_rows($Result);
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Match_On_Ship_Specific = FALSE;

		$row['Value'] = $row['Value'] + 0;
		$row['Skill_Level'] = $row['Skill_Level'] + 0;
		
		if ($row['Excel_Skill_ID'] == 51) {$Berserker_Class = $row['Skill_Level'];}
		if ($row['Excel_Skill_ID'] == 53) {$Gunner_Class = $row['Skill_Level'];}
		
		if ($Builds[$Build_ID]['Character_ID'] > 0 and $Builds[$Build_ID]['Type'] == 3) {
			//
			//
			// Bases
			//
			//
			$row['Value'] = $row['Value'] + 0;
			/*
			if ($row['Excel_Skill_ID'] >= 47 and $row['Excel_Skill_ID'] <= 82 and strpos($Skill_Mods[$Mod_ID]['Name'], "Station") === FALSE) {
				$row['Skill_Level'] = floor($row['Skill_Level']/2) + 0;
			} else {
				$row['Skill_Level'] = ($row['Skill_Level'] + 0);
			}
			*/
			$row['Skill_Level'] = ($row['Skill_Level'] + 0);

			if (strpos($Skill_Mods[$Mod_ID]['Name'], "Station") !== FALSE and $Skill_Mods[$Mod_ID]['Name'] != "Station Slots") {
				$Current_Mod_ID = $Mod_ID;
				$Mod_ID = array_search_multi($Mods, "Name", substr($Skill_Mods[$Mod_ID]['Name'], strlen("Station") + 1 + strpos($Skill_Mods[$Mod_ID]['Name'], "Station")));
				if ($Mod_ID == 0) {
					echo "Couldn't identify the base of " . $Current_Mod_ID;
				}
				if ($Skill_Mods[$Mod_ID]['ToT_Calculation'] == "") {
					$Skill_Mods[$Mod_ID]['Value'] = 1;
					$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= "((1";
				}
					
				if ($Mod_ID == 40 or $Mod_ID == 41 or $Mod_ID == 1 or $Mod_ID == 124 or $Mod_ID == 50 or $Mod_ID == 219) {
					$Skill_Mods[$Mod_ID]['Value'] += ($row['Value'] * $row['Skill_Level']);
					$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " + " . ($row['Value'] * $row['Skill_Level']);

					$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] " . str_replace("_"," ",$row['Name']) . " = " . ($row['Value'] * $row['Skill_Level']) . " (" . $row['Value'] . " * " . $row['Skill_Level'] . ")";
				} else {
					$Skill_Mods[$Mod_ID]['Value'] *= (1+ ($row['Value'] * $row['Skill_Level']));
					$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " * " . (1 + ($row['Value'] * $row['Skill_Level']));

					$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] " . str_replace("_"," ",$row['Name']) . " = " . (1 + ($row['Value'] * $row['Skill_Level'])) . " (1 + (" . $row['Value'] . " * " . $row['Skill_Level'] . "))";
				}
			}
		}
		$r++;
		${$Mod_ID."r"}++;
		if ($Current_Mod_ID != 0) {
			$Mod_ID = $Current_Mod_ID;
		}
	}
}

foreach ($Mods as $Mod_ID => $Mod_Name) {
	if ($Mod_ID == 17 and $Berserker_Class > 0) {
		$Skill_Mods[$Mod_ID]['Value'] += 1;
		$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " + 1";
		$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] Berserker Class Lvl 1 = +1";
	}
	if ($Mod_ID == 17 and $Gunner_Class > 0) {
		$Skill_Mods[$Mod_ID]['Value'] += 1;
		$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " + 1";
		$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] Gunner Class Lvl 1 = +1";
	}
	
	if ($Mod_ID != 17 and $Mod_ID != 1) {
		$Skill_Mods[$Mod_ID]['Value'] = ($Skill_Mods[$Mod_ID]['Value']-1);
		$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= ") -1)";
	}
}


//
//	Add all augmenter mods together
foreach ($Mods as $Mod_ID => $Mod_Name) {
	$r=0;
	// LEFT JOIN Mods ON Skill_Mods.Excel_Mod_ID = Mods.Excel_Mod_ID 
	$Query = "SELECT * FROM Base_Augmenter_Mods RIGHT JOIN User_Build_Items ON User_Build_Items.Item_ID = Base_Augmenter_Mods.Base_Augmenter_ID LEFT JOIN Base_Augmenters ON Base_Augmenters.Base_Augmenter_ID = Base_Augmenter_Mods.Base_Augmenter_ID WHERE Base_Augmenter_Mods.Excel_Mod_ID = " . $Mod_ID ." AND User_Build_Items.Item_Type LIKE 'Base_Augmenter%' AND User_Build_Items.User_Build_ID = " . $Build_ID . " AND Value <> 0";
	$Result = mysql_query($Query, $conn) or die(mysql_error());
	$Last_Row = mysql_num_rows($Result);
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		if ($r==0) {
			if ($Mods[$Mod_ID]['ToT_Calculation'] != "") {
				$Mods[$Mod_ID]['ToT_Calculation'] .= "((";
			} else {
				$Mods[$Mod_ID]['ToT_Calculation'] .= " + ((";
			}
		}

		$Augmenter_Mods[$Mod_ID]['Name'] = $Mods[$Mod_ID]['Name'];

		//	Add all aug effects together
		if ($Mod_ID != 17 and $Mod_ID != 1) {
			$Augmenter_Mods[$Mod_ID]['Value'] += ($row['Value']>0 ? $row['Value'] : ($row['Value']/(1+$row['Value'])));

			$Mods[$Mod_ID]['ToT_Calculation'] .= ($r==0 ? "" : " + ") . ($row['Value']>0 ? $row['Value'] : round(($row['Value']/(1+$row['Value'])),2));
			$Mods[$Mod_ID]['Calculation_'.${$Mod_ID."r"}] = "[Aug] " . $row['Name'] . " = " . ($row['Value']>0 ? $row['Value'] : ($row['Value']/(1+$row['Value']))) . ($row['Value']>0 ? "" : " (" . $row['Value'] . "/(1+" . $row['Value'] . "))");
		} else {
			$Augmenter_Mods[$Mod_ID]['Value'] += $row['Value'];

			$Mods[$Mod_ID]['ToT_Calculation'] .= ($r==0 ? "" : " + ") . $row['Value'];
			$Mods[$Mod_ID]['Calculation_'.${$Mod_ID."r"}] = "[Aug] " . $row['Name'] . " = " . $row['Value'];
		}


		if ($r==$Last_Row-1) {
			// Multiply with the amount of Aug tweak 
			$Augmenter_Mods[$Mod_ID]['Value'] *= (1+$Skill_Mods[40]['Value']);

			$Mods[$Mod_ID]['ToT_Calculation'] .= ") * " . (1+$Skill_Mods[40]['Value']) . ")";

			if ($Augmenter_Mods[$Mod_ID]['Value']<0 and $Mod_ID != 17 and $Mod_ID != 1) {
				//$Mods[$Mod_ID]['ToT_Calculation'] = "( (" . $Mods[$Mod_ID]['ToT_Calculation'] . ") /(1 - (" . $Augmenter_Mods[$Mod_ID]['Value'] . "))";
			}

			// Add the Aug multiplyer to the overall number
			if ($Mod_ID != 17 and $Mod_ID != 1) {
				//$Mods[$Mod_ID]['Value'] = ($Augmenter_Mods[$Mod_ID]['Value']>0 ? $Augmenter_Mods[$Mod_ID]['Value'] : ($Augmenter_Mods[$Mod_ID]['Value']/(1-$Augmenter_Mods[$Mod_ID]['Value'])));
				$Mods[$Mod_ID]['Value'] = $Augmenter_Mods[$Mod_ID]['Value'];
			} else {
				$Mods[$Mod_ID]['Value'] = $Augmenter_Mods[$Mod_ID]['Value'];
			}

			$Mods[$Mod_ID]['Aug_value'] = $Mods[$Mod_ID]['Value'];
			${$Mod_ID."r"}++;
			$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] " . $Skill_Mods[40]['Name'] . " = " . ($Skill_Mods[40]['Value']+1) . " (" . $Skill_Mods[40]['Value'] . " + 1)";
		}

		$r++;
		${$Mod_ID."r"}++;
	}
}

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
	"Base_Extractor" => "Base_Extractors",
	"Base_Overcharger" => "Base_Overchargers",//,
	//"Base_Exterminator" => "Base_Exterminators"
);

if ($Include_Temp_Bonus == TRUE) {
	$Item_Names['Base_Aura_Generator'] = 'Base_Aura_Generators';
}

//
//	Add all bonuses from items to the overall bonus
foreach ($Item_Names as $Item => $Items) {
	foreach ($Mods as $Mod_ID => $Mod_Name) {
		$r=0;
		// LEFT JOIN Mods ON Skill_Mods.Excel_Mod_ID = Mods.Excel_Mod_ID 
		$Query = "SELECT * FROM User_Build_Items LEFT JOIN " . $Item . "_Mods ON User_Build_Items.Item_ID = " . $Item . "_Mods." . $Item . "_ID LEFT JOIN " . $Items . " ON " . $Items . "." . $Item . "_ID = " . $Item . "_Mods." . $Item . "_ID WHERE " . $Item . "_Mods.Excel_Mod_ID = " . $Mod_ID ." AND User_Build_Items.Item_Type REGEXP '^" . $Item . "[0-9]?$' AND User_Build_Items.User_Build_ID = " . $Build_ID;
		$Result = mysql_query($Query, $conn) or die(mysql_error());
		$Last_Row = mysql_num_rows($Result);
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			if ($r==0) {
				if ($Mods[$Mod_ID]['Value'] == 1 and $Augmenter_Mods[$Mod_ID]['Value'] == 0) {
					$Mods[$Mod_ID]['ToT_Calculation'] .= "";
				} else {
					$Mods[$Mod_ID]['ToT_Calculation'] .= " + ";
				}
			}
			$Mods[$Mod_ID]['Value'] += ($row['Value']>0 ? $row['Value'] : ($row['Value']/(1+$row['Value'])));
			$Mods[$Mod_ID]['ToT_Calculation'] .= ($r==0 ? "" : " + ") . ($row['Value']>0 ? $row['Value'] : round($row['Value']/(1+$row['Value']),3));
			$Mods[$Mod_ID]['Calculation_'.${$Mod_ID."r"}] = "[Item Bonus] " . $row['Name'] . " = " . round($row['Value']>0 ? $row['Value'] : ($row['Value']/(1+$row['Value'])),3) . ($row['Value']>0 ? "" :  " (" . "(" . $row['Value'] . " / " . "(1 + " . $row['Value']. "))" );
				
			if ($r==$Last_Row-1) {
				$Mods[$Mod_ID]['ToT_Calculation'] .= ")";
			}
			
			${$Mod_ID."r"}++;
			$r++;
		}
	}
}

foreach ($Mods as $Mod_ID => $Mod_Name) {
	if ($Mods[$Mod_ID]['Value']<0) {
		$Mods[$Mod_ID]['ToT_Calculation'] = "(" . $Mods[$Mod_ID]['ToT_Calculation'] . " / (1 - " . round($Mods[$Mod_ID]['Value'],3) . "))";
		$Mods[$Mod_ID]['Value'] = ($Mods[$Mod_ID]['Value']/(1-$Mods[$Mod_ID]['Value']));
	} 
}

//
//	Multiply Mod with Skill mod
foreach ($Mods as $Mod_ID => $Mod_Name) {
	if ($Skill_Mods[$Mod_ID]['Name'] != "Augmenter Effect" and $Skill_Mods[$Mod_ID]['Name'] != "Neuro Tweak") {

		$Mods[$Mod_ID]['Skill_Multiplyer'] = $Skill_Mods[$Mod_ID]['Value']+1;
		$Mods[$Mod_ID]['Aug_Item_Multiplyer'] = $Mods[$Mod_ID]['Value']+1;
		
		// 1 = Critical Hit Chance
		// 17 = Multifire
		if ($Mod_ID == 17 or $Mod_ID == 1) {
			$Mods[$Mod_ID]['Value'] = $Mods[$Mod_ID]['Value'] + $Skill_Mods[$Mod_ID]['Value'];

			if ($Mods[$Mod_ID]['ToT_Calculation']=="((") {
				$Mods[$Mod_ID]['ToT_Calculation'] .= "0";
			}

			$Mods[$Mod_ID]['ToT_Calculation'] .= ")) + (" . $Skill_Mods[$Mod_ID]['ToT_Calculation'] . ")";
		} else {
			$Mods[$Mod_ID]['Value'] = (1+$Mods[$Mod_ID]['Value']) * (1+$Skill_Mods[$Mod_ID]['Value']);
			if ($Mods[$Mod_ID]['ToT_Calculation']=="((") {
				$Mods[$Mod_ID]['ToT_Calculation'] .= "0";
			}

			$Mods[$Mod_ID]['ToT_Calculation'] .= ") + 1) * (" . $Skill_Mods[$Mod_ID]['ToT_Calculation'] . " + 1)";
		}
	}
}
if (!isset($Calc_Items)) {
	$Calc_Items = array();
}
foreach ($Calc_Items as $Item) {
	if ($Calc_Items[$Item['Item']]['Disable'] == FALSE) {
		foreach ($Calc_Items as $Item2) {
			if ($Item['ID'] == $Item2['ID'] and $Item['Item_Type'] == $Item2['Item_Type'] and $Item['Tech'] == $Item2['Tech'] and $Item['Item'] != $Item2['Item']) {
				if ($Item['Item_Mods'] == $Item2['Item_Mods']) {
					$Calc_Items[$Item['Item']]['N_Identical']++;
					$Calc_Items[$Item2['Item']]['Disable'] = TRUE;
				}
			}
		}
	}
}
/*
echo "<pre>";
print_r($Calc_Items);
echo "</pre>";
*/

//
//	Added global item mod bonuses
foreach ($Calc_Item_Mods as $Item_Mod) {
	if ($Item_Mod['Global'] == "X" and $Calc_Items[$Item_Mod['Item_Type']]['Disable'] == FALSE) {

		// ID 1 == Critical hit chance
		if ($Item_Mod['Bonus1_Value'] <> 0 and $Item_Mod['Bonus1_ID'] != 1) {
			$Mods[$Item_Mod['Bonus1_ID']]['Value'] *= (1+($Item_Mod['Bonus1_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']));
			$Mods[$Item_Mod['Bonus1_ID']]['ToT_Calculation'] .= " * " . (1+($Item_Mod['Bonus1_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']));
			$Mods[$Item_Mod['Bonus1_ID']]['Calculation_'.${$Item_Mod['Bonus1_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . (1+($Item_Mod['Bonus1_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'])) . " (1 + (" . $Item_Mod['Bonus1_Value'] . " * " . $Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . "))";
			
		} elseif($Item_Mod['Bonus1_Value'] <> 0 and $Item_Mod['Bonus1_ID'] == 1) {
			$Mods[$Item_Mod['Bonus1_ID']]['Value'] += $Item_Mod['Bonus1_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'];
			$Mods[$Item_Mod['Bonus1_ID']]['ToT_Calculation'] .= " + " . $Item_Mod['Bonus1_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'];
			$Mods[$Item_Mod['Bonus1_ID']]['Calculation_'.${$Item_Mod['Bonus1_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . $Item_Mod['Bonus1_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . "(" . $Item_Mod['Bonus1_Value'] . " * " . $Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . ")";
		}

		if ($Item_Mod['Bonus2_Value'] <> 0 and $Item_Mod['Bonus2_ID'] != 1) {
			$Mods[$Item_Mod['Bonus2_ID']]['Value'] *= (1+($Item_Mod['Bonus2_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']));
			$Mods[$Item_Mod['Bonus2_ID']]['ToT_Calculation'] .= " * " . (1+($Item_Mod['Bonus2_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']));
			$Mods[$Item_Mod['Bonus2_ID']]['Calculation_'.${$Item_Mod['Bonus2_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . (1+($Item_Mod['Bonus2_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'])) . " (1 + (" . $Item_Mod['Bonus2_Value'] . " * " . $Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . "))";
			
		} elseif($Item_Mod['Bonus2_Value'] <> 0 and $Item_Mod['Bonus2_ID'] == 1) {
			$Mods[$Item_Mod['Bonus2_ID']]['Value'] += $Item_Mod['Bonus2_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'];
			$Mods[$Item_Mod['Bonus2_ID']]['ToT_Calculation'] .= " + " . $Item_Mod['Bonus2_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'];
			$Mods[$Item_Mod['Bonus2_ID']]['Calculation_'.${$Item_Mod['Bonus2_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . ($Item_Mod['Bonus2_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']) . " (" . $Item_Mod['Bonus2_Value'] . " * " . $Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . ")";
		}

		if ($Item_Mod['Bonus3_Value'] <> 0 and $Item_Mod['Bonus3_ID'] != 1) {
			$Mods[$Item_Mod['Bonus3_ID']]['Value'] *= (1+($Item_Mod['Bonus3_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']));
			$Mods[$Item_Mod['Bonus3_ID']]['ToT_Calculation'] .= " * " . (1+($Item_Mod['Bonus3_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']));
			$Mods[$Item_Mod['Bonus3_ID']]['Calculation_'.${$Item_Mod['Bonus3_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . (1+($Item_Mod['Bonus3_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'])) . " (1 + (" . $Item_Mod['Bonus3_Value'] . " * " . $Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . "))";
			
		} elseif($Item_Mod['Bonus3_Value'] <> 0 and $Item_Mod['Bonus3_ID'] == 1) {
			$Mods[$Item_Mod['Bonus3_ID']]['Value'] += $Item_Mod['Bonus3_Value'];
			$Mods[$Item_Mod['Bonus3_ID']]['ToT_Calculation'] .= " + " . $Item_Mod['Bonus3_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical'];
			$Mods[$Item_Mod['Bonus3_ID']]['Calculation_'.${$Item_Mod['Bonus3_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . ($Item_Mod['Bonus3_Value']*$Calc_Items[$Item_Mod['Item_Type']]['N_Identical']) . " (" . $Item_Mod['Bonus3_Value'] . " * " . $Calc_Items[$Item_Mod['Item_Type']]['N_Identical'] . ")";
		}

		${$Item_Mod['Bonus1_ID']."r"}++;
		${$Item_Mod['Bonus2_ID']."r"}++;
		${$Item_Mod['Bonus3_ID']."r"}++;
	} elseif ($Item_Mod['Name'] == "Shielded" and $Item_Mod['Bonus2_ID'] == 13) {
		$Mods[$Item_Mod['Bonus2_ID']]['Value'] *= (1+$Item_Mod['Bonus2_Value']);
		$Mods[$Item_Mod['Bonus2_ID']]['ToT_Calculation'] .= " * " . (1+$Item_Mod['Bonus2_Value']);
		$Mods[$Item_Mod['Bonus2_ID']]['Calculation_'.${$Item_Mod['Bonus2_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . (1+$Item_Mod['Bonus2_Value']) . " (1 + " . $Item_Mod['Bonus2_Value'] . ")";
	}
}


//
//	Temporary Bonuses
//

for ($r=1; $r<=5; $r++) {
	$Calculated_Temp_Bonus[$r]['Name'] = $Mods[$Build_Items['Base_Aura_Generator']['Field_ID_' . $r]]['Name'];
	$Calculated_Temp_Bonus[$r]['Value'] = $Build_Items['Base_Aura_Generator']['Field_Value_' . $r];
	$Calculated_Temp_Bonus[$r]['ID'] = $Build_Items['Base_Aura_Generator']['Field_ID_' . $r];
	$Calculated_Temp_Bonus[$r]['Self'] = $Build_Items['Base_Aura_Generator']['Field_Self_' . $r];

	if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
		if ($Calculated_Temp_Bonus[$r]['Value'] < 0) {
			$Calculated_Temp_Bonus[$r]['Value'] = ($Calculated_Temp_Bonus[$r]['Value']/(1+$Calculated_Temp_Bonus[$r]['Value']));
			$Calculated_Temp_Bonus[$r]['Value'] *= $Mods[124]['Value'];
			$Calculated_Temp_Bonus[$r]['Value'] = $Calculated_Temp_Bonus[$r]['Value']/(1-$Calculated_Temp_Bonus[$r]['Value']);
		} else {
			$Calculated_Temp_Bonus[$r]['Value'] *= $Mods[124]['Value'];
		}
	} else {
		$Calculated_Temp_Bonus[$r]['Value'] *= $Mods[124]['Value'];
	}
	
	if ($Calculated_Temp_Bonus[$r]['Self'] == "Yes" and $Include_Temp_Bonus == TRUE) {
		if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Value'] *= (1 + ($Calculated_Temp_Bonus[$r]['Value']));	
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['ToT_Calculation'] .= " * " . (1 + ($Build_Items['Base_Aura_Generator']['Field_Value_' . $r] * ($Mods[124]['Value'])));
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Calculation_'.${$Calculated_Temp_Bonus[$r]['ID']."r"}] = "[Aura] " . $Calculated_Temp_Bonus[$r]['Name'] . " = " . (1+$Build_Items['Base_Aura_Generator']['Field_Value_' . $r] * ($Mods[124]['Value'])) . " (1 + " . $Build_Items['Base_Aura_Generator']['Field_Value_' . $r] . " * (" . $Mods[124]['Value'] . "))";
		} else {
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Value'] += $Calculated_Temp_Bonus[$r]['Value'];
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['ToT_Calculation'] .= " + " . ($Build_Items['Base_Aura_Generator']['Field_Value_' . $r] * ($Mods[124]['Value']));
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Calculation_'.${$Calculated_Temp_Bonus[$r]['ID']."r"}] = "[Aura] " . $Calculated_Temp_Bonus[$r]['Name'] . " = " . ($Build_Items['Base_Aura_Generator']['Field_Value_' . $r] * ($Mods[124]['Value'])) . " (" . $Build_Items['Base_Aura_Generator']['Field_Value_' . $r] . " * " . $Mods[124]['Value'] . ")";
		}
	}

	if ($Build_Items['Base_Aura_Generator']['Field_Allies_' . $r] == "Yes" and $Build_Items['Base_Aura_Generator']['Field_Enemies_' . $r] == "Yes") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "All";	
	} else if ($Build_Items['Base_Aura_Generator']['Field_Allies_' . $r] == "Yes" and $Build_Items['Base_Aura_Generator']['Field_Enemies_' . $r] == "No") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "Allies";	
	} else if ($Build_Items['Base_Aura_Generator']['Field_Allies_' . $r] == "No" and $Build_Items['Base_Aura_Generator']['Field_Enemies_' . $r] == "Yes") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "Enemies";	
	} else if ($Build_Items['Base_Aura_Generator']['Field_Allies_' . $r] == "No" and $Build_Items['Base_Aura_Generator']['Field_Enemies_' . $r] == "No") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "Self";	
	} else {
		$Calculated_Temp_Bonus[$r]['Targets'] = "None";	
	}
}

// Add vulnerability to resistance
if ($Mods[264]['Value'] != 0) {
	$Mods[24]['Value'] = $Mods[24]['Value'] / $Mods[264]['Value'];
	$Mods[24]['ToT_Calculation'] = "" . $Mods[24]['ToT_Calculation'] . " / " . $Mods[264]['Value'] . "";
	${"24r"}++;
	${"24r"}++;
	$Mods[24]['Calculation_'.${"24r"}] = "Vulnerability multiplier: " . $Mods[264]['Value'] . "<br/>";
	${"23r"}++;
	$Mods[24]['Calculation_'.${"24r"}] = "Vulnerability calculation: " . $Mods[264]['ToT_Calculation'] . "";
	${"24r"}++;
	for ($r=0; $r <= ${"264r"}; $r++) {
		$Mods[24]['Calculation_'.${"24r"}] = $Mods[264]['Calculation_'.$r];
		${"24r"}++;
	}
}
unset($Mods[264]);

// Add recoil to RoF - Rate of Fire
if ($Mods[151]['Value'] != 0) {
	$Mods[23]['Value'] = $Mods[23]['Value'] / $Mods[151]['Value'];
	$Mods[23]['ToT_Calculation'] = "" . $Mods[23]['ToT_Calculation'] . " / " . $Mods[151]['Value'] . "";
	${"23r"}++;
	${"23r"}++;
	$Mods[23]['Calculation_'.${"23r"}] = "Recoil multiplier: " . $Mods[151]['Value'] . "<br/>";
	${"23r"}++;
	$Mods[23]['Calculation_'.${"23r"}] = "Recoil calculation: " . $Mods[151]['ToT_Calculation'] . "";
	${"23r"}++;
	for ($r=0; $r <= ${"151r"}; $r++) {
		$Mods[23]['Calculation_'.${"23r"}] = $Mods[151]['Calculation_'.$r];
		${"23r"}++;
	}
}
unset($Mods[151]);

// Add negative speed to Speed
if ($Mods[262]['Value'] != 0) {
	$Mods[27]['Value'] = $Mods[27]['Value'] * (((1 / $Mods[262]['Value'])-1)+1);
	$Mods[27]['ToT_Calculation'] = "" . $Mods[27]['ToT_Calculation'] . " * " . (((1 / $Mods[262]['Value'])-1)+1) . "";
	${"27r"}++;
	${"27r"}++;
	$Mods[27]['Calculation_'.${"27r"}] = "Negative speed multiplier: " . $Mods[262]['Value'] . "<br/>";
	${"27r"}++;
	$Mods[27]['Calculation_'.${"27r"}] = "Negative speed calculation: " . $Mods[262]['ToT_Calculation'] . "";
	${"27r"}++;
	for ($r=0; $r <= ${"262r"}; $r++) {
		$Mods[27]['Calculation_'.${"27r"}] = $Mods[262]['Calculation_'.$r];
		${"27r"}++;
	}
}
unset($Mods[262]);

// Add Firing Energy to Electric Tempering
if ($Mods[123]['Value'] != 0) {
	$Mods[8]['Value'] = $Mods[8]['Value'] * (((1 / $Mods[123]['Value'])-1)+1);
	$Mods[8]['ToT_Calculation'] = "" . $Mods[8]['ToT_Calculation'] . " * " . (((1 / $Mods[123]['Value'])-1)+1) . "";
	${"8r"}++;
	${"8r"}++;
	$Mods[8]['Calculation_'.${"8r"}] = "Firing Energy multiplier: " . round((((1 / $Mods[123]['Value'])-1)+1),2) . "";
	${"8r"}++;
	$Mods[8]['Calculation_'.${"8r"}] = "Firing Energy calculation: (((1 / " . $Mods[123]['ToT_Calculation'] . ") -1) +1)";
	${"8r"}++;
	for ($r=0; $r <= ${"123r"}; $r++) {
		$Mods[8]['Calculation_'.${"8r"}] = $Mods[123]['Calculation_'.$r];
		${"8r"}++;
	}
}
unset($Mods[123]);

// Should remove the extra for Zen Skills
foreach ($Mods as $Mod_ID => $Mod_Name) {
	if (strpos($Mods[$Mod_ID]['Name'],"Bonus") !== FALSE or strpos($Mods[$Mod_ID]['Name'],"Projectil") !== FALSE) {
		$Mods[$Mod_ID]['Value'] = ($Mods[$Mod_ID]['Value']>0 ? $Mods[$Mod_ID]['Value']-1 : $Mods[$Mod_ID]['Value']);
	}
}