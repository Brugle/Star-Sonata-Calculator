<?php
$Builds = array();
if (!isset($_SESSION['UserID'])) {
	$_SESSION['UserID'] = 0;
}
$Query = "SELECT * FROM User_Builds WHERE Type = 3 AND User_ID = " . $_SESSION['UserID'];
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Builds[$row['Build_ID']]['Name'] = $row['Name'];
	$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
	$Builds[$row['Build_ID']]['Public'] = $row['Public'];
	$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
	$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
	$Builds[$row['Build_ID']]['Type'] = $row['Type'];
	$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
	$Builds[$row['Build_ID']]['Extra_Workers'] = $row['Extra_Workers'];
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
	$Query = "INSERT INTO User_Builds (User_ID, Name, Public, Character_ID, Disable, Type) VALUES (" . $_SESSION['UserID'] . ",'" . $_POST['NewBuild'] . "', 0, 0, 0, 3)";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	
	unset($Builds);
	$Query = "SELECT * FROM User_Builds WHERE Type = 3 AND User_ID = " . $_SESSION['UserID'];
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Builds[$row['Build_ID']]['Name'] = $row['Name'];
		$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
		$Builds[$row['Build_ID']]['Public'] = $row['Public'];
		$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
		$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
		$Builds[$row['Build_ID']]['Type'] = $row['Type'];
		$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
		$Builds[$row['Build_ID']]['Extra_Workers'] = $row['Extra_Workers'];
	}
}

$Build_ID = $_POST['Build'];
if ($_POST['Real_Temp_Bonus_State'] == "on") {
	$Include_Temp_Bonus = TRUE;
} else {
	$Include_Temp_Bonus = FALSE;
}

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
	$Query = "SELECT * FROM User_Builds WHERE Type = 3 AND User_ID = " . $_SESSION['UserID'];
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Builds[$row['Build_ID']]['Name'] = $row['Name'];
		$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
		$Builds[$row['Build_ID']]['Public'] = $row['Public'];
		$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
		$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
		$Builds[$row['Build_ID']]['Type'] = $row['Type'];
		$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
		$Builds[$row['Build_ID']]['Extra_Workers'] = $row['Extra_Workers'];
	}
	$Build_ID = 0;
	unset($_POST['Build']);
}

$Tech_Limit = 0;
$Disable = FALSE;

//
//	If looking at a build
if ($Build_ID == 0 and isset($_REQUEST['Build_ID'])) {
	$Build_ID = $_REQUEST['Build_ID'];
	
	unset($Builds);
	$Query = "SELECT * FROM User_Builds WHERE Type = 3 AND Build_ID = " . $Build_ID . " or User_ID = " . $_SESSION['UserID'];
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Builds[$row['Build_ID']]['Name'] = $row['Name'];
		$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
		$Builds[$row['Build_ID']]['Public'] = $row['Public'];
		$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
		$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
		$Builds[$row['Build_ID']]['Type'] = $row['Type'];
		$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
		$Builds[$row['Build_ID']]['Extra_Workers'] = $row['Extra_Workers'];
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

//
//	Update build
if (isset($_POST['submit'])) {
	if ($_POST['Build'] != "") {
		if (!is_numeric($_POST['Extra_Workers'])) {
			$_POST['Extra_Workers'] = 0;
		}
		if (empty($_POST['Character'])) {
			echo "Please create / select a character";
			die();
		}
		$Query = "UPDATE User_Builds SET Updated = NULL, Name = '" . $_POST['BuildName'] . "', Character_ID = " . $_POST['Character'] . ", Public = " . $_POST['Public_Build'] . ", Type = " . $_POST['Type'] . ", Disable = " . $_POST['Disable'] . ", Extra_Workers = " . ($_POST['Extra_Workers']+0) . " WHERE Build_ID = $Build_ID AND User_ID = " . $_SESSION['UserID'];
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
		
		unset($Builds);
		$Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_SESSION['UserID'];
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Builds[$row['Build_ID']]['Name'] = $row['Name'];
			$Builds[$row['Build_ID']]['ID'] = $row['Build_ID'];
			$Builds[$row['Build_ID']]['Public'] = $row['Public'];
			$Builds[$row['Build_ID']]['Character_ID'] = $row['Character_ID'];
			$Builds[$row['Build_ID']]['User_ID'] = $row['User_ID'];
			$Builds[$row['Build_ID']]['Type'] = $row['Type'];
			$Builds[$row['Build_ID']]['Disable'] = $row['Disable'];
			$Builds[$row['Build_ID']]['Extra_Workers'] = $row['Extra_Workers'];
		}
		
		//
		//
		//	Delete Items
		$Query = "DELETE FROM User_Build_Items WHERE User_Build_ID = $Build_ID;";
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
		//
		//	Delete Mods
		$Query = "DELETE FROM User_Build_Item_Mods WHERE Build_ID = $Build_ID;";
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
		
		//
		//
		//	Prepear Mod Arrays
		foreach ($Item_Names as $Item => $Items) {
			if (isset($_POST[$Item . '_Slots']) and $_POST[$Item . '_Slots'] > 0) {
				for ($r=1; $r<=$_POST[$Item . '_Slots']; $r++) {
					if (!isset($_POST[$Item.$r.'_Mods'])) {$_POST[$Item.$r.'_Mods']=array();}
				}
			} else {
				if (!isset($_POST[$Item.'_Mods'])) {$_POST[$Item.'_Mods']=array();}
			}
		}
				
		//	
		//
		//	Insert Items and Mod Items in User Build
		foreach ($Item_Names as $Item => $Items) {
			if (isset($_POST[$Item . '_Slots']) and $_POST[$Item . '_Slots'] > 0) {
				for ($r=1; $r<=$_POST[$Item . '_Slots']; $r++) {
					if ($_POST[$Item.$r] != "") {
						$Query = "INSERT INTO User_Build_Items (User_Build_ID, Item_Type, Item_ID, Amount) VALUES ($Build_ID, '" . $Item . $r . "', ".$_POST[$Item.$r].", '" . ($_REQUEST[$Item . $r.'_Amount'] > 1 ? $_REQUEST[$Item . $r.'_Amount'] : 1) . "');";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
					}
					if (isset($_POST[$Item . $r . '_Mods'])) {
						foreach ($_POST[$Item.$r.'_Mods'] as $Mod_ID => $Mod_Name) {
							$Query = "INSERT INTO User_Build_Item_Mods (Build_ID, Item_Type, Item_Mod_ID) VALUES ($Build_ID, '" . $Item . $r . "', $Mod_Name);";
							$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
						}
					}
				}
			} else {
				if ($_POST[$Item] != "") {
					$Query = "INSERT INTO User_Build_Items (User_Build_ID, Item_Type, Item_ID, Amount) VALUES ($Build_ID, '" . $Item . "', ".$_POST[$Item].", '" . ($_REQUEST[$Item . $r.'_Amount'] > 1 ? $_REQUEST[$Item . $r.'_Amount'] : 1) . "');";
					$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
				}
				if (isset($_POST[$Item . '_Mods'])) {
					foreach ($_POST[$Item . '_Mods'] as $Mod_ID => $Mod_Name) {
						$Query = "INSERT INTO User_Build_Item_Mods (Build_ID, Item_Type, Item_Mod_ID) VALUES ($Build_ID, '" . $Item . "', $Mod_Name);";
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
					}
				}
			}
		}
		
	} else {
		echo "<br/>Didn't have a build.<br/>";
	}
}


//
//
//	Fetch information on all items and mods
foreach ($Item_Names as $Item => $Items) {
	$Query = "SELECT * FROM $Items WHERE Tech >= $Tech_Limit ORDER BY Tech, Name";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		${$Items}[$row[$Item.'_ID']] = $row['Name'];
		foreach ($row as $field_name => $field_value) {
			${$Item . '_Information'}[$row[$Item.'_ID']][$field_name] = $field_value;
		}
	}
}

//
//
//	Fetch Mods
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

//
//
//	Fetch Item Mods
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
		$Item = preg_replace('/[0-9]+/', '', $row['Item_Type']);
		$Build_Items[$row['Item_Type']] = ${$Item."_Information"}[$row['Item_ID']];
		$Build_Items[$row['Item_Type']]['Item_ID'] = $row['Item_ID'];
		$Build_Items[$row['Item_Type']]['Amount'] = $row['Amount'];
		
		$Build_Original_Items[$row['Item_Type']] = ${$Item."_Information"}[$row['Item_ID']];
		$Build_Original_Items[$row['Item_Type']]['Item_ID'] = $row['Item_ID'];
		$Build_Original_Items[$row['Item_Type']]['Item_Type'] = $row['Item_Type'];
		$Build_Original_Items[$row['Item_Type']]['Amount'] = $row['Amount'];
	}
	
	// Store item bonuses
	foreach ($Build_Original_Items as $Item_ID) {
		$r=0;
		$Item = preg_replace('/[0-9]+/', '', $Item_ID['Item_Type']);
		$Query = "SELECT * FROM " . $Item . "_Mods WHERE " . $Item . "_ID = " . $Item_ID['Item_ID'];
		$Result = mysql_query($Query, $conn) or die(mysql_error());
		$Last_Row = mysql_num_rows($Result);
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Build_Original_Items[$Item_ID['Item_Type']]["[Bonus] " . $Mods[$row['Excel_Mod_ID']]['Name']] = $row['Value'];
			$r++;
		}
	}
	
	//
	//
	//	Store Mod IDs	
	$Query = "SELECT * FROM User_Build_Item_Mods WHERE Build_ID = " . $Build_ID;
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Build_Items[$row['Item_Type']]['Mods'][] = $row['Item_Mod_ID'];
		$Build_Original_Items[$row['Item_Type']]['Mods'][] = $row['Item_Mod_ID'];
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
	require("includes/base_mod_calculation.php");
	
	// Fetch number of slots, if a ship is chosen
	// 37 = Weapon hold
	$Base_Weapon_Slots = floor($Build_Items['Base']['Weapon_Slots']);
	$Base_Augmenter_Slots = $Build_Items['Base']['Aug_Slots'];
	$Base_Solar_Panel_Slots = floor($Build_Items['Base']['Diameter']/10);
	$Base_Diffuser_Slots = 3;
	// 227 Capacitor Slot
	$Base_Capacitor_Slots = 1;
	$Base_Overloader_Slots = 1;
	// 228 Hull Expander Slot
	$Base_Hull_Expander_Slots = 5;
	$Base_Extractor_Slots = 10;
	
	// Calculate
	if ($Build_ID != 0 and $Builds[$Build_ID]['Character_ID'] > 0) {
		require("includes/base_calculation.php");
	}
}

?>
<table style="float:left;">
	<tr>
        <td>
            <form action="?module=skills&amp;content=Station_Calculator" method="post" id="formID">
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
                                    <select id="Build" class="select" style="width:310px;" data-placeholder="Please select a Build..." name="Build" class="">
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
            <form action="?module=skills&amp;content=Station_Calculator" method="post" id="formID">
                <fieldset>
                    <table class="innerTable" id="Build_Details_Table">
						<tr>
							<td style="background-image:none;background-color:#FFF;" colspan="4">
								<input class="submit" style="width: 310px; margin-left: 16px; height: 25px;" id="SubmitForm" type="submit" name="submit" value="Save [Calculate]" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?> />
								<br/>
								<br/>
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
                                    <select id="Public_Build" class="select" style="width:310px;" data-placeholder="Please select a build type..." name="Public_Build" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
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
                                    <select id="Disable" class="select" style="width:310px;" data-placeholder="Toggle for filtering none usable items..." name="Disable" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
																			<option <?php echo ($Builds[$Build_ID]['Disable'] == 0 ? 'selected="selected"' : "") ?> value="0">Disable none usable items</option>
																			<option <?php echo ($Builds[$Build_ID]['Disable'] != 0 ? 'selected="selected"' : "") ?> value="1">Allow all items</option>
                                    </select>
                                </p>
                            </td>
                        </tr>
												<tr>
													<td style="background-image:none;background-color:#FFF;"></td>
													<td colspan="3" style="text-align:left;">
														<p>
															<select id="Character" class="select" style="width:310px;" data-placeholder="Please select a character..." name="Character" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
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
															<select id="Type" class="select" style="width:310px;" data-placeholder="Please select type..." name="Type" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
																<option <?php echo ($Builds[$Build_ID]['Type']==0 ? 'selected="selected"' : "") ?> value="3">Base</option>
															</select>
														</p>
													</td>
												</tr>
                        <tr>
														<td style="" title="Base"><img src="img/SS_img/Base-Kits.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base" class="select" style="width:310px;" data-placeholder="Please select a Base..." name="Base" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
																				<?php
																				echo '<option selected="selected" value="' . $Build_Original_Items['Base']['Item_ID'] . '">' . $Build_Original_Items['Base']['Name'] . '</option>';
																					?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php 
													if ($Build_Original_Items['Base']['Item_ID'] == "") {
														?><tr><td style="background-image:none;background-color:white;" colspan="3">Please save after chosen a Base.</td></tr><?php
													} else {
?>
                        <tr>
														<td title="Extra_Workers"><img src="img/SS_img/Item.png"/></td>
                            <td colspan="3">
																	<input class="extra_workers" id="Extra_Workers" type="text" name="Extra_Workers" placeholder="Number of workers" value="<?php echo $Builds[$Build_ID]['Extra_Workers']; ?>" autocomplete="off" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                            </td>
                        </tr>
                        <tr>
							<td title="Base_Shield"><img src="img/SS_img/Shield.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Shield" class="select" style="width:310px;" data-placeholder="Please select a Shield..." name="Base_Shield" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Shield']['Item_ID'] . '">' . $Build_Original_Items['Base_Shield']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
							<td title="Base_Energy"><img src="img/SS_img/Energy.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Energy" class="select" style="width:310px;" data-placeholder="Please select an Energy source..." name="Base_Energy" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Energy']['Item_ID'] . '">' . $Build_Original_Items['Base_Energy']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
							<td title="Base_Radar"><img src="img/SS_img/Radar.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Radar" class="select" style="width:310px;" data-placeholder="Please select a Radar..." name="Base_Radar" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Radar']['Item_ID'] . '">' . $Build_Original_Items['Base_Radar']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                   
						<?php
							for ($r=1; $r <= $Base_Hull_Expander_Slots; $r++) {
								?>
                        <tr>
							<td title="Base_Hull_Expander<?php echo $r; ?>"><img src="img/SS_img/Hull-Expansions.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Base_Hull_Expander<?php echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select a Hull Expander..." name="Base_Hull_Expander<?php echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Hull_Expander'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Hull_Expander'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
													<td <?php echo ($r == $Base_Hull_Expander_Slots ? 'style="border-bottom:1px solid #ccc;"' : ""); ?>>
														<input class="input-small" id="Base_Hull_Expander<?php echo $r; ?>_Amount" type="text" name="Base_Hull_Expander<?php echo $r; ?>_Amount" placeholder="Amount" value="<?php echo $Build_Original_Items['Base_Hull_Expander'.$r]['Amount']; ?>" autocomplete="off" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
													</td>
                        </tr>
						<?php } ?>
						<?php
							for ($r=1; $r <= $Base_Capacitor_Slots; $r++) {
								?>
                        <tr>
							<td title="Base_Capacitor<?php echo $r; ?>"><img src="img/SS_img/Capacitors.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Capacitor<?php echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select a Capacitor..." name="Base_Capacitor<?php echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Capacitor'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Capacitor'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php
							 }
							for ($r=1; $r <= $Base_Overloader_Slots; $r++) {
								?>
                        <tr>
							<td title="Base_Overloader<?php echo $r; ?>"><img src="img/SS_img/Overloaders.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Overloader<?php echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select an Overloader..." name="Base_Overloader<?php echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Overloader'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Overloader'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php } ?>
                        <tr>
							<td title="Base_Shield_Charger"><img src="img/SS_img/Shield-Chargers.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Shield_Charger" class="select" style="width:310px;" data-placeholder="Please select a Shield Charger..." name="Base_Shield_Charger" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
																					echo '<option selected="selected" value="' . $Build_Original_Items['Base_Shield_Charger']['Item_ID'] . '">' . $Build_Original_Items['Base_Shield_Charger']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
														<td title="Base_Exterminator"><img src="img/SS_img/Exterminators.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Exterminator" class="select" style="width:310px;" data-placeholder="Please select a Exterminator..." name="Base_Exterminator" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
																					echo '<option selected="selected" value="' . $Build_Original_Items['Base_Exterminator']['Item_ID'] . '">' . $Build_Original_Items['Base_Exterminator']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
														<td title="Base_Overcharger"><img src="img/SS_img/Item.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Overcharger" class="select" style="width:310px;" data-placeholder="Please select a Base Overcharger..." name="Base_Overcharger" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
																				echo '<option selected="selected" value="' . $Build_Original_Items['Base_Overcharger']['Item_ID'] . '">' . $Build_Original_Items['Base_Overcharger']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
														<td title="Base_Aura_Generator"><img src="img/SS_img/Aura-Generators.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Aura_Generator" class="select" style="width:310px;" data-placeholder="Please select a Aura Generator..." name="Base_Aura_Generator" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
																				echo '<option selected="selected" value="' . $Build_Original_Items['Base_Aura_Generator']['Item_ID'] . '">' . $Build_Original_Items['Base_Aura_Generator']['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php 
							if ($Build_Original_Items['Base']['Item_ID'] == "") {
								?>
						<tr><td style="background-image:none;background-color:white;" colspan="3">[Weapon slots] -> Save after chosen a base.</td></tr>
						<?php
							} else {
							for ($r=1; $r <= $Base_Weapon_Slots; $r++) {
								?>
                        <tr>
							<td title="Base_Weapon<?php echo $r; ?>"><img src="img/SS_img/Weapon.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Base_Weapon<? echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select a Weapon..." name="Base_Weapon<? echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Weapon'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Weapon'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php 
							}
							} ?>
						<?php 
							if ($Build_Original_Items['Base']['Item_ID'] == "") {
								?>
						<tr><td style="background-image:none;background-color:white;" colspan="3">[Augmenter slots] -> Save after chosen a base.</td></tr>
						<?php
							} else {
							for ($r=1; $r <= $Base_Augmenter_Slots; $r++) {
								?>
                        <tr>
														<td title="Base_Augmenter<?php echo $r; ?>"><img src="img/SS_img/Augmenters.png"/></td>
                            <td colspan="3">
                                <p>
                                    <select id="Base_Augmenter<? echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select an Augmenter..." name="Base_Augmenter<? echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
																					echo '<option selected="selected" value="' . $Build_Original_Items['Base_Augmenter'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Augmenter'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
                        </tr>
						<?php }} ?>
						<?php
							for ($r=1; $r <= $Base_Diffuser_Slots; $r++) {
								?>
                        <tr>
												<td title="Base_Diffuser<?php echo $r; ?>"><img src="img/SS_img/Diffusers.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Base_Diffuser<? echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select a Diffuser..." name="Base_Diffuser<? echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Diffuser'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Diffuser'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
													<td>
														<input class="input-small" id="Base_Diffuser<?php echo $r; ?>_Amount" type="text" name="Base_Diffuser<?php echo $r; ?>_Amount" placeholder="Amount" value="<?php echo $Build_Original_Items['Base_Diffuser'.$r]['Amount']; ?>" autocomplete="off" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
													</td>
                        </tr>
						<?php } ?>
											<!--
             <tr>
							<td style="background-image:none;background-color:#FFF;"></td>
              <td colspan="3">
              	<p>
									<input class="input" id="Base_Workers" type="text" name="Base_Workers" placeholder="# Extra workers" value="<?php echo $Builds[$Build_ID]['Extra_Workers']; ?>"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
                </p>
							</td>
						</tr>
-->
						<?php
							for ($r=1; $r <= $Base_Extractor_Slots; $r++) {
								?>
                        <tr>
												<td title="Base_Extractor<?php echo $r; ?>"><img src="img/SS_img/Item.png"/></td>
                            <td colspan="2">
                                <p>
                                    <select id="Base_Extractor<? echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select a Extractor..." name="Base_Extractor<? echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Extractor'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Extractor'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
													<td <?php echo ($r == $Base_Extractor_Slots ? 'style="border-bottom:1px solid #ccc;"' : ""); ?>>
														<input class="input-small" id="Base_Extractor<?php echo $r; ?>_Amount" type="text" name="Base_Extractor<?php echo $r; ?>_Amount" placeholder="Amount" value="<?php echo $Build_Original_Items['Base_Extractor'.$r]['Amount']; ?>" autocomplete="off" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
													</td>
                        </tr>
						<?php } ?>
						<?php 
							if ($Build_Original_Items['Base']['Item_ID'] == "") {
								?>
						<tr><td style="background-image:none;background-color:white;" colspan="3">[Solar Panel slots] -> Save after chosen a base.</td></tr>
						<?php
							} else {
							for ($r=1; $r <= $Base_Solar_Panel_Slots; $r++) {
								?>
                        <tr>
							<td title="Base_Solar_Panel<?php echo $r; ?>"><img src="img/SS_img/Solar-Panels.png"/></td>
                            <td colspan="2" <?php echo ($r == $Base_Solar_Panel_Slots ? 'style="border-bottom:1px solid #ccc;"' : ""); ?>>
                                <p>
                                    <select id="Base_Solar_Panel<? echo $r; ?>" class="select" style="width:310px;" data-placeholder="Please select a Solar Panel..." name="Base_Solar_Panel<? echo $r; ?>" class="" <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>>
                                        <option value=""></option>
																				<option disabled value="0">Please use search field to find items!</option>
                                        <?php
										echo '<option selected="selected" value="' . $Build_Original_Items['Base_Solar_Panel'.$r]['Item_ID'] . '">' . $Build_Original_Items['Base_Solar_Panel'.$r]['Name'] . '</option>';
                                        ?>
                                    </select>
                                </p>
                            </td>
													<td></td>
                        </tr>
						<?php }}} ?>
                    </table>
                </fieldset>
							<br/>
              <input class="submit" style="width: 310px; margin-left: 16px; height: 25px;" id="SubmitForm" type="submit" name="submit" value="Save [Calculate]"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
							<br/>
							<br/>
							<input class="submit" style="width: 310px; margin-left: 16px; height: 25px;" id="SubmitForm" type="submit" name="DeleteBuild" value="Delete Build"  <? echo ($Disable == TRUE ? 'disabled="disabled" ' : "");?>/>
				<br/>
				<br/>
				<br/>
				<table style="display:none;">
					<tr>
						<td>Weapon slots:</td>
						<td><input class="input" id="Base_Weapon_Slots" type="input" name="Base_Weapon_Slots" value="<?php echo $Base_Weapon_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Augmenter slots:</td>
						<td><input class="input" id="Base_Augmenter_Slots" type="input" name="Base_Augmenter_Slots" value="<?php echo $Base_Augmenter_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Solar Panel slots:</td>
						<td><input class="input" id="Base_Solar_Panel_Slots" type="input" name="Base_Solar_Panel_Slots" value="<?php echo $Base_Solar_Panel_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Diffuser slots:</td>
						<td><input class="input" id="Base_Diffuser_Slots" type="input" name="Base_Diffuser_Slots" value="<?php echo $Base_Diffuser_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Capacitor slots:</td>
						<td><input class="input" id="Base_Capacitor_Slots" type="input" name="Base_Capacitor_Slots" value="<?php echo $Base_Capacitor_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Overloader slots:</td>
						<td><input class="input" id="Base_Overloader_Slots" type="input" name="Base_Overloader_Slots" value="<?php echo $Base_Overloader_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Hull Expander slots:</td>
						<td><input class="input" id="Base_Hull_Expander_Slots" type="input" name="Base_Hull_Expander_Slots" value="<?php echo $Base_Hull_Expander_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Extractor slots:</td>
						<td><input class="input" id="Base_Extractor_Slots" type="input" name="Base_Extractor_Slots" value="<?php echo $Base_Extractor_Slots; ?>" /></td>
					</tr>
					<tr>
						<td>Build ID:</td>
						<td><input class="input" id="Build" type="input" name="Build" value="<?php echo $Build_ID; ?>" /></td>
					</tr>
					<tr>
						<td>Include temp bonus:</td>
						<td><input type="checkbox" id="Real_Temp_Bonus_State" name="Real_Temp_Bonus_State" <?php if ($Include_Temp_Bonus === TRUE) {echo 'checked="checked"';} ?> /></td>
					</tr>
				</table>
            </form>
        </td>
    </tr>
	<?php } ?>
</table>
<?php 
if ($Build_ID != 0 and $Builds[$Build_ID]['Character_ID'] != 0 and $Build_Original_Items['Base']['Item_ID'] != "") {
	require("includes/base_window.php");
}

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
		"Base_Overcharger" => "Base_Overchargers",
		"Base_Augmenter" => "Base_Augmenters",
		"Base_Extractor" => "Base_Extractors"//,
		//"Base_Exterminator" => "Base_Exterminators"
	);
	
foreach ($Item_Names as $Item => $Items) { 
	if (isset(${"" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
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

        $(".select").chosen({allow_single_deselect: true});
				//$("#Temp_Bonus_State").button();
				//$(".multiselect").chosen({allow_single_deselect: true});
				$(".multiselect").multiselect({
			 		selectedList: 4 // 0-based index
				}); 
			  $("#Temp_Bonus_State").click(function() {
        	var checkBoxes = $("#Real_Temp_Bonus_State");
        	checkBoxes.prop("checked", !checkBoxes.prop("checked"));
    		});  
			
					    });
		var running = false;
		var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		  };
		})();
		
		
<?php
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
			
foreach ($Item_Names as $Item => $Items) {
	if (isset(${"" . $Item . "_Slots"})) {
		for ($r=1; $r <= ${"" . $Item . "_Slots"};$r++) {
			 echo '
					$(document).on("keydown", "#'.$Item.$r.'_chosen", function (e) {
						var regex = new RegExp("^[a-zA-Z0-9]+$");
						var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
						if (regex.test(str)) {
							if (running) return false;
							var currenttext = $("select#'.$Item.$r.' option:selected").text();
							var currentvalue = $("select#'.$Item.$r.'").val();
							delay(function(){
								running = true;
								var searchparam = $("#'.$Item.$r.'_chosen input").val();
								var object = $("select#'.$Item.$r.'").get(0);
								var object_results = $("#'.$Item.$r.'_chosen .chosen-results").get(0);
								$(object).empty();
								$(object).append($("<option></option>").val(0).html(""));
								$(object).append($("<option value=" + currentvalue + " selected></option>").html(currenttext));
								$.getJSON("includes/dropdown_option_fetcher.php?build='.$Build_ID.'&searchparam=" + searchparam + "&type='.$Item.'&types='.$Items.'",{},function(data)
								{
									$.each(data, function(i,obj)
									{
									if (obj["ID"] != currentvalue) {
										if (obj["Disabled"] == "FALSE") {
											$(object).append($("<option></option>").val(obj["ID"]).html(obj["Name"]));
										} else {
											$(object).append($(\'<option disabled="disabled"></option>\').val(obj["ID"]).html(obj["Name"]));
										}
									}
									}),$(object).trigger("chosen:updated"), $("#'.$Item.$r.'_chosen input").val(searchparam);
								});

								running = false;
								}, 500 );
						}
					});
				';
		}
	} else {
		 echo '
				$(document).on("keydown", "#'.$Item.'_chosen", function (e) {
					var regex = new RegExp("^[a-zA-Z0-9]+$");
					var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
					if (regex.test(str)) {
						if (running) return false;
						var currenttext = $("select#'.$Item.' option:selected").text();
						var currentvalue = $("select#'.$Item.'").val();
						delay(function(){
							running = true;
							var searchparam = $("#'.$Item.'_chosen input").val();
							var object = $("select#'.$Item.'").get(0);
							$(object).empty();
							$(object).append($("<option></option>").val(0).html(""));
							$(object).append($("<option value=" + currentvalue + " selected></option>").html(currenttext));
							$.getJSON("includes/dropdown_option_fetcher.php?build='.$Build_ID.'&searchparam=" + searchparam + "&type='.$Item.'&types='.$Items.'",{},function(data)
							{
								$.each(data, function(i,obj)
								{
								if (obj["ID"] != currentvalue) {
									if (obj["Disabled"] == "FALSE") {
										$(object).append($("<option></option>").val(obj["ID"]).html(obj["Name"]));
									} else {
										$(object).append($(\'<option disabled="disabled"></option>\').val(obj["ID"]).html(obj["Name"]));
									}
								}}),$(object).trigger("chosen:updated"), $("#'.$Item.'_chosen input").val(searchparam);
								
							});

							running = false;
							}, 500 );
					}
				});
			';
	}
}
?>

    function reloadwindow() {
        var r = confirm("Are you sure you want to reset the form?\n\n All changes will be lost.");
        if (r == true)
        {
            window.location.reload();
        }
    }
</script>