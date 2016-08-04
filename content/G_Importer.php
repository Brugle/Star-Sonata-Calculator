<script type="text/javascript">
    $(function() {
        // Jquery UI på skjema knappene
        $("input[type=submit], input[type=button]").button();
    });
</script>
<?php 

$GID['Modifications'] = 251496243;
$GID['Skills'] = 1208208884;
$GID['Overloaders'] = 1775319891;
$GID['Augmenters'] = 1158805401;
$GID['Shields'] = 744230800;
$GID['Ships'] = 2117238253;
$GID['Energies'] = 1141471262;
$GID['Weapons'] = 1333455837;
$GID['Solar_Panels'] = 1217050268;
$GID['Capacitors'] = 1535628674;
$GID['Diffusers'] = 881774419;
$GID['Hull_Expanders'] = 4075512;
$GID['Radars'] = 238480722;
$GID['Scoops'] = 1479716950;
$GID['Cloaks'] = 2012909520;
$GID['Item_Modifications'] = 197861099;
$GID['Shield_Chargers'] = 332468550;
$GID['Engines'] = 474082480;
$GID['Tractors'] = 541918024;
$GID['Controlbots'] = 2143372701;
$GID['Aura_Generators'] = 1760127730;
$GID['Exterminators'] = 103923532;
$GID['Homing_Beacons'] = 501287872;
$GID['Neurotweaks'] = 1955690791;
$GID['NT_Effects'] = 1052375952;

$GID['Bases'] = 816634533;
$GID['Base_Weapons'] = 865014419;
$GID['Base_Shields'] = 1322666401;
$GID['Base_Energies'] = 1914920427;
$GID['Base_Radars'] = 851064713;
$GID['Base_Shield_Chargers'] = 1196610656;
$GID['Base_Capacitors'] = 1114098460;
$GID['Base_Solar_Panels'] = 1763200637;
$GID['Base_Overloaders'] = 1251261452;
$GID['Base_Aura_Generators'] = 1326620911;
$GID['Base_Diffusers'] = 1136172443;
$GID['Base_Augmenters'] = 1327647102;
$GID['Base_Hull_Expanders'] = 1053577046;
$GID['Base_Extractors'] = 297766484;
$GID['Base_Overchargers'] = 550209243;

?>
<p>
	The data is imported from:<br/>
	<a href="https://docs.google.com/spreadsheets/d/13MB1ymbuKxgRL4fEoo5-wClpAiYNmqFCYsUXvBrpnR4/edit#gid=4075512">Google Sheet</a>
	<br/>
	<br/>
</p>
<table style="float:left;">
	<tr>
        <td>
            <form action="?module=skills&amp;content=G_Importer" method="post" id="formID">
				<h2>
					Import
				</h2>
                <fieldset>
                    <table>
                        <tr>
						<?php $r=0; foreach ($GID as $Item => $ID) { if ($Item=="NT_Effects"){}else{ ?>
							<td style="vertical-align:bottom;">
								<input class="submit" style="width:150px;margin:3px; bottom:2px; padding:4.5px 15px;" id="<?php echo $Item; ?>" type="submit" name="<?php echo $Item; ?>" value="<?php if ($Item == "Modifications") { echo "Effects";} else{echo str_replace("_", " ", $Item);} ?>" />
							</td>
						<?php $r++; if ($r==5) {echo "</tr><tr>"; $r=0;}  ?>
						<?php } } ?>
						</tr>
					</table>
				</fieldset>
			</form>
		</td>
	</tr>
</table>
						
<?php
//	Google Sheets Variables
$spreadsheet_url="https://docs.google.com/spreadsheets/d/13MB1ymbuKxgRL4fEoo5-wClpAiYNmqFCYsUXvBrpnR4/pub?output=csv&gid=";


if(!ini_set('default_socket_timeout',    15)) {
	echo "<!-- unable to change socket timeout -->";
}

if (isset($_POST['Modifications'])) {
	//
	//	Modifications
	//
	// Fetch data from Excel - Modifications
	if (isset($GID['Modifications'])) {
		if (($handle = fopen($spreadsheet_url . $GID['Modifications'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");

		$r=0;
		foreach ($spreadsheet_data as $row) {
			if ($row[0] != "MOD_ID" and $row[1] != "Name") {
				//	ID - Name - ID
				$Excel_ID = mysql_real_escape_string($row[0],$conn);
				$Mod_Name = "'" . mysql_real_escape_string($row[1],$conn) . "'";
				$Mod_Img = "'" . mysql_real_escape_string($row[2],$conn) . "'";

				$Query = "INSERT INTO Mods (Excel_Mod_ID, Name, Img) VALUES ($Excel_ID, $Mod_Name, $Mod_Img);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");
				$r+=1;
			}
		}

		echo "Imported $r modifications<br/>";
	}
}
if (isset($_POST['Skills'])) {
	//
	//	Skills
	//
	// Fetch data from Excel
	if (isset($GID['Skills'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Skills'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Skills";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Skill_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Skill_ID = mysql_real_escape_string($row[0], $conn);
				$Skill_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				if (mysql_real_escape_string($row[2], $conn) == "") {
					$Skill_Family_1 = "NULL";
				} else {
					$Skill_Family_1 = mysql_real_escape_string($row[2], $conn);	
				}
				if (mysql_real_escape_string($row[3], $conn) == "") {
					$Skill_Family_2 = "NULL";
				} else {
					$Skill_Family_2 = mysql_real_escape_string($row[3], $conn);	
				}
				$Query = "INSERT INTO Skills (Excel_Skill_ID, Name, Family_1, Family_2) VALUES ($Skill_ID, $Skill_Name, $Skill_Family_1, $Skill_Family_2);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>1 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Skill_Mods (Skill_ID, Excel_Mod_ID, Value) VALUES ($Skill_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r skills and $m skill modifications<br/>";
	}
}
if (isset($_POST['Overloaders'])) {
	//
	//	Overloaders
	//
	// Fetch data from Excel
	if (isset($GID['Overloaders'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Overloaders'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Overloaders";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Overloader_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Overloader_ID = mysql_real_escape_string($row[0], $conn);
				$Overloader_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Overloader_Weight = mysql_real_escape_string($row[2], $conn);
				$Overloader_Tech = mysql_real_escape_string($row[3], $conn);
				$Overloader_Size = mysql_real_escape_string($row[4], $conn);
				$Overloader_Fail = mysql_real_escape_string($row[5], $conn);
				$Overloader_Visibility = mysql_real_escape_string($row[6], $conn);
				$Overloader_Electricity = mysql_real_escape_string($row[7], $conn);
				$Overloader_Req_Skill_ID = mysql_real_escape_string($row[8], $conn)+0;
				$Overloader_Req_Skill_Level = mysql_real_escape_string($row[10], $conn)+0;
				$Overloader_Req_Ship = "'" . mysql_real_escape_string($row[11], $conn) . "'";
				
				$Query = "INSERT INTO Overloaders (Overloader_ID, Name, Weight, Tech, Size, Fail, Visibility, Electricity, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Overloader_ID, $Overloader_Name, $Overloader_Weight,$Overloader_Tech, $Overloader_Size, $Overloader_Fail, $Overloader_Visibility, $Overloader_Electricity, $Overloader_Req_Skill_ID, $Overloader_Req_Skill_Level, $Overloader_Req_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>11 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Overloader_Mods (Overloader_ID, Excel_Mod_ID, Value) VALUES ($Overloader_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r overloaders and $m overloader modifications<br/>";
	}
}
if (isset($_POST['Augmenters'])) {
	//
	//	Augmenters
	//
	// Fetch data from Excel
	if (isset($GID['Augmenters'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Augmenters'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Augmenters";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Augmenter_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Augmenter_ID = mysql_real_escape_string($row[0], $conn);
				$Augmenter_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Augmenter_Weight = "'" . mysql_real_escape_string($row[2], $conn) . "'";
				$Augmenter_Tech = "'" . mysql_real_escape_string($row[3], $conn) . "'";
				$Augmenter_Size = "'" . mysql_real_escape_string($row[4], $conn) . "'";
				$Augmenter_Req_Skill_ID = mysql_real_escape_string($row[5], $conn)+0;
				$Augmenter_Req_Skill_Level = mysql_real_escape_string($row[7], $conn)+0;
				$Augmenter_Req_Ship = "'" . mysql_real_escape_string($row[8], $conn) . "'";
				$Query = "INSERT INTO Augmenters (Augmenter_ID, Name, Weight, Tech, Size, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Augmenter_ID, $Augmenter_Name, $Augmenter_Weight, $Augmenter_Tech, $Augmenter_Size, $Augmenter_Req_Skill_ID, $Augmenter_Req_Skill_Level, $Augmenter_Req_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error());

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>8 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Augmenter_Mods (Augmenter_ID, Excel_Mod_ID, Value) VALUES ($Augmenter_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error());
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r augmenters and $m augmenter modifications<br/>";
	}
}
if (isset($_POST['Shields'])) {
	//
	//	Shields
	//
	// Fetch data from Excel
	if (isset($GID['Shields'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Shields'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Shields";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Shield_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Shield_ID = mysql_real_escape_string($row[0], $conn);
				$Shield_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Shield_Tech = mysql_real_escape_string($row[2], $conn);
				$Shield_Size = mysql_real_escape_string($row[3], $conn);
				$Shield_Shield_Bank = mysql_real_escape_string($row[4], $conn);
				$Shield_Shield_Regeneration = mysql_real_escape_string($row[5], $conn);
				$Shield_Regeneration_Electricity = mysql_real_escape_string($row[6], $conn);
				$Shield_Electricity = mysql_real_escape_string($row[7], $conn);
				$Shield_Visbility = mysql_real_escape_string($row[8], $conn);
				$Shield_Weight = mysql_real_escape_string($row[9], $conn);
				$Shield_Req_Skill_ID = mysql_real_escape_string($row[10], $conn)+0;
				$Shield_Req_Skill_Level = mysql_real_escape_string($row[12], $conn)+0;
				$Shield_Req_Ship = "'" . mysql_real_escape_string($row[13], $conn) . "'";
				$Query = "INSERT INTO Shields (Shield_ID, Name, Tech, Size, Bank, Regeneration, Reg_Elec, Electricity, Visibility, Weight, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Shield_ID, $Shield_Name, $Shield_Tech, $Shield_Size, $Shield_Shield_Bank, $Shield_Shield_Regeneration, $Shield_Regeneration_Electricity, $Shield_Electricity, $Shield_Visbility, $Shield_Weight, $Shield_Req_Skill_ID, $Shield_Req_Skill_Level, $Shield_Req_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>13 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Shield_Mods (Shield_ID, Excel_Mod_ID, Value) VALUES ($Shield_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r shields and $m shield modifications<br/>";
	}
}
if (isset($_POST['Ships'])) {
	//
	//	Ships
	//
	// Fetch data from Excel
	if (isset($GID['Ships'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Ships'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Ships";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Ship_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Ship_ID = mysql_real_escape_string($row[0], $conn);
				$Ship_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Ship_Tech = mysql_real_escape_string($row[2], $conn);
				$Ship_Hull_Space = mysql_real_escape_string($row[3], $conn);
				$Ship_Max_Speed = mysql_real_escape_string($row[4], $conn);
				$Ship_Augmenter_Slots = mysql_real_escape_string($row[5], $conn);
				$Ship_Weapon_Slots = mysql_real_escape_string($row[6], $conn);
				$Ship_Size = mysql_real_escape_string($row[7], $conn);
				$Ship_Weight = mysql_real_escape_string($row[8], $conn);
				$Ship_Visibility = mysql_real_escape_string($row[9], $conn);
				$Ship_Reflectivity = mysql_real_escape_string($row[10], $conn);
				$Ship_Inbuilt_Electricity = mysql_real_escape_string($row[11], $conn);
				$Ship_Laser = mysql_real_escape_string($row[12], $conn);
				$Ship_Energy = mysql_real_escape_string($row[13], $conn);
				$Ship_Heat = mysql_real_escape_string($row[14], $conn);
				$Ship_Physical = mysql_real_escape_string($row[15], $conn);
				$Ship_Radiation = mysql_real_escape_string($row[16], $conn);
				$Ship_Surgical = mysql_real_escape_string($row[17], $conn);
				$Ship_Mining = mysql_real_escape_string($row[18], $conn);
				$Ship_Transference = mysql_real_escape_string($row[19], $conn);
				$Ship_Type = "'" . mysql_real_escape_string($row[20], $conn) . "'";
				$Ship_Required_Skill_ID = mysql_real_escape_string($row[21], $conn)+0;
				$Ship_Required_Skill_Level = mysql_real_escape_string($row[23], $conn)+0;
				
				$Query = "INSERT INTO Ships (Ship_ID, Name, Tech, Hull_Space, Max_Speed, Aug_Slots, Weapon_Slots, Size, Weight, Visibility, Reflectivity, Inbuilt_Electricity, Resistance_Laser, Resistance_Energy, Resistance_Heat, Resistance_Physical, Resistance_Radiation, Resistance_Surgical, Resistance_Mining, Resistance_Transference, Ship_Type, Require_Skill_ID, Require_Skill_Level) VALUES ($Ship_ID, $Ship_Name, $Ship_Tech, $Ship_Hull_Space, $Ship_Max_Speed, $Ship_Augmenter_Slots, $Ship_Weapon_Slots, $Ship_Size, $Ship_Weight, $Ship_Visibility, $Ship_Reflectivity, $Ship_Inbuilt_Electricity, $Ship_Laser, $Ship_Energy, $Ship_Heat, $Ship_Physical, $Ship_Radiation, $Ship_Surgical, $Ship_Mining, $Ship_Transference, $Ship_Type, $Ship_Required_Skill_ID, $Ship_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>23 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Ship_Mods (Ship_ID, Excel_Mod_ID, Value) VALUES ($Ship_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r ships and $m ship modifications<br/>";
	}
}
if (isset($_POST['Energies'])) {
	//
	//	Energies
	//
	// Fetch data from Excel
	if (isset($GID['Energies'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Energies'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Energies";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Energy_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Energy_ID = mysql_real_escape_string($row[0], $conn);
				$Energy_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Energy_Tech = mysql_real_escape_string($row[2], $conn);
				$Energy_Size = mysql_real_escape_string($row[3], $conn);
				$Energy_Bank_Size = mysql_real_escape_string($row[4], $conn);
				$Energy_Regeneration = mysql_real_escape_string($row[5], $conn);
				$Energy_Visibility = mysql_real_escape_string($row[6], $conn);
				$Energy_Weight = mysql_real_escape_string($row[7], $conn);
				if (mysql_real_escape_string($row[8], $conn) == "") {
					$Energy_Fuel_Amount = "NULL";
				} else {
					$Energy_Fuel_Amount = mysql_real_escape_string($row[8], $conn);
				}
				$Energy_Fuel_Type = "'" . mysql_real_escape_string($row[9], $conn) . "'";
				if (mysql_real_escape_string($row[10], $conn) == "") {
					$Energy_Fuel_Tick = "NULL";
				} else {
					$Energy_Fuel_Tick = mysql_real_escape_string($row[10], $conn);
				}
				$Energy_Required_Skill_ID = mysql_real_escape_string($row[11], $conn)+0;
				$Energy_Required_Skill_Level = mysql_real_escape_string($row[13], $conn)+0;
				$Energy_Required_Ship = "'" . mysql_real_escape_string($row[14], $conn) . "'";
				
				$Query = "INSERT INTO Energies (Energy_ID, Name, Tech, Size, Energy_Bank, Electricity, Visibility, Weight, Fuel_Amount, Fuel_Type, Fuel_Tick, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Energy_ID, $Energy_Name, $Energy_Tech, $Energy_Size, $Energy_Bank_Size, $Energy_Regeneration, $Energy_Visibility, $Energy_Weight, $Energy_Fuel_Amount, $Energy_Fuel_Type, $Energy_Fuel_Tick, $Energy_Required_Skill_ID, $Energy_Required_Skill_Level, $Energy_Required_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
				
				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>14 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);
						
						$Query = "INSERT INTO Energy_Mods (Energy_ID, Excel_Mod_ID, Value) VALUES ($Energy_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r energies and $m energy modifications<br/>";
	}
}
if (isset($_POST['Weapons'])) {
	//
	//	Weapons
	//
	// Fetch data from Excel
	if (isset($GID['Weapons'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Weapons'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Weapons";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "") {
				//	ID - Name - ID
				$Weapon_ID = mysql_real_escape_string($row[0], $conn);
				$Weapon_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Weapon_Tech = mysql_real_escape_string($row[2], $conn);
				$Weapon_Size = mysql_real_escape_string($row[3], $conn);
				$Weapon_Damage_Low = mysql_real_escape_string($row[4], $conn);
				$Weapon_Damage_High = mysql_real_escape_string($row[5], $conn);
				$Weapon_Projectiles = mysql_real_escape_string($row[6], $conn);
				$Weapon_Damage_Self = mysql_real_escape_string(($row[7]+0), $conn);
				$Weapon_Range = mysql_real_escape_string($row[8], $conn);
				$Weapon_Recoil = mysql_real_escape_string($row[9], $conn);
				$Weapon_Electricity = mysql_real_escape_string($row[10], $conn);
				if (mysql_real_escape_string($row[11], $conn) == "") {
					$Weapon_Visibility = "NULL";
				} else {
					$Weapon_Visibility = mysql_real_escape_string($row[11], $conn);
				}
				if (mysql_real_escape_string($row[12], $conn) == "") {
					$Weapon_Visibility_Length = "NULL";
				} else {
					$Weapon_Visibility_Length = mysql_real_escape_string($row[12], $conn);
				}

				$Weapon_Weight = mysql_real_escape_string($row[13]+0, $conn);
				$Weapon_Ethereal = mysql_real_escape_string($row[14], $conn);
				$Weapon_Type = "'" . mysql_real_escape_string($row[15], $conn) . "'";
				$Weapon_Req_Skill_ID = mysql_real_escape_string($row[16], $conn)+0;
				$Weapon_Req_Skill_Level = mysql_real_escape_string($row[18], $conn)+0;
				$Weapon_Req_Ship = "'" . mysql_real_escape_string($row[19], $conn) . "'";

				$Query = "INSERT INTO Weapons (Weapon_ID, Name, Tech, Size, Projectiles, Damage_Min, Damage_Max, Damage_Self, W_Range, Recoil, Electricity, Visibility, Vis_Length, Weight, Ethereal, Type, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Weapon_ID, $Weapon_Name, $Weapon_Tech, $Weapon_Size, $Weapon_Projectiles, $Weapon_Damage_Low, $Weapon_Damage_High, $Weapon_Damage_Self, $Weapon_Range, $Weapon_Recoil, $Weapon_Electricity, $Weapon_Visibility, $Weapon_Visibility_Length, $Weapon_Weight, $Weapon_Ethereal, $Weapon_Type, $Weapon_Req_Skill_ID, $Weapon_Req_Skill_Level, $Weapon_Req_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>19 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Weapon_Mods (Weapon_ID, Excel_Mod_ID, Value) VALUES ($Weapon_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r weapons<br/>";
	}
}
if (isset($_POST['Solar_Panels'])) {
	//
	//	Solar Panels
	//
	// Fetch data from Excel
	if (isset($GID['Solar_Panels'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Solar_Panels'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Solar_Panels";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Solar_Panel_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Solar_Panel_ID = mysql_real_escape_string($row[0], $conn);
				$Solar_Panel_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Solar_Panel_Tech = mysql_real_escape_string($row[2], $conn);
				$Solar_Panel_Size = mysql_real_escape_string($row[3], $conn);
				$Solar_Panel_Electricity = mysql_real_escape_string($row[4], $conn);
				$Solar_Panel_Weight = mysql_real_escape_string($row[5], $conn);
				$Solar_Panel_Required_Skill_ID = mysql_real_escape_string($row[6], $conn)+0;
				$Solar_Panel_Required_Skill_Level = mysql_real_escape_string($row[8], $conn)+0;
				$Solar_Panel_Required_Ship = "'" . mysql_real_escape_string($row[9], $conn) . "'";

				$Query = "INSERT INTO Solar_Panels (Solar_Panel_ID, Name, Tech, Size, Electricity, Weight, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Solar_Panel_ID, $Solar_Panel_Name, $Solar_Panel_Tech, $Solar_Panel_Size, $Solar_Panel_Electricity, $Solar_Panel_Weight, $Solar_Panel_Required_Skill_ID, $Solar_Panel_Required_Skill_Level, $Solar_Panel_Required_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>9 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Solar_Panel_Mods (Solar_Panel_ID, Excel_Mod_ID, Value) VALUES ($Solar_Panel_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r solar panels and $m solar panel modifications<br/>";
	}
}
if (isset($_POST['Capacitors'])) {
	//
	//	Capacitors
	//
	// Fetch data from Excel
	if (isset($GID['Capacitors'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Capacitors'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Capacitors";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Capacitor_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Capacitor_ID = mysql_real_escape_string($row[0], $conn);
				$Capacitor_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Capacitor_Tech = mysql_real_escape_string($row[2], $conn);
				$Capacitor_Size = mysql_real_escape_string($row[3], $conn);
				$Capacitor_Shield_Boost = mysql_real_escape_string($row[4], $conn);
				$Capacitor_Energy_Boost = mysql_real_escape_string($row[5], $conn);
				$Capacitor_Weight = mysql_real_escape_string($row[6], $conn);

				$Query = "INSERT INTO Capacitors (Capacitor_ID, Name, Tech, Size, Weight, Shield_Boost, Energy_Boost) VALUES ($Capacitor_ID, $Capacitor_Name, $Capacitor_Tech, $Capacitor_Size, $Capacitor_Weight, $Capacitor_Shield_Boost, $Capacitor_Energy_Boost);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>6 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Capacitor_Mods (Capacitor_ID, Excel_Mod_ID, Value) VALUES ($Capacitor_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r capacitors and $m capacitor modifications<br/>";
	}
}
if (isset($_POST['Diffusers'])) {
	//
	//	Diffusers
	//
	// Fetch data from Excel
	if (isset($GID['Diffusers'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Diffusers'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Diffusers";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Diffuser_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Diffuser_ID = mysql_real_escape_string($row[0], $conn);
				$Diffuser_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Diffuser_Tech = mysql_real_escape_string($row[2], $conn);
				$Diffuser_Size = mysql_real_escape_string($row[3], $conn);
				$Diffuser_Failure = mysql_real_escape_string(str_replace("%","",$row[4]), $conn);
				$Diffuser_Electricity = mysql_real_escape_string($row[5], $conn);
				$Diffuser_Visibility = mysql_real_escape_string($row[6], $conn);
				$Diffuser_Weight = mysql_real_escape_string($row[7], $conn);

				$Diffuser_Laser = mysql_real_escape_string($row[8], $conn) + 0;
				$Diffuser_Energy = mysql_real_escape_string($row[9], $conn) + 0;
				$Diffuser_Heat = mysql_real_escape_string($row[10], $conn) + 0;
				$Diffuser_Physical = mysql_real_escape_string($row[11], $conn) + 0;
				$Diffuser_Radiation = mysql_real_escape_string($row[12], $conn) + 0;
				$Diffuser_Surgical = mysql_real_escape_string($row[13], $conn) + 0;
				$Diffuser_Mining = mysql_real_escape_string($row[14], $conn) + 0;
				$Diffuser_Transference = mysql_real_escape_string($row[15], $conn) + 0;
				$Diffuser_Required_Skill_ID = mysql_real_escape_string($row[16], $conn) + 0;
				$Diffuser_Required_Skill_Level = mysql_real_escape_string($row[18], $conn) + 0;
				$Diffuser_Required_Ship = "'" . mysql_real_escape_string($row[19], $conn) . "'";

				$Query = "INSERT INTO Diffusers (Diffuser_ID, Name, Tech, Size, Failure, Electricity, Visibility, Weight, Resistance_Laser, Resistance_Energy, Resistance_Heat, Resistance_Physical, Resistance_Radiation, Resistance_Surgical, Resistance_Mining, Resistance_Transference, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Diffuser_ID, $Diffuser_Name, $Diffuser_Tech, $Diffuser_Size, $Diffuser_Failure, $Diffuser_Electricity, $Diffuser_Visibility, $Diffuser_Weight, $Diffuser_Laser, $Diffuser_Energy, $Diffuser_Heat, $Diffuser_Physical, $Diffuser_Radiation, $Diffuser_Surgical, $Diffuser_Mining, $Diffuser_Transference, $Diffuser_Required_Skill_ID, $Diffuser_Required_Skill_Level, $Diffuser_Required_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>19 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Diffuser_Mods (Diffuser_ID, Excel_Mod_ID, Value) VALUES ($Diffuser_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r diffusers and $m diffuser modifications<br/>";
	}
}
if (isset($_POST['Hull_Expanders'])) {
	//
	//	Hull Expanders
	//
	// Fetch data from Excel
	if (isset($GID['Hull_Expanders'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Hull_Expanders'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Hull_Expanders";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Hull_Expander_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Hull_Expander_ID = mysql_real_escape_string($row[0], $conn);
				$Hull_Expander_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Hull_Expander_Tech = mysql_real_escape_string($row[2], $conn);
				$Hull_Expander_Size = mysql_real_escape_string($row[3], $conn);
				$Hull_Expander_Capacity = mysql_real_escape_string($row[4], $conn);
				$Hull_Expander_Weight = mysql_real_escape_string($row[5], $conn);
				$Hull_Expander_Required_Skill_ID = mysql_real_escape_string($row[6], $conn) + 0;
				$Hull_Expander_Required_Skill_Level = mysql_real_escape_string($row[8], $conn) + 0;
				$Hull_Expander_Required_Ship = "'" . mysql_real_escape_string($row[9], $conn) . "'";

				$Query = "INSERT INTO Hull_Expanders (Hull_Expander_ID, Name, Tech, Size, Extra_Space, Weight, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Hull_Expander_ID, $Hull_Expander_Name, $Hull_Expander_Tech, $Hull_Expander_Size, $Hull_Expander_Capacity, $Hull_Expander_Weight, $Hull_Expander_Required_Skill_ID, $Hull_Expander_Required_Skill_Level, $Hull_Expander_Required_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>9 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Hull_Expander_Mods (Hull_Expander_ID, Excel_Mod_ID, Value) VALUES ($Hull_Expander_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r hull expanders and $m hull expander modifications<br/>";
	}
}
if (isset($_POST['Radars'])) {
	//
	//	Radars
	//
	// Fetch data from Excel
	if (isset($GID['Radars'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Radars'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Radars";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Radar_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Radar_ID = mysql_real_escape_string($row[0], $conn);
				$Radar_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Radar_Tech = mysql_real_escape_string($row[2], $conn);
				$Radar_Size = mysql_real_escape_string($row[3], $conn);
				$Radar_Vision = mysql_real_escape_string($row[4], $conn);
				$Radar_Detection = mysql_real_escape_string($row[5], $conn);
				$Radar_Electricity = mysql_real_escape_string($row[6], $conn);
				$Radar_Visibility = mysql_real_escape_string($row[7], $conn);
				$Radar_Weight = mysql_real_escape_string($row[8], $conn);

				$Radar_Charge_Required = (mysql_real_escape_string($row[9], $conn) == "" ? "NULL" : mysql_real_escape_string($row[9], $conn));
				$Radar_Charging_Rate = (mysql_real_escape_string($row[10], $conn) == "" ? "NULL" : mysql_real_escape_string($row[10], $conn));
				$Radar_Ping_Bonus = (mysql_real_escape_string($row[11], $conn) == "" ? "NULL" : mysql_real_escape_string($row[11], $conn));
				$Radar_Ping_Time = (mysql_real_escape_string($row[12], $conn) == "" ? "NULL" : mysql_real_escape_string($row[12], $conn));
				$Radar_Ping_Visibility = (mysql_real_escape_string($row[13], $conn) == "" ? "NULL" : mysql_real_escape_string($row[13], $conn));

				$Query = "INSERT INTO Radars (Radar_ID, Name, Tech, Size, Vision, Detection, Electricity, Visibility, Weight, Charge_Required, Charging_Rate, Ping_Bonus, Ping_Time, Ping_Visibility) VALUES ($Radar_ID, $Radar_Name, $Radar_Tech, $Radar_Size, $Radar_Vision, $Radar_Detection, $Radar_Electricity, $Radar_Visibility, $Radar_Weight, $Radar_Charge_Required, $Radar_Charging_Rate, $Radar_Ping_Bonus, $Radar_Ping_Time, $Radar_Ping_Visibility);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>13 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Radar_Mods (Radar_ID, Excel_Mod_ID, Value) VALUES ($Radar_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r radars and $m radar modifications<br/>";
	}
}
if (isset($_POST['Scoops'])) {
	//
	//	Scoops
	//
	// Fetch data from Excel
	if (isset($GID['Scoops'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Scoops'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Scoops";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Scoop_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Scoop_ID = mysql_real_escape_string($row[0], $conn);
				$Scoop_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Scoop_Tech = mysql_real_escape_string($row[2], $conn);
				$Scoop_Size = mysql_real_escape_string($row[3], $conn);
				$Scoop_Electricity = mysql_real_escape_string($row[4], $conn);
				$Scoop_Range = (mysql_real_escape_string($row[5], $conn) == "" ? "NULL" : mysql_real_escape_string($row[5], $conn));
				$Scoop_Debris = (mysql_real_escape_string($row[6], $conn) == "" ? "NULL" : mysql_real_escape_string($row[6], $conn));
				$Scoop_Drones = "'" . (mysql_real_escape_string($row[7], $conn) == "Yes" ? "Yes" : "No") . "'";
				$Scoop_Maul = (mysql_real_escape_string($row[8], $conn) == "" ? "NULL" : mysql_real_escape_string($row[8], $conn));
				$Scoop_Weight = mysql_real_escape_string($row[9], $conn);

				$Query = "INSERT INTO Scoops (Scoop_ID, Name, Tech, Size, Electricity, S_Range, Debris, Scoop_Drones, Maul, Weight) VALUES ($Scoop_ID, $Scoop_Name, $Scoop_Tech, $Scoop_Size, $Scoop_Electricity, $Scoop_Range, $Scoop_Debris, $Scoop_Drones, $Scoop_Maul, $Scoop_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>9 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Scoop_Mods (Scoop_ID, Excel_Mod_ID, Value) VALUES ($Scoop_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r scoops and $m scoop modifications<br/>";
	}
}
if (isset($_POST['Cloaks'])) {
	//
	//	Cloaks
	//
	// Fetch data from Excel
	if (isset($GID['Cloaks'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Cloaks'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Cloaks";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Cloak_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Cloak_ID = mysql_real_escape_string($row[0], $conn);
				$Cloak_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Cloak_Tech = mysql_real_escape_string($row[2], $conn);
				$Cloak_Size = mysql_real_escape_string($row[3], $conn);
				$Cloak_Detection_Cloaking = mysql_real_escape_string($row[4], $conn);
				$Cloak_Visibility_Cloaking = mysql_real_escape_string($row[5], $conn);
				$Cloak_Electricity = mysql_real_escape_string($row[6], $conn);
				$Cloak_Weight = mysql_real_escape_string($row[7], $conn);

				$Query = "INSERT INTO Cloaks (Cloak_ID, Name, Tech, Size, Detection_Cloaking, Visibility_Cloaking, Electricity, Weight) VALUES ($Cloak_ID, $Cloak_Name, $Cloak_Tech, $Cloak_Size, $Cloak_Detection_Cloaking, $Cloak_Visibility_Cloaking, $Cloak_Electricity, $Cloak_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>7 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Cloak_Mods (Cloak_ID, Excel_Mod_ID, Value) VALUES ($Cloak_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r cloaks and $m cloak modifications<br/>";
	}
}
if (isset($_POST['Item_Modifications'])) {
	//
	//	Item Modifications
	//
	// Fetch data from Excel
	if (isset($GID['Item_Modifications'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Item_Modifications'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Item_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Item_Modification_ID = mysql_real_escape_string($row[0], $conn);
				$Item_Modification_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Item_Modification_Item = "'" . mysql_real_escape_string($row[2], $conn) . "'";
				$Item_Modification_Mod1_ID = mysql_real_escape_string($row[3], $conn)+0;
				$Item_Modification_Mod1_Initial = mysql_real_escape_string($row[5], $conn)+0;
				$Item_Modification_Mod1_Tech = mysql_real_escape_string($row[6], $conn)+0;
				$Item_Modification_Mod2_ID = mysql_real_escape_string($row[7], $conn)+0;
				$Item_Modification_Mod2_Initial = mysql_real_escape_string($row[9], $conn)+0;
				$Item_Modification_Mod2_Tech = mysql_real_escape_string($row[10], $conn)+0;
				$Item_Modification_Mod3_ID = mysql_real_escape_string($row[11], $conn)+0;
				$Item_Modification_Mod3_Initial = mysql_real_escape_string($row[13], $conn)+0;
				$Item_Modification_Mod3_Tech = mysql_real_escape_string($row[14], $conn)+0;
				$Item_Modification_Mod_Flat_ID = mysql_real_escape_string($row[15], $conn)+0;
				$Item_Modification_Mod_Flat_Initial = mysql_real_escape_string($row[17], $conn)+0;
				$Item_Modification_Mod_Flat_Tech = mysql_real_escape_string($row[18], $conn)+0;
				if (mysql_real_escape_string($row[19], $conn) == "") {
					$Item_Modification_Specific = "NULL";	
				} else {
					$Item_Modification_Specific = "'" . mysql_real_escape_string($row[19], $conn) . "'";
				}
				if (mysql_real_escape_string($row[20], $conn) == "") {
					$Item_Modification_Global = "NULL";
				} else {
					$Item_Modification_Global = "'" . mysql_real_escape_string($row[20], $conn) . "'";	
				}

				$Query = "INSERT INTO Item_Mods (Item_Mods_ID, Name, Item, Mod1_ID, Mod1_Initial, Mod1_Tech, Mod2_ID, Mod2_Initial, Mod2_Tech, Mod3_ID, Mod3_Initial, Mod3_Tech, Mod_Flat_ID, Mod_Flat_Initial, Mod_Flat_Tech, Mod_Specific, Mod_Global) VALUES ($Item_Modification_ID, $Item_Modification_Name, $Item_Modification_Item, $Item_Modification_Mod1_ID, $Item_Modification_Mod1_Initial, $Item_Modification_Mod1_Tech, $Item_Modification_Mod2_ID, $Item_Modification_Mod2_Initial, $Item_Modification_Mod2_Tech, $Item_Modification_Mod3_ID, $Item_Modification_Mod3_Initial, $Item_Modification_Mod3_Tech, $Item_Modification_Mod_Flat_ID, $Item_Modification_Mod_Flat_Initial, $Item_Modification_Mod_Flat_Tech, $Item_Modification_Specific, $Item_Modification_Global);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
				$r+=1;
			}
		}

		echo "Imported $r item modifications<br/>";
	}
}
if (isset($_POST['Engines'])) {
	//
	//	Engines
	//
	// Fetch data from Excel
	if (isset($GID['Engines'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Engines'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Engines";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Engine_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Engine_ID = mysql_real_escape_string($row[0], $conn);
				$Engine_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Engine_Tech = mysql_real_escape_string($row[2], $conn);
				$Engine_Size = mysql_real_escape_string($row[3], $conn);
				$Engine_Thrust = mysql_real_escape_string($row[4], $conn);
				$Engine_Turn = mysql_real_escape_string($row[5], $conn);
				$Engine_Visibility = mysql_real_escape_string($row[6], $conn);
				$Engine_Weight = mysql_real_escape_string($row[7], $conn);

				$Query = "INSERT INTO Engines (Engine_ID, Name, Tech, Size, Thrust, Turn, Visibility, Weight) VALUES ($Engine_ID, $Engine_Name, $Engine_Tech, $Engine_Size, $Engine_Thrust, $Engine_Turn, $Engine_Visibility, $Engine_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>7 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Engine_Mods (Engine_ID, Excel_Mod_ID, Value) VALUES ($Engine_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r engines and $m engine modifications<br/>";
	}
}
if (isset($_POST['Shield_Chargers'])) {
	//
	//	Shield Chargers
	//
	// Fetch data from Excel
	if (isset($GID['Shield_Chargers'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Shield_Chargers'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Shield_Chargers";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Shield_Charger_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Shield_Charger_ID = mysql_real_escape_string($row[0], $conn);
				$Shield_Charger_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Shield_Charger_Tech = mysql_real_escape_string($row[2], $conn);
				$Shield_Charger_Size = mysql_real_escape_string($row[3], $conn);
				$Shield_Charger_Shield_Recharge = mysql_real_escape_string($row[4], $conn);
				$Shield_Charger_Electricity = mysql_real_escape_string($row[5], $conn);
				$Shield_Charger_Weight = mysql_real_escape_string($row[6], $conn);

				$Query = "INSERT INTO Shield_Chargers (Shield_Charger_ID, Name, Tech, Size, Regeneration, Electricity, Weight) VALUES ($Shield_Charger_ID, $Shield_Charger_Name, $Shield_Charger_Tech, $Shield_Charger_Size, $Shield_Charger_Shield_Recharge, $Shield_Charger_Electricity, $Shield_Charger_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>6 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Shield_Charger_Mods (Shield_Charger_ID, Excel_Mod_ID, Value) VALUES ($Shield_Charger_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r shield chargers and $m shield charger modifications<br/>";
	}
}
if (isset($_POST['Tractors'])) {
	//
	//	Tractors
	//
	// Fetch data from Excel
	if (isset($GID['Tractors'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Tractors'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Tractors";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Tractor_ID = mysql_real_escape_string($row[0], $conn);
				$Tractor_Type = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Tractor_Name = "'" . mysql_real_escape_string($row[2], $conn) . "'";
				$Tractor_Tech = mysql_real_escape_string($row[3], $conn);
				$Tractor_Size = mysql_real_escape_string($row[4], $conn);
				$Tractor_Strength = mysql_real_escape_string($row[5], $conn);
				$Tractor_Density = mysql_real_escape_string($row[6], $conn);
				$Tractor_Range = mysql_real_escape_string($row[7], $conn);
				$Tractor_Rest_Length = mysql_real_escape_string($row[8], $conn);
				$Tractor_Electricity = mysql_real_escape_string($row[9], $conn);
				$Tractor_Visibility = mysql_real_escape_string($row[10], $conn);
				$Tractor_Weight = mysql_real_escape_string($row[11], $conn);

				$Query = "INSERT INTO Tractors (Tractor_ID, Type, Name, Tech, Size, Strength, Density, T_Range, Rest_Length, Electricity, Visibility, Weight) VALUES ($Tractor_ID, $Tractor_Type, $Tractor_Name, $Tractor_Tech, $Tractor_Size, $Tractor_Strength, $Tractor_Density, $Tractor_Range, $Tractor_Rest_Length, $Tractor_Electricity, $Tractor_Visibility, $Tractor_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				$r+=1;
			}
		}

		echo "Imported $r tractors<br/>";
	}
}
if (isset($_POST['Controlbots'])) {
	//
	//	Controlbots
	//
	// Fetch data from Excel
	if (isset($GID['Controlbots'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Controlbots'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Controlbots";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Controlbot_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Controlbot_ID = mysql_real_escape_string($row[0], $conn);
				$Controlbot_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Controlbot_Tech = mysql_real_escape_string($row[2], $conn);
				$Controlbot_Size = mysql_real_escape_string($row[3], $conn);
				$Controlbot_Weight = mysql_real_escape_string($row[4], $conn);

				$Query = "INSERT INTO Controlbots (Controlbot_ID, Name, Tech, Size, Weight) VALUES ($Controlbot_ID, $Controlbot_Name, $Controlbot_Tech, $Controlbot_Size, $Controlbot_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>4 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Controlbot_Mods (Controlbot_ID, Excel_Mod_ID, Value) VALUES ($Controlbot_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r controlbots and $m controlbot modifications<br/>";
	}
}
if (isset($_POST['Aura_Generators'])) {
	//
	//	Aura_Generators
	//
	// Fetch data from Excel
	if (isset($GID['Aura_Generators'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Aura_Generators'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Aura_Generators";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Aura_Generator_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Aura_Generator_ID = mysql_real_escape_string($row[0], $conn);
				$Aura_Generator_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Aura_Generator_Tech = mysql_real_escape_string($row[2], $conn);
				$Aura_Generator_Size = mysql_real_escape_string($row[3], $conn);
				$Aura_Generator_Electricity = mysql_real_escape_string($row[4], $conn);
				$Aura_Generator_Visibility = mysql_real_escape_string($row[5], $conn);
				$Aura_Generator_Weight = mysql_real_escape_string($row[6], $conn);

				$Aura_Generator_ID_1 = mysql_real_escape_string($row[7], $conn)+0;
				//
				$Aura_Generator_Value_1 = mysql_real_escape_string($row[9], $conn)+0;
				$Aura_Generator_Range_1 = mysql_real_escape_string($row[10], $conn)+0;
				$Aura_Generator_Cycle_1 = mysql_real_escape_string($row[11], $conn)+0;
				$Aura_Generator_Duration_1 = mysql_real_escape_string($row[12], $conn)+0;
				$Aura_Generator_Allies_1 = "'" . mysql_real_escape_string($row[13], $conn) . "'";
				$Aura_Generator_Enemies_1 = "'" . mysql_real_escape_string($row[14], $conn) . "'";
				$Aura_Generator_Neutral_1 = "'" . mysql_real_escape_string($row[15], $conn) . "'";
				$Aura_Generator_Self_1 = "'" . mysql_real_escape_string($row[16], $conn) . "'";
				$Aura_Generator_Tweakable_1 = "'" . mysql_real_escape_string($row[17], $conn) . "'";
				$Aura_Generator_Capship_1 = "'" . mysql_real_escape_string($row[18], $conn) . "'";
				$Aura_Generator_Delay_1 = mysql_real_escape_string($row[19], $conn)+0;

				$Aura_Generator_ID_2 = mysql_real_escape_string($row[20], $conn)+0;
				//
				$Aura_Generator_Value_2 = mysql_real_escape_string($row[22], $conn)+0;
				$Aura_Generator_Range_2 = mysql_real_escape_string($row[23], $conn)+0;
				$Aura_Generator_Cycle_2 = mysql_real_escape_string($row[24], $conn)+0;
				$Aura_Generator_Duration_2 = mysql_real_escape_string($row[25], $conn)+0;
				$Aura_Generator_Allies_2 = "'" . mysql_real_escape_string($row[26], $conn) . "'";
				$Aura_Generator_Enemies_2 = "'" . mysql_real_escape_string($row[27], $conn) . "'";
				$Aura_Generator_Neutral_2 = "'" . mysql_real_escape_string($row[28], $conn) . "'";
				$Aura_Generator_Self_2 = "'" . mysql_real_escape_string($row[29], $conn) . "'";
				$Aura_Generator_Tweakable_2 = "'" . mysql_real_escape_string($row[30], $conn) . "'";
				$Aura_Generator_Capship_2 = "'" . mysql_real_escape_string($row[31], $conn) . "'";
				$Aura_Generator_Delay_2 = mysql_real_escape_string($row[32], $conn)+0;

				$Aura_Generator_ID_3 = mysql_real_escape_string($row[33], $conn)+0;
				//
				$Aura_Generator_Value_3 = mysql_real_escape_string($row[35], $conn)+0;
				$Aura_Generator_Range_3 = mysql_real_escape_string($row[36], $conn)+0;
				$Aura_Generator_Cycle_3 = mysql_real_escape_string($row[37], $conn)+0;
				$Aura_Generator_Duration_3 = mysql_real_escape_string($row[38], $conn)+0;
				$Aura_Generator_Allies_3 = "'" . mysql_real_escape_string($row[39], $conn) . "'";
				$Aura_Generator_Enemies_3 = "'" . mysql_real_escape_string($row[40], $conn) . "'";
				$Aura_Generator_Neutral_3 = "'" . mysql_real_escape_string($row[41], $conn) . "'";
				$Aura_Generator_Self_3 = "'" . mysql_real_escape_string($row[42], $conn) . "'";
				$Aura_Generator_Tweakable_3 = "'" . mysql_real_escape_string($row[43], $conn) . "'";
				$Aura_Generator_Capship_3 = "'" . mysql_real_escape_string($row[44], $conn) . "'";
				$Aura_Generator_Delay_3 = mysql_real_escape_string($row[45], $conn)+0;

				$Aura_Generator_ID_4 = mysql_real_escape_string($row[46], $conn)+0;
				//
				$Aura_Generator_Value_4 = mysql_real_escape_string($row[48], $conn)+0;
				$Aura_Generator_Range_4 = mysql_real_escape_string($row[49], $conn)+0;
				$Aura_Generator_Cycle_4 = mysql_real_escape_string($row[50], $conn)+0;
				$Aura_Generator_Duration_4 = mysql_real_escape_string($row[51], $conn)+0;
				$Aura_Generator_Allies_4 = "'" . mysql_real_escape_string($row[52], $conn) . "'";
				$Aura_Generator_Enemies_4 = "'" . mysql_real_escape_string($row[53], $conn) . "'";
				$Aura_Generator_Neutral_4 = "'" . mysql_real_escape_string($row[54], $conn) . "'";
				$Aura_Generator_Self_4 = "'" . mysql_real_escape_string($row[55], $conn) . "'";
				$Aura_Generator_Tweakable_4 = "'" . mysql_real_escape_string($row[56], $conn) . "'";
				$Aura_Generator_Capship_4 = "'" . mysql_real_escape_string($row[57], $conn) . "'";
				$Aura_Generator_Delay_4 = mysql_real_escape_string($row[58], $conn)+0;

				$Aura_Generator_ID_5 = mysql_real_escape_string($row[59], $conn)+0;
				//
				$Aura_Generator_Value_5 = mysql_real_escape_string($row[61], $conn)+0;
				$Aura_Generator_Range_5 = mysql_real_escape_string($row[62], $conn)+0;
				$Aura_Generator_Cycle_5 = mysql_real_escape_string($row[63], $conn)+0;
				$Aura_Generator_Duration_5 = mysql_real_escape_string($row[64], $conn)+0;
				$Aura_Generator_Allies_5 = "'" . mysql_real_escape_string($row[65], $conn) . "'";
				$Aura_Generator_Enemies_5 = "'" . mysql_real_escape_string($row[66], $conn) . "'";
				$Aura_Generator_Neutral_5 = "'" . mysql_real_escape_string($row[67], $conn) . "'";
				$Aura_Generator_Self_5 = "'" . mysql_real_escape_string($row[68], $conn) . "'";
				$Aura_Generator_Tweakable_5 = "'" . mysql_real_escape_string($row[69], $conn) . "'";
				$Aura_Generator_Capship_5 = "'" . mysql_real_escape_string($row[70], $conn) . "'";
				$Aura_Generator_Delay_5 = mysql_real_escape_string($row[71], $conn)+0;

				$Query = "INSERT INTO Aura_Generators (Aura_Generator_ID, Name, Tech, Size, Electricity, Visibility, Weight, Field_ID_1, Field_Value_1, Field_Range_1, Field_Cycle_1, Field_Duration_1, Field_Allies_1, Field_Enemies_1, Field_Neutral_1, Field_Self_1, Field_Tweakable_1, Capship_1, Delay_1, Field_ID_2, Field_Value_2, Field_Range_2, Field_Cycle_2, Field_Duration_2, Field_Allies_2, Field_Enemies_2, Field_Neutral_2, Field_Self_2, Field_Tweakable_2, Capship_2, Delay_2, Field_ID_3, Field_Value_3, Field_Range_3, Field_Cycle_3, Field_Duration_3, Field_Allies_3, Field_Enemies_3, Field_Neutral_3, Field_Self_3, Field_Tweakable_3, Capship_3, Delay_3, Field_ID_4, Field_Value_4, Field_Range_4, Field_Cycle_4, Field_Duration_4, Field_Allies_4, Field_Enemies_4, Field_Neutral_4, Field_Self_4, Field_Tweakable_4, Capship_4, Delay_4, Field_ID_5, Field_Value_5, Field_Range_5, Field_Cycle_5, Field_Duration_5, Field_Allies_5, Field_Enemies_5, Field_Neutral_5, Field_Self_5, Field_Tweakable_5, Capship_5, Delay_5) VALUES ($Aura_Generator_ID, $Aura_Generator_Name, $Aura_Generator_Tech, $Aura_Generator_Size, $Aura_Generator_Electricity, $Aura_Generator_Visibility, $Aura_Generator_Weight, $Aura_Generator_ID_1, $Aura_Generator_Value_1, $Aura_Generator_Range_1, $Aura_Generator_Cycle_1, $Aura_Generator_Duration_1, $Aura_Generator_Allies_1, $Aura_Generator_Enemies_1, $Aura_Generator_Neutral_1, $Aura_Generator_Self_1, $Aura_Generator_Tweakable_1, $Aura_Generator_Capship_1, $Aura_Generator_Delay_1, $Aura_Generator_ID_2, $Aura_Generator_Value_2, $Aura_Generator_Range_2, $Aura_Generator_Cycle_2, $Aura_Generator_Duration_2, $Aura_Generator_Allies_2, $Aura_Generator_Enemies_2, $Aura_Generator_Neutral_2, $Aura_Generator_Self_2, $Aura_Generator_Tweakable_2, $Aura_Generator_Capship_2, $Aura_Generator_Delay_2, $Aura_Generator_ID_3, $Aura_Generator_Value_3, $Aura_Generator_Range_3, $Aura_Generator_Cycle_3, $Aura_Generator_Duration_3, $Aura_Generator_Allies_3, $Aura_Generator_Enemies_3, $Aura_Generator_Neutral_3, $Aura_Generator_Self_3, $Aura_Generator_Tweakable_3, $Aura_Generator_Capship_3, $Aura_Generator_Delay_3, $Aura_Generator_ID_4, $Aura_Generator_Value_4, $Aura_Generator_Range_4, $Aura_Generator_Cycle_4, $Aura_Generator_Duration_4, $Aura_Generator_Allies_4, $Aura_Generator_Enemies_4, $Aura_Generator_Neutral_4, $Aura_Generator_Self_4, $Aura_Generator_Tweakable_4, $Aura_Generator_Capship_4, $Aura_Generator_Delay_4, $Aura_Generator_ID_5, $Aura_Generator_Value_5, $Aura_Generator_Range_5, $Aura_Generator_Cycle_5, $Aura_Generator_Duration_5, $Aura_Generator_Allies_5, $Aura_Generator_Enemies_5, $Aura_Generator_Neutral_5, $Aura_Generator_Self_5, $Aura_Generator_Tweakable_5, $Aura_Generator_Capship_5, $Aura_Generator_Delay_5);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>71 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Aura_Generator_Mods (Aura_Generator_ID, Excel_Mod_ID, Value) VALUES ($Aura_Generator_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}

				$r+=1;
			}
		}
		echo "Imported $r aura generators and $m aura generator modifications<br/>";
	}
}

if (isset($_POST['Exterminators'])) {
	//
	//	Exterminators
	//
	// Fetch data from Excel
	if (isset($GID['Exterminators'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Exterminators'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Exterminators";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Exterminator_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Exterminator_ID = mysql_real_escape_string($row[0], $conn);
				$Exterminator_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Exterminator_Tech = mysql_real_escape_string($row[2], $conn);
				$Exterminator_Size = mysql_real_escape_string($row[3], $conn);
				$Exterminator_Appetite = mysql_real_escape_string($row[4], $conn);
				$Exterminator_Tick = mysql_real_escape_string($row[5], $conn);
				$Exterminator_Electricity = mysql_real_escape_string($row[6], $conn);
				$Exterminator_Weight = mysql_real_escape_string($row[7], $conn);

				$Query = "INSERT INTO Exterminators (Exterminator_ID, Name, Tech, Size, Appetite, Tick, Electricity, Weight) VALUES ($Exterminator_ID, $Exterminator_Name, $Exterminator_Tech, $Exterminator_Size, $Exterminator_Appetite, $Exterminator_Tick, $Exterminator_Electricity, $Exterminator_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>7 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Exterminator_Mods (Exterminator_ID, Excel_Mod_ID, Value) VALUES ($Exterminator_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Exterminators and $m exterminator modifications<br/>";
	}
}

if (isset($_POST['Homing_Beacons'])) {
	//
	//	Homing_Beacons
	//
	// Fetch data from Excel
	if (isset($GID['Homing_Beacons'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Homing_Beacons'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Homing_Beacons";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Homing_Beacon_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Homing_Beacon_ID = mysql_real_escape_string($row[0], $conn);
				$Homing_Beacon_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Homing_Beacon_Tech = mysql_real_escape_string($row[2], $conn);
				$Homing_Beacon_Size = mysql_real_escape_string($row[3], $conn);
				$Homing_Beacon_Visibility = mysql_real_escape_string($row[4], $conn);
				$Homing_Beacon_Electricity = mysql_real_escape_string($row[5], $conn);
				$Homing_Beacon_Weight = mysql_real_escape_string($row[6], $conn);

				$Query = "INSERT INTO Homing_Beacons (Homing_Beacon_ID, Name, Tech, Size, Visibility, Electricity, Weight) VALUES ($Homing_Beacon_ID, $Homing_Beacon_Name, $Homing_Beacon_Tech, $Homing_Beacon_Size, $Homing_Beacon_Visibility, $Homing_Beacon_Electricity, $Homing_Beacon_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>6 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Homing_Beacon_Mods (Homing_Beacon_ID, Excel_Mod_ID, Value) VALUES ($Homing_Beacon_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Homing beacons and $m homing beacon modifications<br/>";
	}
}

if (isset($_POST['Neurotweaks'])) {
	//
	//	Neurotweaks
	//
	// Fetch data from Excel
	if (isset($GID['Neurotweaks'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Neurotweaks'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Neurotweaks";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Neurotweak_ID = mysql_real_escape_string($row[0], $conn);
				$Neurotweak_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Neurotweak_Tech = mysql_real_escape_string($row[2], $conn);
				$Neurotweak_Size = mysql_real_escape_string($row[3], $conn);
				$Neurotweak_Elec_Cost = mysql_real_escape_string($row[4], $conn)+0;
				$Neurotweak_Shield_Cost = mysql_real_escape_string($row[5], $conn)+0;
				$Neurotweak_Weight = mysql_real_escape_string($row[6], $conn)+0;
				$Neurotweak_Visibility = mysql_real_escape_string($row[7], $conn)+0;
				$Neurotweak_Require_Skill_ID = mysql_real_escape_string($row[8], $conn)+0;
				$Neurotweak_Require_Skill_Level = mysql_real_escape_string($row[10], $conn)+0;
				$Neurotweak_Require_Ship = "'" . mysql_real_escape_string($row[11], $conn) . "'";

				$Query = "INSERT INTO Neurotweaks (Neurotweak_ID, Name, Tech, Size, Electricity, Shield, Weight, Visibility, Require_Skill_ID, Require_Skill_Level, Require_Ship) VALUES ($Neurotweak_ID, $Neurotweak_Name, $Neurotweak_Tech, $Neurotweak_Size, $Neurotweak_Elec_Cost, $Neurotweak_Shield_Cost, $Neurotweak_Weight, $Neurotweak_Visibility, $Neurotweak_Require_Skill_ID, $Neurotweak_Require_Skill_Level, $Neurotweak_Require_Ship);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				$r+=1;
			}
		}

		echo "Imported $r Neurotweaks<br/>";
	}
	
if (isset($GID['NT_Effects'])) {
	//
	//
	//	Neurotweak Effects
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['NT_Effects'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table

		$Query = "TRUNCATE Neurotweak_Effects";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Neurotweak_ID = mysql_real_escape_string($row[0], $conn);
				$Neurotweak_Mod_ID = mysql_real_escape_string($row[1], $conn);
				$Neurotweak_Mod_Value = mysql_real_escape_string($row[3], $conn)+0;
				$Neurotweak_Mod_Length = mysql_real_escape_string($row[4], $conn)+0;
				$Neurotweak_Mod_After = mysql_real_escape_string($row[5], $conn)+0;

				$Query = "INSERT INTO Neurotweak_Effects (Excel_Mod_ID, Neurotweak_ID, Value, Length, After) VALUES ($Neurotweak_ID, $Neurotweak_Mod_ID, $Neurotweak_Mod_Value, $Neurotweak_Mod_Length, $Neurotweak_Mod_After);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				$r+=1;
			}
		}

		echo "Imported $r Neurotweak effects<br/>";
	}
}

if (isset($_POST['Bases'])) {
	//
	//	Bases
	//
	// Fetch data from Excel
	if (isset($GID['Bases'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Bases'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Bases";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Attached = "'" . mysql_real_escape_string($row[2], $conn) . "'";
				$Base_Tech = mysql_real_escape_string($row[3], $conn);
				$Base_Aug_Slots = mysql_real_escape_string($row[4], $conn);
				$Base_Weapon_Slots = mysql_real_escape_string($row[5], $conn);
				$Base_Hull = mysql_real_escape_string($row[6], $conn)+0;

				$Base_Resistance_Laser = mysql_real_escape_string($row[7], $conn);
				$Base_Resistance_Energy = mysql_real_escape_string($row[8], $conn);
				$Base_Resistance_Heat = mysql_real_escape_string($row[9], $conn);
				$Base_Resistance_Physical = mysql_real_escape_string($row[10], $conn);
				$Base_Resistance_Radiation = mysql_real_escape_string($row[11], $conn);
				$Base_Resistance_Surgical = mysql_real_escape_string($row[12], $conn);
				$Base_Resistance_Mining = mysql_real_escape_string($row[13], $conn);
				$Base_Resistance_Transference = mysql_real_escape_string($row[14], $conn);
				
				$Base_Soak_Laser = mysql_real_escape_string($row[15], $conn);
				$Base_Soak_Energy = mysql_real_escape_string($row[16], $conn);
				$Base_Soak_Heat = mysql_real_escape_string($row[17], $conn);
				$Base_Soak_Physical = mysql_real_escape_string($row[18], $conn);
				$Base_Soak_Radiation = mysql_real_escape_string($row[19], $conn);
				$Base_Soak_Surgical = mysql_real_escape_string($row[20], $conn);
				$Base_Soak_Mining = mysql_real_escape_string($row[21], $conn);
				$Base_Soak_Transference = mysql_real_escape_string($row[22], $conn);
				
				$Base_Size = mysql_real_escape_string($row[23], $conn);
				$Base_Visibility = mysql_real_escape_string($row[24], $conn);
				$Base_Weight = mysql_real_escape_string($row[25], $conn);
				$Base_Inbuilt_Electricity = mysql_real_escape_string($row[26], $conn);
				$Base_Diameter = mysql_real_escape_string($row[27], $conn);
				$Base_Require_Skill_ID = mysql_real_escape_string($row[28], $conn)+0;
				$Base_Require_Skill_Level = mysql_real_escape_string($row[30], $conn)+0;

				$Query = "INSERT INTO Bases (Base_ID, Name, Attached, Tech, Aug_Slots, Weapon_Slots, Hull, Resistance_Laser, Resistance_Energy, Resistance_Heat, Resistance_Physical, Resistance_Radiation, Resistance_Surgical, Resistance_Mining, Resistance_Transference, Soak_Laser, Soak_Energy, Soak_Heat, Soak_Physical, Soak_Radiation, Soak_Surgical, Soak_Mining, Soak_Transference, Size, Visibility, Weight, Inbuilt_Electricity, Diameter, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_ID, $Base_Name, $Base_Attached, $Base_Tech, $Base_Aug_Slots, $Base_Weapon_Slots, $Base_Hull, $Base_Resistance_Laser, $Base_Resistance_Energy, $Base_Resistance_Heat, $Base_Resistance_Radiation, $Base_Resistance_Physical, $Base_Resistance_Surgical, $Base_Resistance_Mining, $Base_Resistance_Transference, $Base_Soak_Laser, $Base_Soak_Energy, $Base_Soak_Heat, $Base_Soak_Radiation, $Base_Soak_Physical, $Base_Soak_Surgical, $Base_Soak_Mining, $Base_Soak_Transference, $Base_Size, $Base_Visibility, $Base_Weight, $Base_Inbuilt_Electricity, $Base_Diameter, $Base_Require_Skill_ID, $Base_Require_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				// , Resistance_Heat, Resistance_Physical, Resistance_Radiation, Resistance_Surgical, Resistance_Mining, Resistance_Transference, Soak_Laser, Soak_Energy, Soak_Heat, Soak_Physical, Soak_Radiation, Soak_Surgical, Soak_Mining, Soak_Transference, Size, Visibility, Weight, Inbuilt_Electricity, Diameter, Require_Skill_ID, Require_Skill_Level
				// , $Base_Resistance_Heat, $Base_Resistance_Physical, $Resistance_Radiation, $Base_Resistance_Surgical, $Base_Resistance_Mining, $Base_Resistance_Transference, $Base_Soak_Laser, $Base_Soak_Energy, $Base_Soak_Heat, $Base_Soak_Physical, $Base_Soak_Surgical, $Base_Soak_Mining, $Base_Soak_Transference, $Base_Size, $Base_Visibility, $Base_Weight, $Base_Inbuilt_Electricity, $Base_Diameter, $Base_Require_Skill_ID, $Base_Require_Skill_Level
				
				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>30 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Mods (Base_ID, Excel_Mod_ID, Value) VALUES ($Base_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Bases and $m base modifications<br/>";
	}
}
if (isset($_POST['Base_Weapons'])) {
	//
	//	Base_Weapons
	//
	// Fetch data from Excel
	if (isset($GID['Base_Weapons'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Weapons'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Weapons";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "") {
				//	ID - Name - ID
				$Base_Weapon_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Weapon_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Weapon_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Weapon_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Weapon_Damage_Low = mysql_real_escape_string($row[4], $conn);
				$Base_Weapon_Damage_High = mysql_real_escape_string($row[5], $conn);
				$Base_Weapon_Projectiles = mysql_real_escape_string($row[6], $conn);
				$Base_Weapon_Damage_Self = mysql_real_escape_string(($row[7]), $conn)+0;
				$Base_Weapon_Range = mysql_real_escape_string($row[8], $conn);
				$Base_Weapon_Recoil = mysql_real_escape_string($row[9], $conn);
				$Base_Weapon_Electricity = mysql_real_escape_string($row[10], $conn);
				$Base_Weapon_Visibility = mysql_real_escape_string($row[11], $conn)+0;
				$Base_Weapon_Visibility_Length = mysql_real_escape_string($row[12], $conn)+0;
				$Base_Weapon_Weight = mysql_real_escape_string($row[13]+0, $conn);
				$Base_Weapon_Ethereal = mysql_real_escape_string($row[14], $conn);
				$Base_Weapon_Type = "'" . mysql_real_escape_string($row[15], $conn) . "'";
				$Base_Weapon_Req_Skill_ID = mysql_real_escape_string($row[16], $conn)+0;
				$Base_Weapon_Req_Skill_Level = mysql_real_escape_string($row[18], $conn)+0;

				$Query = "INSERT INTO Base_Weapons (Base_Weapon_ID, Name, Tech, Size, Projectiles, Damage_Min, Damage_Max, Damage_Self, W_Range, Recoil, Electricity, Visibility, Vis_Length, Weight, Ethereal, Type, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Weapon_ID, $Base_Weapon_Name, $Base_Weapon_Tech, $Base_Weapon_Size, $Base_Weapon_Projectiles, $Base_Weapon_Damage_Low, $Base_Weapon_Damage_High, $Base_Weapon_Damage_Self, $Base_Weapon_Range, $Base_Weapon_Recoil, $Base_Weapon_Electricity, $Base_Weapon_Visibility, $Base_Weapon_Visibility_Length, $Base_Weapon_Weight, $Base_Weapon_Ethereal, $Base_Weapon_Type, $Base_Weapon_Req_Skill_ID, $Base_Weapon_Req_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>18 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Weapon_Mods (Base_Weapon_ID, Excel_Mod_ID, Value) VALUES ($Base_Weapon_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base weapons<br/>";
	}
}
if (isset($_POST['Base_Shields'])) {
	//
	//	Base_Shields
	//
	// Fetch data from Excel
	if (isset($GID['Base_Shields'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Shields'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Shields";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Shield_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Shield_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Shield_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Shield_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Shield_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Shield_Shield_Bank = mysql_real_escape_string($row[4], $conn);
				$Base_Shield_Shield_Regeneration = mysql_real_escape_string($row[5], $conn);
				$Base_Shield_Regeneration_Electricity = mysql_real_escape_string($row[6], $conn);
				$Base_Shield_Electricity = mysql_real_escape_string($row[7], $conn);
				$Base_Shield_Visbility = mysql_real_escape_string($row[8], $conn);
				$Base_Shield_Weight = mysql_real_escape_string($row[9], $conn);
				$Base_Shield_Req_Skill_ID = mysql_real_escape_string($row[10], $conn)+0;
				$Base_Shield_Req_Skill_Level = mysql_real_escape_string($row[12], $conn)+0;
				
				$Query = "INSERT INTO Base_Shields (Base_Shield_ID, Name, Tech, Size, Bank, Regeneration, Reg_Elec, Electricity, Visibility, Weight, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Shield_ID, $Base_Shield_Name, $Base_Shield_Tech, $Base_Shield_Size, $Base_Shield_Shield_Bank, $Base_Shield_Shield_Regeneration, $Base_Shield_Regeneration_Electricity, $Base_Shield_Electricity, $Base_Shield_Visbility, $Base_Shield_Weight, $Base_Shield_Req_Skill_ID, $Base_Shield_Req_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>12 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Shield_Mods (Base_Shield_ID, Excel_Mod_ID, Value) VALUES ($Base_Shield_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base shields and $m base shield modifications<br/>";
	}
}
if (isset($_POST['Base_Energies'])) {
	//
	//	Base_Energies
	//
	// Fetch data from Excel
	if (isset($GID['Base_Energies'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Energies'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Energies";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Energy_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Energy_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Energy_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Energy_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Energy_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Energy_Bank_Size = mysql_real_escape_string($row[4], $conn);
				$Base_Energy_Regeneration = mysql_real_escape_string($row[5], $conn);
				$Base_Energy_Visibility = mysql_real_escape_string($row[6], $conn);
				$Base_Energy_Weight = mysql_real_escape_string($row[7], $conn);
				$Base_Energy_Fuel_Amount = mysql_real_escape_string($row[8], $conn)+0;
				$Base_Energy_Fuel_Type = "'" . mysql_real_escape_string($row[9], $conn) . "'";
				$Base_Energy_Fuel_Tick = mysql_real_escape_string($row[10], $conn)+0;
				$Base_Energy_Requirement_Skill_ID = mysql_real_escape_string($row[11], $conn)+0;
				$Base_Energy_Requirement_Skill_Level = mysql_real_escape_string($row[13], $conn)+0;

				$Query = "INSERT INTO Base_Energies (Base_Energy_ID, Name, Tech, Size, Energy_Bank, Electricity, Visibility, Weight, Fuel_Amount, Fuel_Type, Fuel_Tick, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Energy_ID, $Base_Energy_Name, $Base_Energy_Tech, $Base_Energy_Size, $Base_Energy_Bank_Size, $Base_Energy_Regeneration, $Base_Energy_Visibility, $Base_Energy_Weight, $Base_Energy_Fuel_Amount, $Base_Energy_Fuel_Type, $Base_Energy_Fuel_Tick, $Base_Energy_Requirement_Skill_ID, $Base_Energy_Requirement_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>10 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Energy_Mods (Base_Energy_ID, Excel_Mod_ID, Value) VALUES ($Base_Energy_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Base Energies and $m base energy modifications<br/>";
	}
}
if (isset($_POST['Base_Radars'])) {
	//
	//	Base_Radars
	//
	// Fetch data from Excel
	if (isset($GID['Base_Radars'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Radars'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Radars";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Radar_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Radar_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Radar_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Radar_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Radar_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Radar_Vision = mysql_real_escape_string($row[4], $conn);
				$Base_Radar_Detection = mysql_real_escape_string($row[5], $conn);
				$Base_Radar_Electricity = mysql_real_escape_string($row[6], $conn);
				$Base_Radar_Visibility = mysql_real_escape_string($row[7], $conn);
				$Base_Radar_Weight = mysql_real_escape_string($row[8], $conn);
				$Base_Radar_Required_Skill_ID = mysql_real_escape_string($row[9], $conn)+0;
				$Base_Radar_Required_Skill_Level = mysql_real_escape_string($row[11], $conn)+0;


				$Query = "INSERT INTO Base_Radars (Base_Radar_ID, Name, Tech, Size, Vision, Detection, Electricity, Visibility, Weight, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Radar_ID, $Base_Radar_Name, $Base_Radar_Tech, $Base_Radar_Size, $Base_Radar_Vision, $Base_Radar_Detection, $Base_Radar_Electricity, $Base_Radar_Visibility, $Base_Radar_Weight, $Base_Radar_Required_Skill_ID, $Base_Radar_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>11 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Radar_Mods (Base_Radar_ID, Excel_Mod_ID, Value) VALUES ($Base_Radar_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Base Radars and $m base radar modifications<br/>";
	}
}
if (isset($_POST['Base_Shield_Chargers'])) {
	//
	//	Shield Chargers
	//
	// Fetch data from Excel
	if (isset($GID['Base_Shield_Chargers'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Shield_Chargers'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Shield_Chargers";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Shield_Charger_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Shield_Charger_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Shield_Charger_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Shield_Charger_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Shield_Charger_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Shield_Charger_Shield_Recharge = mysql_real_escape_string($row[4], $conn);
				$Base_Shield_Charger_Electricity = mysql_real_escape_string($row[5], $conn);
				$Base_Shield_Charger_Weight = mysql_real_escape_string($row[6], $conn);
				$Base_Shield_Charger_Required_Skill_ID = mysql_real_escape_string($row[7], $conn)+0;
				$Base_Shield_Charger_Required_Skill_Level = mysql_real_escape_string($row[9], $conn)+0;

				$Query = "INSERT INTO Base_Shield_Chargers (Base_Shield_Charger_ID, Name, Tech, Size, Regeneration, Electricity, Weight, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Shield_Charger_ID, $Base_Shield_Charger_Name, $Base_Shield_Charger_Tech, $Base_Shield_Charger_Size, $Base_Shield_Charger_Shield_Recharge, $Base_Shield_Charger_Electricity, $Base_Shield_Charger_Weight, $Base_Shield_Charger_Required_Skill_ID, $Base_Shield_Charger_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>9 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Shield_Charger_Mods (Base_Shield_Charger_ID, Excel_Mod_ID, Value) VALUES ($Base_Shield_Charger_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base shield chargers and $m base shield charger modifications<br/>";
	}
}
if (isset($_POST['Base_Capacitors'])) {
	//
	//	Base_Capacitors
	//
	// Fetch data from Excel
	if (isset($GID['Base_Capacitors'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Capacitors'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Capacitors";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Capacitor_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Capacitor_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Capacitor_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Capacitor_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Capacitor_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Capacitor_Shield_Boost = mysql_real_escape_string($row[4], $conn);
				$Base_Capacitor_Energy_Boost = mysql_real_escape_string($row[5], $conn);
				$Base_Capacitor_Weight = mysql_real_escape_string($row[6], $conn);
				$Base_Capacitor_Required_Skill_ID = mysql_real_escape_string($row[7], $conn)+0;
				$Base_Capacitor_Required_Skill_Level = mysql_real_escape_string($row[9], $conn)+0;

				$Query = "INSERT INTO Base_Capacitors (Base_Capacitor_ID, Name, Tech, Size, Weight, Shield_Boost, Energy_Boost, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Capacitor_ID, $Base_Capacitor_Name, $Base_Capacitor_Tech, $Base_Capacitor_Size, $Base_Capacitor_Weight, $Base_Capacitor_Shield_Boost, $Base_Capacitor_Energy_Boost, $Base_Capacitor_Required_Skill_ID, $Base_Capacitor_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>9 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Capacitor_Mods (Base_Capacitor_ID, Excel_Mod_ID, Value) VALUES ($Base_Capacitor_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Base Capacitors and $m base capacitor modifications<br/>";
	}
}
if (isset($_POST['Base_Solar_Panels'])) {
	//
	//	Solar Panels
	//
	// Fetch data from Excel
	if (isset($GID['Base_Solar_Panels'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Solar_Panels'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Solar_Panels";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Solar_Panel_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Solar_Panel_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Solar_Panel_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Solar_Panel_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Solar_Panel_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Solar_Panel_Electricity = mysql_real_escape_string($row[4], $conn);
				$Base_Solar_Panel_Weight = mysql_real_escape_string($row[5], $conn);
				$Base_Solar_Required_Skill_ID = mysql_real_escape_string($row[6], $conn)+0;
				$Base_Solar_Required_Skill_Level = mysql_real_escape_string($row[8], $conn)+0;

				$Query = "INSERT INTO Base_Solar_Panels (Base_Solar_Panel_ID, Name, Tech, Size, Electricity, Weight, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Solar_Panel_ID, $Base_Solar_Panel_Name, $Base_Solar_Panel_Tech, $Base_Solar_Panel_Size, $Base_Solar_Panel_Electricity, $Base_Solar_Panel_Weight, $Base_Solar_Required_Skill_ID, $Base_Solar_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>8 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Solar_Panel_Mods (Base_Solar_Panel_ID, Excel_Mod_ID, Value) VALUES ($Base_Solar_Panel_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r solar panels and $m solar panel modifications<br/>";
	}
}
if (isset($_POST['Base_Overloaders'])) {
	//
	//	Base_Overloaders
	//
	// Fetch data from Excel
	if (isset($GID['Base_Overloaders'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Overloaders'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Overloaders";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Overloader_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Overloader_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Overloader_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Overloader_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Overloader_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Overloader_Electricity = mysql_real_escape_string($row[4], $conn);
				$Base_Overloader_Fail = mysql_real_escape_string($row[5], $conn);
				$Base_Overloader_Weight = mysql_real_escape_string($row[6], $conn);
				$Base_Overloader_Visibility = mysql_real_escape_string($row[7], $conn);
				$Base_Overloader_Req_Skill_ID = mysql_real_escape_string($row[8], $conn)+0;
				$Base_Overloader_Req_Skill_Level = mysql_real_escape_string($row[10], $conn)+0;
				
				$Query = "INSERT INTO Base_Overloaders (Base_Overloader_ID, Name, Weight, Tech, Size, Fail, Visibility, Electricity, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Overloader_ID, $Base_Overloader_Name, $Base_Overloader_Weight, $Base_Overloader_Tech, $Base_Overloader_Size, $Base_Overloader_Fail, $Base_Overloader_Visibility, $Base_Overloader_Electricity, $Base_Overloader_Req_Skill_ID, $Base_Overloader_Req_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>10 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Overloader_Mods (Base_Overloader_ID, Excel_Mod_ID, Value) VALUES ($Base_Overloader_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . " Query: $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Base Overloaders and $m base overloader modifications<br/>";
	}
}

if (isset($_POST['Base_Aura_Generators'])) {
	//
	//	Base_Aura_Generators
	//
	// Fetch data from Excel
	if (isset($GID['Base_Aura_Generators'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Aura_Generators'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Aura_Generators";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Aura_Generator_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Aura_Generator_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Aura_Generator_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Aura_Generator_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Aura_Generator_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Aura_Generator_Electricity = mysql_real_escape_string($row[4], $conn);
				$Base_Aura_Generator_Visibility = mysql_real_escape_string($row[5], $conn);
				$Base_Aura_Generator_Weight = mysql_real_escape_string($row[6], $conn);

				$Base_Aura_Generator_ID_1 = mysql_real_escape_string($row[7], $conn)+0;
				//
				$Base_Aura_Generator_Value_1 = mysql_real_escape_string($row[9], $conn)+0;
				$Base_Aura_Generator_Range_1 = mysql_real_escape_string($row[10], $conn)+0;
				$Base_Aura_Generator_Cycle_1 = mysql_real_escape_string($row[11], $conn)+0;
				$Base_Aura_Generator_Duration_1 = mysql_real_escape_string($row[12], $conn)+0;
				$Base_Aura_Generator_Allies_1 = "'" . mysql_real_escape_string($row[13], $conn) . "'";
				$Base_Aura_Generator_Enemies_1 = "'" . mysql_real_escape_string($row[14], $conn) . "'";
				$Base_Aura_Generator_Neutral_1 = "'" . mysql_real_escape_string($row[15], $conn) . "'";
				$Base_Aura_Generator_Self_1 = "'" . mysql_real_escape_string($row[16], $conn) . "'";
				$Base_Aura_Generator_Tweakable_1 = "'" . mysql_real_escape_string($row[17], $conn) . "'";
				$Base_Aura_Generator_Capship_1 = "'" . mysql_real_escape_string($row[18], $conn) . "'";
				$Base_Aura_Generator_Delay_1 = mysql_real_escape_string($row[19], $conn)+0;

				$Base_Aura_Generator_ID_2 = mysql_real_escape_string($row[20], $conn)+0;
				//
				$Base_Aura_Generator_Value_2 = mysql_real_escape_string($row[22], $conn)+0;
				$Base_Aura_Generator_Range_2 = mysql_real_escape_string($row[23], $conn)+0;
				$Base_Aura_Generator_Cycle_2 = mysql_real_escape_string($row[24], $conn)+0;
				$Base_Aura_Generator_Duration_2 = mysql_real_escape_string($row[25], $conn)+0;
				$Base_Aura_Generator_Allies_2 = "'" . mysql_real_escape_string($row[26], $conn) . "'";
				$Base_Aura_Generator_Enemies_2 = "'" . mysql_real_escape_string($row[27], $conn) . "'";
				$Base_Aura_Generator_Neutral_2 = "'" . mysql_real_escape_string($row[28], $conn) . "'";
				$Base_Aura_Generator_Self_2 = "'" . mysql_real_escape_string($row[29], $conn) . "'";
				$Base_Aura_Generator_Tweakable_2 = "'" . mysql_real_escape_string($row[30], $conn) . "'";
				$Base_Aura_Generator_Capship_2 = "'" . mysql_real_escape_string($row[31], $conn) . "'";
				$Base_Aura_Generator_Delay_2 = mysql_real_escape_string($row[32], $conn)+0;

				$Base_Aura_Generator_ID_3 = mysql_real_escape_string($row[33], $conn)+0;
				//
				$Base_Aura_Generator_Value_3 = mysql_real_escape_string($row[35], $conn)+0;
				$Base_Aura_Generator_Range_3 = mysql_real_escape_string($row[36], $conn)+0;
				$Base_Aura_Generator_Cycle_3 = mysql_real_escape_string($row[37], $conn)+0;
				$Base_Aura_Generator_Duration_3 = mysql_real_escape_string($row[38], $conn)+0;
				$Base_Aura_Generator_Allies_3 = "'" . mysql_real_escape_string($row[39], $conn) . "'";
				$Base_Aura_Generator_Enemies_3 = "'" . mysql_real_escape_string($row[40], $conn) . "'";
				$Base_Aura_Generator_Neutral_3 = "'" . mysql_real_escape_string($row[41], $conn) . "'";
				$Base_Aura_Generator_Self_3 = "'" . mysql_real_escape_string($row[42], $conn) . "'";
				$Base_Aura_Generator_Tweakable_3 = "'" . mysql_real_escape_string($row[43], $conn) . "'";
				$Base_Aura_Generator_Capship_3 = "'" . mysql_real_escape_string($row[44], $conn) . "'";
				$Base_Aura_Generator_Delay_3 = mysql_real_escape_string($row[45], $conn)+0;

				$Base_Aura_Generator_ID_4 = mysql_real_escape_string($row[46], $conn)+0;
				//
				$Base_Aura_Generator_Value_4 = mysql_real_escape_string($row[48], $conn)+0;
				$Base_Aura_Generator_Range_4 = mysql_real_escape_string($row[49], $conn)+0;
				$Base_Aura_Generator_Cycle_4 = mysql_real_escape_string($row[50], $conn)+0;
				$Base_Aura_Generator_Duration_4 = mysql_real_escape_string($row[51], $conn)+0;
				$Base_Aura_Generator_Allies_4 = "'" . mysql_real_escape_string($row[52], $conn) . "'";
				$Base_Aura_Generator_Enemies_4 = "'" . mysql_real_escape_string($row[53], $conn) . "'";
				$Base_Aura_Generator_Neutral_4 = "'" . mysql_real_escape_string($row[54], $conn) . "'";
				$Base_Aura_Generator_Self_4 = "'" . mysql_real_escape_string($row[55], $conn) . "'";
				$Base_Aura_Generator_Tweakable_4 = "'" . mysql_real_escape_string($row[56], $conn) . "'";
				$Base_Aura_Generator_Capship_4 = "'" . mysql_real_escape_string($row[57], $conn) . "'";
				$Base_Aura_Generator_Delay_4 = mysql_real_escape_string($row[58], $conn)+0;

				$Base_Aura_Generator_ID_5 = mysql_real_escape_string($row[59], $conn)+0;
				//
				$Base_Aura_Generator_Value_5 = mysql_real_escape_string($row[61], $conn)+0;
				$Base_Aura_Generator_Range_5 = mysql_real_escape_string($row[62], $conn)+0;
				$Base_Aura_Generator_Cycle_5 = mysql_real_escape_string($row[63], $conn)+0;
				$Base_Aura_Generator_Duration_5 = mysql_real_escape_string($row[64], $conn)+0;
				$Base_Aura_Generator_Allies_5 = "'" . mysql_real_escape_string($row[65], $conn) . "'";
				$Base_Aura_Generator_Enemies_5 = "'" . mysql_real_escape_string($row[66], $conn) . "'";
				$Base_Aura_Generator_Neutral_5 = "'" . mysql_real_escape_string($row[67], $conn) . "'";
				$Base_Aura_Generator_Self_5 = "'" . mysql_real_escape_string($row[68], $conn) . "'";
				$Base_Aura_Generator_Tweakable_5 = "'" . mysql_real_escape_string($row[69], $conn) . "'";
				$Base_Aura_Generator_Capship_5 = "'" . mysql_real_escape_string($row[70], $conn) . "'";
				$Base_Aura_Generator_Delay_5 = mysql_real_escape_string($row[71], $conn)+0;

				$Query = "INSERT INTO Base_Aura_Generators (Base_Aura_Generator_ID, Name, Tech, Size, Electricity, Visibility, Weight, Field_ID_1, Field_Value_1, Field_Range_1, Field_Cycle_1, Field_Duration_1, Field_Allies_1, Field_Enemies_1, Field_Neutral_1, Field_Self_1, Field_Tweakable_1, Capship_1, Delay_1, Field_ID_2, Field_Value_2, Field_Range_2, Field_Cycle_2, Field_Duration_2, Field_Allies_2, Field_Enemies_2, Field_Neutral_2, Field_Self_2, Field_Tweakable_2, Capship_2, Delay_2, Field_ID_3, Field_Value_3, Field_Range_3, Field_Cycle_3, Field_Duration_3, Field_Allies_3, Field_Enemies_3, Field_Neutral_3, Field_Self_3, Field_Tweakable_3, Capship_3, Delay_3, Field_ID_4, Field_Value_4, Field_Range_4, Field_Cycle_4, Field_Duration_4, Field_Allies_4, Field_Enemies_4, Field_Neutral_4, Field_Self_4, Field_Tweakable_4, Capship_4, Delay_4, Field_ID_5, Field_Value_5, Field_Range_5, Field_Cycle_5, Field_Duration_5, Field_Allies_5, Field_Enemies_5, Field_Neutral_5, Field_Self_5, Field_Tweakable_5, Capship_5, Delay_5) VALUES ($Base_Aura_Generator_ID, $Base_Aura_Generator_Name, $Base_Aura_Generator_Tech, $Base_Aura_Generator_Size, $Base_Aura_Generator_Electricity, $Base_Aura_Generator_Visibility, $Base_Aura_Generator_Weight, $Base_Aura_Generator_ID_1, $Base_Aura_Generator_Value_1, $Base_Aura_Generator_Range_1, $Base_Aura_Generator_Cycle_1, $Base_Aura_Generator_Duration_1, $Base_Aura_Generator_Allies_1, $Base_Aura_Generator_Enemies_1, $Base_Aura_Generator_Neutral_1, $Base_Aura_Generator_Self_1, $Base_Aura_Generator_Tweakable_1, $Base_Aura_Generator_Capship_1, $Base_Aura_Generator_Delay_1, $Base_Aura_Generator_ID_2, $Base_Aura_Generator_Value_2, $Base_Aura_Generator_Range_2, $Base_Aura_Generator_Cycle_2, $Base_Aura_Generator_Duration_2, $Base_Aura_Generator_Allies_2, $Base_Aura_Generator_Enemies_2, $Base_Aura_Generator_Neutral_2, $Base_Aura_Generator_Self_2, $Base_Aura_Generator_Tweakable_2, $Base_Aura_Generator_Capship_2, $Base_Aura_Generator_Delay_2, $Base_Aura_Generator_ID_3, $Base_Aura_Generator_Value_3, $Base_Aura_Generator_Range_3, $Base_Aura_Generator_Cycle_3, $Base_Aura_Generator_Duration_3, $Base_Aura_Generator_Allies_3, $Base_Aura_Generator_Enemies_3, $Base_Aura_Generator_Neutral_3, $Base_Aura_Generator_Self_3, $Base_Aura_Generator_Tweakable_3, $Base_Aura_Generator_Capship_3, $Base_Aura_Generator_Delay_3, $Base_Aura_Generator_ID_4, $Base_Aura_Generator_Value_4, $Base_Aura_Generator_Range_4, $Base_Aura_Generator_Cycle_4, $Base_Aura_Generator_Duration_4, $Base_Aura_Generator_Allies_4, $Base_Aura_Generator_Enemies_4, $Base_Aura_Generator_Neutral_4, $Base_Aura_Generator_Self_4, $Base_Aura_Generator_Tweakable_4, $Base_Aura_Generator_Capship_4, $Base_Aura_Generator_Delay_4, $Base_Aura_Generator_ID_5, $Base_Aura_Generator_Value_5, $Base_Aura_Generator_Range_5, $Base_Aura_Generator_Cycle_5, $Base_Aura_Generator_Duration_5, $Base_Aura_Generator_Allies_5, $Base_Aura_Generator_Enemies_5, $Base_Aura_Generator_Neutral_5, $Base_Aura_Generator_Self_5, $Base_Aura_Generator_Tweakable_5, $Base_Aura_Generator_Capship_5, $Base_Aura_Generator_Delay_5);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>71 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Aura_Generator_Mods (Base_Aura_Generator_ID, Excel_Mod_ID, Value) VALUES ($Base_Aura_Generator_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}

				$r+=1;
			}
		}
		echo "Imported $r aura generators and $m aura generator modifications<br/>";
	}
}
if (isset($_POST['Base_Diffusers'])) {
	//
	//	Base_Diffusers
	//
	// Fetch data from Excel
	if (isset($GID['Base_Diffusers'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Diffusers'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Diffusers";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Diffuser_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Diffuser_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Diffuser_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Diffuser_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Diffuser_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Diffuser_Failure = mysql_real_escape_string(str_replace("%","",$row[4]), $conn);
				$Base_Diffuser_Electricity = mysql_real_escape_string($row[5], $conn);
				$Base_Diffuser_Visibility = mysql_real_escape_string($row[6], $conn);
				$Base_Diffuser_Weight = mysql_real_escape_string($row[7], $conn);
				
				$Base_Diffuser_Laser = mysql_real_escape_string($row[8], $conn) + 0;
				$Base_Diffuser_Energy = mysql_real_escape_string($row[9], $conn) + 0;
				$Base_Diffuser_Heat = mysql_real_escape_string($row[10], $conn) + 0;
				$Base_Diffuser_Physical = mysql_real_escape_string($row[11], $conn) + 0;
				$Base_Diffuser_Radiation = mysql_real_escape_string($row[12], $conn) + 0;
				$Base_Diffuser_Surgical = mysql_real_escape_string($row[13], $conn) + 0;
				$Base_Diffuser_Mining = mysql_real_escape_string($row[14], $conn) + 0;
				$Base_Diffuser_Transference = mysql_real_escape_string($row[15], $conn) + 0;
				
				$Base_Diffuser_Required_Skill_ID = mysql_real_escape_string($row[15], $conn) + 0;
				$Base_Diffuser_Required_Skill_Level = mysql_real_escape_string($row[17], $conn) + 0;
				
				$Query = "INSERT INTO Base_Diffusers (Base_Diffuser_ID, Name, Tech, Size, Failure, Electricity, Visibility, Weight, Resistance_Laser, Resistance_Energy, Resistance_Heat, Resistance_Physical, Resistance_Radiation, Resistance_Surgical, Resistance_Mining, Resistance_Transference, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Diffuser_ID, $Base_Diffuser_Name, $Base_Diffuser_Tech, $Base_Diffuser_Size, $Base_Diffuser_Failure, $Base_Diffuser_Electricity, $Base_Diffuser_Visibility, $Base_Diffuser_Weight, $Base_Diffuser_Laser, $Base_Diffuser_Energy, $Base_Diffuser_Heat, $Base_Diffuser_Physical, $Base_Diffuser_Radiation, $Base_Diffuser_Surgical, $Base_Diffuser_Mining, $Base_Diffuser_Transference, $Base_Diffuser_Required_Skill_ID, $Base_Diffuser_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
				
				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>17 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);
						
						$Query = "INSERT INTO Base_Diffuser_Mods (Base_Diffuser_ID, Excel_Mod_ID, Value) VALUES ($Base_Diffuser_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base diffusers and $m base diffuser modifications<br/>";
	}
}

	if (isset($_POST['Base_Augmenters'])) {
	//
	//	Base_Augmenters
	//
	// Fetch data from Excel
	if (isset($GID['Base_Augmenters'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Augmenters'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Augmenters";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Augmenter_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Augmenter_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Augmenter_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Augmenter_Weight = "'" . mysql_real_escape_string($row[2], $conn) . "'";
				$Base_Augmenter_Tech = "'" . mysql_real_escape_string($row[3], $conn) . "'";
				$Base_Augmenter_Size = "'" . mysql_real_escape_string($row[4], $conn) . "'";
				$Base_Augmenter_Req_Skill_ID = mysql_real_escape_string($row[5], $conn)+0;
				$Base_Augmenter_Req_Skill_Level = mysql_real_escape_string($row[7], $conn)+0;

				$Query = "INSERT INTO Base_Augmenters (Base_Augmenter_ID, Name, Weight, Tech, Size, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Augmenter_ID, $Base_Augmenter_Name, $Base_Augmenter_Weight, $Base_Augmenter_Tech, $Base_Augmenter_Size, $Base_Augmenter_Req_Skill_ID, $Base_Augmenter_Req_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error());

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>7 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Augmenter_Mods (Base_Augmenter_ID, Excel_Mod_ID, Value) VALUES ($Base_Augmenter_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error());
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r Base Augmenters and $m base augmenter modifications<br/>";
	}
}

if (isset($_POST['Base_Hull_Expanders'])) {
	//
	//	Hull Expanders
	//
	// Fetch data from Excel
	if (isset($GID['Base_Hull_Expanders'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Hull_Expanders'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Hull_Expanders";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Hull_Expander_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Hull_Expander_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Hull_Expander_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Hull_Expander_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Hull_Expander_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Hull_Expander_Capacity = mysql_real_escape_string($row[4], $conn);
				$Base_Hull_Expander_Weight = mysql_real_escape_string($row[5], $conn);
				$Base_Hull_Expander_Required_Skill_ID = mysql_real_escape_string($row[6], $conn)+0;
				$Base_Hull_Expander_Required_Skill_Level = mysql_real_escape_string($row[8], $conn)+0;

				$Query = "INSERT INTO Base_Hull_Expanders (Base_Hull_Expander_ID, Name, Tech, Size, Extra_Space, Weight, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Hull_Expander_ID, $Base_Hull_Expander_Name, $Base_Hull_Expander_Tech, $Base_Hull_Expander_Size, $Base_Hull_Expander_Capacity, $Base_Hull_Expander_Weight, $Base_Hull_Expander_Required_Skill_ID, $Base_Hull_Expander_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>8 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Hull_Expander_Mods (Base_Hull_Expander_ID, Excel_Mod_ID, Value) VALUES ($Base_Hull_Expander_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base hull expanders and $m base hull expander modifications<br/>";
	}
}

if (isset($_POST['Base_Extractors'])) {
	//
	//	Hull Expanders
	//
	// Fetch data from Excel
	if (isset($GID['Base_Extractors'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Extractors'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Extractors";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Extractor_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Extractor_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Extractor_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Extractor_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Extractor_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Extractor_Commodity = "'" . mysql_real_escape_string($row[4], $conn) . "'";
				$Base_Extractor_Extraction_Rate = mysql_real_escape_string($row[5], $conn);
				$Base_Extractor_Weight = mysql_real_escape_string($row[6], $conn);
				$Base_Extractor_Workers = mysql_real_escape_string($row[7], $conn)+0;
				$Required_Commodity_1 = "'" . mysql_real_escape_string($row[8], $conn) . "'";
				$Required_Amount_1 = mysql_real_escape_string($row[9], $conn)+0;
				$Required_Commodity_2 = "'" . mysql_real_escape_string($row[10], $conn) . "'";
				$Required_Amount_2 = mysql_real_escape_string($row[11], $conn)+0;
				$Required_Commodity_3 = "'" . mysql_real_escape_string($row[12], $conn) . "'";
				$Required_Amount_3 = mysql_real_escape_string($row[13], $conn)+0;
				$Required_Commodity_4 = "'" . mysql_real_escape_string($row[14], $conn) . "'";
				$Required_Amount_4 = mysql_real_escape_string($row[15], $conn)+0;
				$Required_Commodity_5 = "'" . mysql_real_escape_string($row[16], $conn) . "'";
				$Required_Amount_5 = mysql_real_escape_string($row[17], $conn)+0;
				$Base_Extractor_Required_Skill_ID = mysql_real_escape_string($row[18], $conn)+0;
				$Base_Extractor_Required_Skill_Level = mysql_real_escape_string($row[20], $conn)+0;

				$Query = "INSERT INTO Base_Extractors (Base_Extractor_ID, Name, Tech, Size, Commodity, Extraction_Rate, Weight, Workers, `Required Commodity 1`, `Required Amount 1`, `Required Commodity 2`, `Required Amount 2`, `Required Commodity 3`, `Required Amount 3`, `Required Commodity 4`, `Required Amount 4`, `Required Commodity 5`, `Required Amount 5`, Require_Skill_ID, Require_Skill_Level) VALUES ($Base_Extractor_ID, $Base_Extractor_Name, $Base_Extractor_Tech, $Base_Extractor_Size, $Base_Extractor_Commodity, $Base_Extractor_Extraction_Rate, $Base_Extractor_Weight, $Base_Extractor_Workers, $Required_Commodity_1, $Required_Amount_1, $Required_Commodity_2, $Required_Amount_2, $Required_Commodity_3, $Required_Amount_3, $Required_Commodity_4, $Required_Amount_4, $Required_Commodity_5, $Required_Amount_5, $Base_Extractor_Required_Skill_ID, $Base_Extractor_Required_Skill_Level);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>20 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Extractor_Mods (Base_Extractor_ID, Excel_Mod_ID, Value) VALUES ($Base_Extractor_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base extractors and $m base extractor modifications<br/>";
	}
}

if (isset($_POST['Base_Overchargers'])) {
	//
	//	Base Overchargers
	//
	// Fetch data from Excel
	if (isset($GID['Base_Overchargers'])) {
		unset($spreadsheet_data);
		if (($handle = fopen($spreadsheet_url . $GID['Base_Overchargers'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$spreadsheet_data[]=$data;
			}
		  fclose($handle);
		} else { die("Problem reading csv"); }

		// Empty table
		$Query = "TRUNCATE Base_Overchargers";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$Query = "TRUNCATE Base_Overcharger_Mods";
		$Result = mysql_query($Query, $conn) or die(mysql_error());

		$r=0;
		$m=0;
		foreach ($spreadsheet_data as $row) {
			$N_Columns = count($row);
			if ($row[0] != "ID" and $row[0] != "" and $row[1] != "Modification ID -->") {
				//	ID - Name - ID
				$Base_Overcharger_ID = mysql_real_escape_string($row[0], $conn);
				$Base_Overcharger_Name = "'" . mysql_real_escape_string($row[1], $conn) . "'";
				$Base_Overcharger_Tech = mysql_real_escape_string($row[2], $conn);
				$Base_Overcharger_Size = mysql_real_escape_string($row[3], $conn);
				$Base_Overcharger_Charge_Required = mysql_real_escape_string($row[4], $conn);
				$Base_Overcharger_Charging_Rate = mysql_real_escape_string($row[5], $conn);
				$Base_Overcharger_Weight = mysql_real_escape_string($row[6], $conn)+0;

				$Query = "INSERT INTO Base_Overchargers (Base_Overcharger_ID, Name, Tech, Size, Charge_Required, Electricity, Weight) VALUES ($Base_Overcharger_ID, $Base_Overcharger_Name, $Base_Overcharger_Tech, $Base_Overcharger_Size, $Base_Overcharger_Charge_Required, $Base_Overcharger_Charging_Rate, $Base_Overcharger_Weight);";
				$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");

				for ($c=0;$c<=$N_Columns;$c++) {
					if (is_numeric($spreadsheet_data[0][$c]) and $c>6 and $row[$c] <> 0) {
						$Mod_Excel_ID = mysql_real_escape_string($spreadsheet_data[0][$c], $conn);
						$Mod_Value = mysql_real_escape_string(str_replace("%","",$row[$c]), $conn);

						$Query = "INSERT INTO Base_Overcharger_Mods (Base_Overcharger_ID, Excel_Mod_ID, Value) VALUES ($Base_Overcharger_ID, $Mod_Excel_ID, $Mod_Value);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/><br/> $Query");
						$m+=1;
					}
				}
				$r+=1;
			}
		}

		echo "Imported $r base overchargers and $m base overcharger modifications<br/>";
	}
}
?>