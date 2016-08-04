<?php
$Query = "SELECT * FROM Mods ORDER BY Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Mods[$row['Excel_Mod_ID']]['ID'] = $row['Excel_Mod_ID'];
	$Mods[$row['Excel_Mod_ID']]['Name'] = $row['Name'];
	$Mods[$row['Excel_Mod_ID']]['Value'] = 0;
	$Mods[$row['Excel_Mod_ID']]['ToT_Calculation'] = "((";
	${$row['Excel_Mod_ID']."r"} = 1;
}



$Calc_Item_Mods = array();
$Query = "SELECT * FROM User_Build_Item_Mods LEFT JOIN Item_Mods ON User_Build_Item_Mods.Item_Mod_ID = Item_Mods.Item_Mods_ID LEFT JOIN User_Build_Items ON User_Build_Items.User_Build_ID = User_Build_Item_Mods.Build_ID and User_Build_Items.Item_Type = User_Build_Item_Mods.Item_Type WHERE User_Build_Item_Mods.Build_ID = " . $Build_ID;
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['ID'] = $row['Build_Item_Mod_ID'];
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Name'] = $row['Name'];
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Item_Type'] = $row['Item_Type'];
	$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Item_ID'] = $row['Item_ID'];

	if (substr($row['Item_Type'],0,6) == "Weapon") {
		$Weapon_Mods[$row['Item_Type']][] = $row['Item_Mod_ID'];
		$row['Item_Type'] = "Weapon";
	}

	if ($row['Mod1_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus1_ID'] = $row['Mod1_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus1_Value'] = $row['Mod1_Initial'] + $row['Mod1_Tech']*${$row['Item_Type']."_Information"}[$row['Item_ID']]['Tech'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus1_Calc'] = $row['Mod1_Initial'] . " + " . $row['Mod1_Tech'] . " * " . ${$row['Item_Type']."_Information"}[$row['Item_ID']]['Tech'];
	}
	if ($row['Mod2_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus2_ID'] = $row['Mod2_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus2_Value'] = $row['Mod2_Initial'] + $row['Mod2_Tech'] * ${$row['Item_Type']."_Information"}[$row['Item_ID']]['Tech'];
	}
	if ($row['Mod3_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus3_ID'] = $row['Mod3_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus3_Value'] = $row['Mod3_Initial'] + $row['Mod3_Tech'] * ${$row['Item_Type']."_Information"}[$row['Item_ID']]['Tech'];
	}
	if ($row['Mod_Flat_ID'] != 0) {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus_Flat_ID'] = $row['Mod_Flat_ID'];
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Bonus_Flat_Value'] = $row['Mod_Flat_Initial'] + ($row['Mod_Flat_Tech']<>0 ? $row['Mod_Flat_Tech'] * ${$row['Item_Type']."_Information"}[$row['Item_ID']]['Tech'] : 0);
	}

	if ($row['Mod_Specific'] == "X") {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Global'] = "";	
	} else {
		$Calc_Item_Mods[$row['Build_Item_Mod_ID']]['Global'] = "X";
	}	
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
		if ($row['Excel_Skill_ID'] >= 47 and $row['Excel_Skill_ID'] <= 82) {
			$row['Skill_Level'] = floor($row['Skill_Level']/2) + 0;
		} else {
			$row['Skill_Level'] = ($row['Skill_Level'] + 0);
		}
			
		if (($row['Excel_Skill_ID'] >= 47 and $row['Excel_Skill_ID'] <= 82) or strpos($Skill_Mods[$Mod_ID]['Name'], "Combat Slave") !== FALSE) {
			if (strpos($Skill_Mods[$Mod_ID]['Name'], $Build_Items['Ship']['Ship_Type']) !== FALSE) {
				$Current_Mod_ID = $Mod_ID;
				$Mod_ID = array_search_multi($Mods, "Name", substr($Skill_Mods[$Mod_ID]['Name'], strlen($Build_Items['Ship']['Ship_Type']) + 1 + strpos($Skill_Mods[$Mod_ID]['Name'], $Build_Items['Ship']['Ship_Type'])));
				if ($Skill_Mods[$Mod_ID]['ToT_Calculation'] == "") {
					$Skill_Mods[$Mod_ID]['Value'] = 1;
					$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= "((1";
				}
			}
			if (strpos($Skill_Mods[$Mod_ID]['Name'], "Combat Slave") !== FALSE and $Mod_ID != 48) {
				$Current_Mod_ID = $Mod_ID;
				$Mod_ID = array_search_multi($Mods, "Name", substr($Skill_Mods[$Mod_ID]['Name'], strlen("Combat Slave") + 1 + strpos($Skill_Mods[$Mod_ID]['Name'], "Combat Slave")));
				if ($Skill_Mods[$Mod_ID]['ToT_Calculation'] == "") {
					$Skill_Mods[$Mod_ID]['Value'] = 1;
					$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= "((1";
				}
			}
			
			//echo $Current_Mod_ID . " => " . $Skill_Mods[$Mod_ID]['Name'] . " (" . $Mod_ID . "):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row['Value'] . " * " . $row['Skill_Level'] . "<br/>";
			
			if ($row['Excel_Skill_ID'] == 51) {$Berserker_Class = $row['Skill_Level'];}
			
			if ($Mod_ID == 40 or $Mod_ID == 41 or $Mod_ID == 1) {
				$Skill_Mods[$Mod_ID]['Value'] += ($row['Value'] * $row['Skill_Level']);
				$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " + " . ($row['Value'] * $row['Skill_Level']);
				
				$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] " . str_replace("_"," ",$row['Name']) . " = " . ($row['Value'] * $row['Skill_Level']) . " (" . $row['Value'] . " * " . $row['Skill_Level'] . ")";
			} else {
				$Skill_Mods[$Mod_ID]['Value'] *= (1+ ($row['Value'] * $row['Skill_Level']));
				$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " * " . (1 + ($row['Value'] * $row['Skill_Level']));
				
				$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] " . str_replace("_"," ",$row['Name']) . " = " . (1 + ($row['Value'] * $row['Skill_Level'])) . " (1 + (" . $row['Value'] . " * " . $row['Skill_Level'] . "))";
			}
			$r++;
			${$Mod_ID."r"}++;
			if ($Current_Mod_ID != 0) {
				$Mod_ID = $Current_Mod_ID;
			}
		}
	}
}

foreach ($Mods as $Mod_ID => $Mod_Name) {
	if ($Mod_ID == 17 and $Berserker_Class > 0) {
		$Skill_Mods[$Mod_ID]['Value'] += 0.5;
		$Skill_Mods[$Mod_ID]['ToT_Calculation'] .= " + 0.5";
		$Mods[$Mod_ID]['Calculation_' . ${$Mod_ID."r"}] = "[Skill] Berserker Class Lvl 1 = +0.5";
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
	$Query = "SELECT * FROM Augmenter_Mods RIGHT JOIN User_Build_Items ON User_Build_Items.Item_ID = Augmenter_Mods.Augmenter_ID LEFT JOIN Augmenters ON Augmenters.Augmenter_ID = Augmenter_Mods.Augmenter_ID WHERE Augmenter_Mods.Excel_Mod_ID = " . $Mod_ID ." AND User_Build_Items.Item_Type LIKE 'Augmenter%' AND User_Build_Items.User_Build_ID = " . $Build_ID . " AND Value <> 0";
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

			$Mods[$Mod_ID]['ToT_Calculation'] .= ($r==0 ? "" : " + ") . ($row['Value']>0 ? $row['Value'] : "(" . $row['Value'] . "/(1+" . $row['Value'] . ")" );
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
		"Controlbot" => "Controlbots",
		"Exterminator" => "Exterminators"
);

//
//	Add all bonuses from items to the overall bonus
foreach ($Item_Names as $Item => $Items) {
	foreach ($Mods as $Mod_ID => $Mod_Name) {
		$r=0;
		// LEFT JOIN Mods ON Skill_Mods.Excel_Mod_ID = Mods.Excel_Mod_ID 
		$Query = "SELECT * FROM " . $Item . "_Mods RIGHT JOIN User_Build_Items ON User_Build_Items.Item_ID = " . $Item . "_Mods." . $Item . "_ID LEFT JOIN " . $Items . " ON " . $Items . "." . $Item . "_ID = " . $Item . "_Mods." . $Item . "_ID WHERE " . $Item . "_Mods.Excel_Mod_ID = " . $Mod_ID ." AND User_Build_Items.Item_Type LIKE '" . $Item . "%' AND User_Build_Items.User_Build_ID = " . $Build_ID;
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
			$Mods[$Mod_ID]['ToT_Calculation'] .= ($r==0 ? "" : " + ") . ($row['Value']>0 ? $row['Value'] : ($row['Value']/(1+$row['Value'])));
			$Mods[$Mod_ID]['Calculation_'.${$Mod_ID."r"}] = "[Item Bonus] " . $row['Name'] . " = " . ($row['Value']>0 ? $row['Value'] : ($row['Value']/(1+$row['Value']))) . ($row['Value']>0 ? "" :  " (" . "(" . $row['Value'] . " / " . "(1 + " . $row['Value']. "))" );

			//$Mods[$Mod_ID]['Value'] += $row['Value'];
			//$Mods[$Mod_ID]['ToT_Calculation'] .= ($r==0 ? "" : " + ") . $row['Value'];
			//$Mods[$Mod_ID]['Calculation_'.${$Mod_ID."r"}] = "[Item Bonus] " . $row['Name'] . " = " . $row['Value'];

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
		$Mods[$Mod_ID]['Value'] = ($Mods[$Mod_ID]['Value']/(1-$Mods[$Mod_ID]['Value']));
		$Mods[$Mod_ID]['ToT_Calculation'] = "(" . $Mods[$Mod_ID]['ToT_Calculation'] . " / (1 - " . $Mods[$Mod_ID]['Value'] . "))";
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

//
//	Added global item mod bonuses
foreach ($Calc_Item_Mods as $Item_Mod) {
	if ($Item_Mod['Global'] == "X") {

		// ID 1 == Critical hit chance
		if ($Item_Mod['Bonus1_Value'] <> 0 and $Item_Mod['Bonus1_ID'] != 1) {
			$Mods[$Item_Mod['Bonus1_ID']]['Value'] *= (1+$Item_Mod['Bonus1_Value']);
			$Mods[$Item_Mod['Bonus1_ID']]['ToT_Calculation'] .= " * " . (1+$Item_Mod['Bonus1_Value']);
			$Mods[$Item_Mod['Bonus1_ID']]['Calculation_'.${$Item_Mod['Bonus1_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = (1 + " . $Item_Mod['Bonus1_Value'] . ")";
		} elseif($Item_Mod['Bonus1_Value'] <> 0 and $Item_Mod['Bonus1_ID'] == 1) {
			$Mods[$Item_Mod['Bonus1_ID']]['Value'] += $Item_Mod['Bonus1_Value'];
			$Mods[$Item_Mod['Bonus1_ID']]['ToT_Calculation'] .= " + " . $Item_Mod['Bonus1_Value'];
			$Mods[$Item_Mod['Bonus1_ID']]['Calculation_'.${$Item_Mod['Bonus1_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . $Item_Mod['Bonus1_Value'];
		}

		if ($Item_Mod['Bonus2_Value'] <> 0 and $Item_Mod['Bonus2_ID'] != 1) {
			$Mods[$Item_Mod['Bonus2_ID']]['Value'] *= (1+$Item_Mod['Bonus2_Value']);
			$Mods[$Item_Mod['Bonus2_ID']]['ToT_Calculation'] .= " * " . (1+$Item_Mod['Bonus2_Value']);
			$Mods[$Item_Mod['Bonus2_ID']]['Calculation_'.${$Item_Mod['Bonus2_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = (1 + " . $Item_Mod['Bonus2_Value'] . ")";
		} elseif($Item_Mod['Bonus2_Value'] <> 0 and $Item_Mod['Bonus2_ID'] == 1) {
			$Mods[$Item_Mod['Bonus2_ID']]['Value'] += $Item_Mod['Bonus2_Value'];
			$Mods[$Item_Mod['Bonus2_ID']]['ToT_Calculation'] .= " + " . $Item_Mod['Bonus2_Value'];
			$Mods[$Item_Mod['Bonus2_ID']]['Calculation_'.${$Item_Mod['Bonus2_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . $Item_Mod['Bonus2_Value'];
		}

		if ($Item_Mod['Bonus3_Value'] <> 0 and $Item_Mod['Bonus3_ID'] != 1) {
			$Mods[$Item_Mod['Bonus3_ID']]['Value'] *= (1+$Item_Mod['Bonus3_Value']);
			$Mods[$Item_Mod['Bonus3_ID']]['ToT_Calculation'] .= " * " . (1+$Item_Mod['Bonus3_Value']);
			$Mods[$Item_Mod['Bonus3_ID']]['Calculation_'.${$Item_Mod['Bonus3_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = (1 + " . $Item_Mod['Bonus3_Value'] . ")";
		} elseif($Item_Mod['Bonus3_Value'] <> 0 and $Item_Mod['Bonus3_ID'] == 1) {
			$Mods[$Item_Mod['Bonus3_ID']]['Value'] += $Item_Mod['Bonus3_Value'];
			$Mods[$Item_Mod['Bonus3_ID']]['ToT_Calculation'] .= " + " . $Item_Mod['Bonus3_Value'];
			$Mods[$Item_Mod['Bonus3_ID']]['Calculation_'.${$Item_Mod['Bonus3_ID']."r"}] = "[Item Mod] " . $Item_Mod['Name'] . " (" . $Item_Mod['Item_Type'] . ") = " . $Item_Mod['Bonus3_Value'];
		}

		${$Item_Mod['Bonus1_ID']."r"}++;
		${$Item_Mod['Bonus2_ID']."r"}++;
		${$Item_Mod['Bonus3_ID']."r"}++;
	} else {
		//
		//	Specifics will adjust the item directly
	}
}

// Should remove the extra for Zen Skills
foreach ($Mods as $Mod_ID => $Mod_Name) {
	if (strpos($Mods[$Mod_ID]['Name'],"Bonus") !== FALSE) {
		$Mods[$Mod_ID]['Value'] = ($Mods[$Mod_ID]['Value']>0 ? $Mods[$Mod_ID]['Value']-1 : $Mods[$Mod_ID]['Value']);
	}
}

// Add recoil to RoF - Rate of Fire
if ($Mods[151]['Value'] != 0) {
	$Mods[23]['Value'] = $Mods[23]['Value'] / $Mods[151]['Value'];
	$Mods[23]['ToT_Calculation'] = "" . $Mods[23]['ToT_Calculation'] . " / " . $Mods[151]['Value'] . "";
	${"23r"}++;
	${"23r"}++;
	$Mods[23]['Calculation_'.${"23r"}] = "Recoil multiplier: " . $Mods[151]['Value'] . " (" . $Mods[151]['ToT_Calculation'] . ")";
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
	$Mods[8]['Calculation_'.${"8r"}] = "Firing Energy multiplier: " . $Mods[123]['Value'] . "<br/>";
	${"8r"}++;
	$Mods[8]['Calculation_'.${"8r"}] = "Firing Energy calculation: " . $Mods[123]['ToT_Calculation'] . "";
	${"8r"}++;
	for ($r=0; $r <= ${"123r"}; $r++) {
		$Mods[8]['Calculation_'.${"8r"}] = $Mods[123]['Calculation_'.$r];
		${"8r"}++;
	}
}
unset($Mods[123]);

//
//	Temporary Bonuses
//

for ($r=1; $r<=5; $r++) {
	$Calculated_Temp_Bonus[$r]['Name'] = $Mods[$Aura_Generator_Information[$Build_Aura_Generator]['Field_ID_' . $r]]['Name'];
	$Calculated_Temp_Bonus[$r]['Value'] = $Aura_Generator_Information[$Build_Aura_Generator]['Field_Value_' . $r] * ($Mods[124]['Value']);

	$Calculated_Temp_Bonus[$r]['ID'] = $Aura_Generator_Information[$Build_Aura_Generator]['Field_ID_' . $r];

	$Calculated_Temp_Bonus[$r]['Self'] = $Aura_Generator_Information[$Build_Aura_Generator]['Field_Self_' . $r];

	if ($Calculated_Temp_Bonus[$r]['Self'] == "Yes") {
		//echo $Calculated_Temp_Bonus[$r]['Name'] . ": " . $Calculated_Temp_Bonus[$r]['Value'] . "<br/>";
		if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Value'] *= (1 + ($Aura_Generator_Information[$Build_Aura_Generator]['Field_Value_' . $r] * ($Mods[124]['Value'])));
			//$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Calculation_'.${$Calculated_Temp_Bonus[$r]['ID'] . "r"}] = $Calculated_Temp_Bonus[$r]['Name'] . ": " . $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Value'] . " (1 + (" . $Calculated_Temp_Bonus[$r]['Value'] . " * " . $Mods[124]['Value'] . "))";
		} else {
			$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Value'] += $Aura_Generator_Information[$Build_Aura_Generator]['Field_Value_' . $r] * $Mods[124]['Value'];
			//$Mods[$Calculated_Temp_Bonus[$r]['ID']]['Calculation_'.${$Calculated_Temp_Bonus[$r]['ID'] . "r"}] = $Calculated_Temp_Bonus[$r]['Name'] . ": " . $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Value'];
		}
	}


	if ($Aura_Generator_Information[$Build_Aura_Generator]['Field_Allies_' . $r] == "Yes" and $Aura_Generator_Information[$Build_Aura_Generator]['Field_Enemies_' . $r] == "Yes") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "All";	
	} else if ($Aura_Generator_Information[$Build_Aura_Generator]['Field_Allies_' . $r] == "Yes" and $Aura_Generator_Information[$Build_Aura_Generator]['Field_Enemies_' . $r] == "No") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "Allies";	
	} else if ($Aura_Generator_Information[$Build_Aura_Generator]['Field_Allies_' . $r] == "No" and $Aura_Generator_Information[$Build_Aura_Generator]['Field_Enemies_' . $r] == "Yes") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "Enemies";	
	} else if ($Aura_Generator_Information[$Build_Aura_Generator]['Field_Allies_' . $r] == "No" and $Aura_Generator_Information[$Build_Aura_Generator]['Field_Enemies_' . $r] == "No") {
		$Calculated_Temp_Bonus[$r]['Targets'] = "Self";	
	} else {
		$Calculated_Temp_Bonus[$r]['Targets'] = "None";	
	}
}
