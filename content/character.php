<script type="text/javascript">
	$(function() {
		//Ber browsere ikke fylle inn informasjon automatisk
		$("input").attr("autocomplete", "off");

		// Jquery UI på skjema knappene
		$("input[type=submit], input[type=button]").button();
		$("select").chosen({allow_single_deselect: true});
	});
</script>
<?php 

$Query = "SELECT * FROM Characters WHERE User_ID = " . $_SESSION['UserID'] . " ORDER BY Name"; 
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Characters[$row['Character_ID']]['ID'] = $row['Character_ID'];
	$Characters[$row['Character_ID']]['Name'] = $row['Name'];
	$Characters[$row['Character_ID']]['Class'] = $row['Class'];
	$Characters[$row['Character_ID']]['Focus'] = $row['Focus'];
	$Character_Max = max($Character_Max, $row['Character_ID']);
}


if (isset($_POST)) {
	if (isset($_POST['CreateCharacter'])) {
		$Character_Max++;
		$Query = "INSERT INTO Characters (User_ID, Character_ID, Name) VALUES (" . $_SESSION['UserID'] . ", $Character_Max, '" . $_POST['NewCharacter'] . "');";
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	}
	
	if (isset($_POST['DeleteCharacter'])) {
		unset($Builds);
		$Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_SESSION['UserID'] . " AND Character_ID =" . $_POST['Character'];
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
		
		if (!isset($Builds)) {
			$Character_Max--;
			$Query = "DELETE FROM Characters WHERE User_ID = ".$_SESSION['UserID']." AND Character_ID = " . $_POST['Character'] . ";";
			$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");

			$Query = "DELETE FROM User_Skills WHERE User_ID = ".$_SESSION['UserID']." AND Character_ID = " . $_POST['Character'] . ";";
			$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
			
			echo "<br/>Deleted character!<br/><br/><br/>";
		} else {
			echo "<br/>Can't delete characters with stored builds!<br/>";
		}
	}
	
	if (isset($_POST['submitCharacterName']) and $_POST['Character'] != 0) {
		$Query = "UPDATE Characters SET Name='" . $_POST['CharacterName'] . "' WHERE User_ID = ".$_SESSION['UserID']." AND Character_ID = " . $_POST['Character'] . ";";
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		echo '<h2 style="color:green;text-align:center;">Character\'s name is saved!</h2><br/><br/>';
	}
	
	if (isset($_POST['SubmitFocus']) and is_numeric($_POST['Focus'])) {
		$Query = "UPDATE Characters SET Focus = " . $_POST['Focus'] . " WHERE User_ID = " . $_SESSION['UserID'] . " AND Character_ID = " . $_POST['Character'] . ";"; 
		$Result = mysql_query($Query, $conn) or die(mysql_error()); 
		echo '<h2 style="color:green;text-align:center;">Character\'s focus is saved!</h2><br/><br/>';
	} 
	
	if (isset($_POST['SubmitClass']) and is_numeric($_POST['Class'])) {
		$Query = "UPDATE Characters SET Class = " . $_POST['Class'] . " WHERE User_ID = " . $_SESSION['UserID'] . " AND Character_ID = " . $_POST['Character'] . ";"; 
		$Result = mysql_query($Query, $conn) or die(mysql_error()); 
		echo '<h2 style="color:green;text-align:center;">Character\'s class is saved!</h2><br/><br/>';
	}
}

$Query = "SELECT * FROM Characters WHERE User_ID = " . $_SESSION['UserID'] . " ORDER BY Name"; 
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Characters[$row['Character_ID']]['ID'] = $row['Character_ID'];
	$Characters[$row['Character_ID']]['Name'] = $row['Name'];
	$Characters[$row['Character_ID']]['Class'] = $row['Class'];
	$Characters[$row['Character_ID']]['Focus'] = $row['Focus'];
	$Character_Max = max($Character_Max, $row['Character_ID']);
}

$Query = "SELECT * FROM Skills ORDER BY Family_1, Family_2, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Skills[$row['Excel_Skill_ID']]['Name'] = $row['Name'];
	$Skills[$row['Excel_Skill_ID']]['Skill_ID'] = $row['Excel_Skill_ID'];
	$Skills[$row['Excel_Skill_ID']]['Family_1'] = $row['Family_1'];
	$Skills[$row['Excel_Skill_ID']]['Family_2'] = $row['Family_2'];
}

?>
<table id="RF1199_FormatTable" style="width:100%;">
	<tr>
		<td>
			<h2>Character registration</h2>
			<form action="?module=skills&amp;content=character" method="post" id="formID">
				<fieldset>
					<table class="innerTable">
                        <tr>
                            <td>
                                <p>
                                    
									<input class="input" id="NewCharacter" type="text" name="NewCharacter" placeholder="Name on new Character..." value="" />
                                </p>
                            </td>
							<td>
								<input class="submit" style="width:150px;margin-left:10px;" id="SubmitForm" type="submit" name="CreateCharacter" value="Create Character" />
							</td>
						</tr>
						<tr>
                            <td style="text-align:left;">
                                <p>
                                    <select id="Character" class="select" data-placeholder="Please select a Character..." name="Character" class="">
                                        <option value="0"></option>
										<option value="0">No Character</option>
                                        <?php
                                        foreach ($Characters as $Character) { ?>
												<option <?php echo ($Character['ID']==$_POST['Character'] ? 'selected="selected"' : "") ?> value="<?php echo $Character['ID']; ?>"><?php echo str_replace("_", " ",$Character['Name']); ?></option>
										<?php	
										}
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
								<input class="submit" id="SubmitForm" name="submitbutton" style="width:150px;margin-left:10px;" type="submit" value="Select Character" />	
							</td>
                        </tr>
						<tr><td colspan="2"></td></tr>
						<?php if (isset($_POST['Character']) and $_POST['Character'] != 0) { ?>
                        <tr>
                            <td>
                                <p>
                                    <label for="CharacterName" class="outField" style="display:block;opacity:1;width:300px;">Character Name</label>
									<input class="input" id="CharacterName" type="text" name="CharacterName" value="<?php echo $Characters[$_POST['Character']]['Name']; ?>" />
                                </p>
                            </td>
														<td style="vertical-align:bottom;">
														<input class="submit" style="width:150px;margin-left:10px;" id="submitCharacterName" type="submit" name="submitCharacterName" value="Update Name" />
													</td>
												</tr>
                        <tr>
													<td style="vertical-align:bottom;" colspan="2">
														<input class="submit" style="width:150px;margin-left:10px;" id="DeleteCharacter" type="submit" name="DeleteCharacter" value="Delete Character" />
													</td>
												</tr>
												<tr>
                            <td style="text-align:left;">
                                <p>
                                    <select id="Focus" class="select" data-placeholder="Please select a Focus..." name="Focus" class="">
                                        <option value="0"></option>
										<option value="0">No Focus</option>
                                        <?php
                                        foreach ($Skills as $Skill_ID => $Skill_Name) {
											if (strpos($Skills[$Skill_ID]['Name'],"Focus") !== FALSE) {
?>
												<option <?php echo ($Skills[$Skill_ID]['Skill_ID']==$Characters[$_POST['Character']]['Focus'] ? 'selected="selected"' : "") ?> value="<?php echo $Skills[$Skill_ID]['Skill_ID']; ?>"><?php echo str_replace("_", " ",$Skills[$Skill_ID]['Name']); ?></option>
										<?php	
																								  }}
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
								<input class="submit" id="SubmitFocus" name="SubmitFocus" style="width:110px;margin-left:10px;" type="submit" value="Save focus" />	
							</td>
                        </tr>
						<?php if ($Characters[$_POST['Character']]['Focus'] != 0) { ?>
						<tr>
                            <td style="text-align:left;">
                                <p>
                                    <select id="Class" class="select" data-placeholder="Please select a Class..." name="Class" class="">
                                        <option value="0"></option>
										<option value="0">No Class</option>
                                        <?php
                                        foreach ($Skills as $Skill_ID => $Skill_Name) {
											if (strpos($Skills[$Skill_ID]['Name'],"Class") !== FALSE and $Skill_ID != 125) {
												if ($Skills[$Skill_ID]['Family_1'] == $Skills[$Characters[$_POST['Character']]['Focus']]['Family_1']) {
										?>
												<option <?php echo ($Skills[$Skill_ID]['Skill_ID']==$Characters[$_POST['Character']]['Class'] ? 'selected="selected"' : "") ?> value="<?php echo $Skills[$Skill_ID]['Skill_ID']; ?>"><?php echo str_replace("_", " ",$Skills[$Skill_ID]['Name']); ?></option>
										<?php	
												}	
											}
										}
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td>
								<input class="submit" id="SubmitClass" name="SubmitClass" style="width:110px;margin-left:10px;" type="submit" value="Save class" />
							</td>
                        </tr>
					</table>
				</fieldset>
				<?php } } ?>
			</form>
		</td>
	</tr>
</table>
<br/>
