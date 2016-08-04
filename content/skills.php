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
$Skill_Family_Names = array(
	0 => "Miscellaneous",	
	1 => "Combat Focus", 
	2 => "Fleet Focus", 
	3 => "Recon Focus", 
	4 => "Support Focus", 
	5 => "Team Skills", 
	6 => "General Skills", 
	7 => "Tweaking Skills", 
	8 => "Research Skills", 
	9 => "Advanced Class Sub Skills", 
	10 => "Zen Skills", 
	11 => "Resistance Skills", 
	12 => "Control Skills", 
	13 => "Imperial Skills", 
	14 => "Bar Skills", 
	15 => "Kalthi Skills"
);


$Query = "SELECT * FROM Skills ORDER BY Family_1, Family_2, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Skills[$row['Excel_Skill_ID']]['Name'] = $row['Name'];
	$Skills[$row['Excel_Skill_ID']]['Skill_ID'] = $row['Excel_Skill_ID'];
	$Skills[$row['Excel_Skill_ID']]['Family_1'] = $row['Family_1'];
	$Skills[$row['Excel_Skill_ID']]['Family_2'] = $row['Family_2'];
}

if ($_POST['Character'] != 0) {
	$Query = "SELECT * FROM Skills LEFT JOIN User_Skills ON User_Skills.Skill_ID = Skills.Excel_Skill_ID WHERE User_Skills.User_ID = " . $_SESSION['UserID'] . " AND User_Skills.Character_ID = " . $_POST['Character'] . " ORDER BY Family_1, Family_2, Name";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Skills[$row['Excel_Skill_ID']]['Level'] = $row['Skill_Level'];
		$Skills[$row['Excel_Skill_ID']]['Name'] = $row['Name'];
		$Skills[$row['Excel_Skill_ID']]['Skill_ID'] = $row['Excel_Skill_ID'];
		$Skills[$row['Excel_Skill_ID']]['Family_1'] = $row['Family_1'];
		$Skills[$row['Excel_Skill_ID']]['Family_2'] = $row['Family_2'];
	}
}

$Query = "SELECT * FROM Skill_Mods LEFT JOIN Mods ON Skill_Mods.Excel_Mod_ID = Mods.Excel_Mod_ID";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Skills[$row['Skill_ID']]['Mods'] = ($Skills[$row['Skill_ID']]['Mods']=="" ? 1 : $Skills[$row['Skill_ID']]['Mods']+1);
	$Skills[$row['Skill_ID']]['Mods'.$Skills[$row['Skill_ID']]['Mods']] = $row['Name'] . ": " . $row['Value'] . " pr. level";
}

$Query = "SELECT * FROM Characters WHERE User_ID = " . $_SESSION['UserID'] . " ORDER BY Name"; 
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Characters[$row['Character_ID']]['ID'] = $row['Character_ID'];
	$Characters[$row['Character_ID']]['Name'] = $row['Name'];
	$Characters[$row['Character_ID']]['Class'] = $row['Class'];
	$Characters[$row['Character_ID']]['Focus'] = $row['Focus'];
}


if (isset($_POST)) {
	if ($_POST['submitbutton'] == "Save skills") {
		
		foreach ($Skills as $Skill_ID => $Skill) {
			$Skills[$Skill_ID]['Name'] = str_replace(" ","-",$Skills[$Skill_ID]['Name']);
			if ($_POST[$Skill_ID] == ""){
				$_POST[$Skill_ID] = 0;
			}
			if (!isset($_POST[$Skill_ID])) {
				$_POST[$Skill_ID] = 0;
			}
			
			if (isset($_POST[$Skill_ID]) and $_POST['Character'] != 0) {
				$Query1 = "SELECT count(*) as total FROM User_Skills WHERE User_ID = " . $_SESSION['UserID'] . " AND Skill_ID = ".$Skill_ID." AND Character_ID = " . $_POST['Character'] . ";"; 
				$Result1 = mysql_query($Query1, $conn) or die(mysql_error() . "<br/>Query: " . $Query1); 
				while ($row = mysql_fetch_array($Result1, MYSQL_ASSOC)) {
					$Query="";
					if ($row['total'] > 0){
						$Query = "UPDATE User_Skills SET Skill_Level=".$_POST[$Skill_ID]." WHERE User_ID = ".$_SESSION['UserID']." AND Skill_ID=$Skill_ID AND Character_ID=" . $_POST['Character'] . ";";
					} else {
						$Query = "INSERT INTO User_Skills (User_ID, Skill_ID, Skill_Level, Character_ID) VALUES (".$_SESSION['UserID'].",$Skill_ID," . $_POST[$Skill_ID]."," . $_POST['Character'].")"; 		
					}
					if ($Query != "") {
						$Result = mysql_query($Query, $conn) or die(mysql_error() . "Query: " . $Query);	
					}
				}
			}
		}
		echo '<h2 style="color:green;text-align:center;">Skills are saved!</h2><br/><br/>';
	}
}

if ($_POST['Character'] != 0) {
	$Query = "SELECT * FROM Skills LEFT JOIN User_Skills ON User_Skills.Skill_ID = Skills.Excel_Skill_ID WHERE User_Skills.User_ID = " . $_SESSION['UserID'] . " AND User_Skills.Character_ID = " . $_POST['Character'] . " ORDER BY Family_1, Family_2, Name";
	$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
	while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		$Skills[$row['Excel_Skill_ID']]['Level'] = $row['Skill_Level'];
		$Skills[$row['Excel_Skill_ID']]['Name'] = $row['Name'];
		$Skills[$row['Excel_Skill_ID']]['Skill_ID'] = $row['Excel_Skill_ID'];
		$Skills[$row['Excel_Skill_ID']]['Family_1'] = $row['Family_1'];
		$Skills[$row['Excel_Skill_ID']]['Family_2'] = $row['Family_2'];
	}
}

?>
<table id="RF1199_FormatTable" style="width:100%;">
	<tr>
		<td>
			<h2>Skills registration</h2>
			<form action="?module=skills&amp;content=skills" method="post" id="formID">
				<fieldset>
					<table class="innerTable Skilltable">
						<tr>
                            <td colspan="4" style="text-align:left;">
                                <p>
                                    <select id="Character" class="select" data-placeholder="Please select a character..." name="Character" class="">
                                        <option value="0"></option>
										<option value="0">No Character</option>
                                        <?php
                                        foreach ($Characters as $Character) {
										?>
										<option <?php echo ($Character['ID']==$_POST['Character'] ? 'selected="selected"' : "") ?> value="<?php echo $Character['ID']; ?>"><?php echo $Character['Name']; ?></option>
										<?php	
										}
                                        ?>
                                    </select>
                                </p>
                            </td>
							<td colspan="11">
								<input class="submit" id="SelectCharacter" name="SelectCharacter" style="width:150px;margin-left:10px;" type="submit" value="Select Character" />	
							</td>
                        </tr>
						<tr>
							<td colspan="15">
								<p>
						<?php if ($_POST['Character'] == 0) {
								echo "Please select a character!";
							} else {
								if ($Characters[$_POST['Character']]['Class'] != 0 and $Characters[$_POST['Character']]['Focus'] != 0) {  } else {echo "FYI: You will not see any class skills before the chosen character has a stored Focus and Class for your character.";} 
							}
						?> 
								</p>
							</td>
						</tr>
						<?php	if ($_POST['Character'] != 0) {	?>
						<tr>
							<td colspan="15">
									<input class="submit" id="SubmitForm" name="submitbutton" style="width: 310px;" type="submit" value="Save skills" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right;">Skill name</td>
							<td style="text-align:center;">Lvl</td>
							<td># Mods</td>
							<td></td>
							<td style="text-align:right;">Skill name</td>
							<td style="text-align:center;">Lvl</td>
							<td># Mods</td>
							<td></td>
							<td style="text-align:right;">Skill name</td>
							<td style="text-align:center;">Lvl</td>
							<td># Mods</td>
							<td></td>
							<td style="text-align:right;">Skill name</td>
							<td style="text-align:center;">Lvl</td>
							<td># Mods</td>
						</tr>
						<?php 
						$r=0;
						foreach ($Skills as $Skill_ID => $Skill_Name) {
							if (
								($Skills[$Skill_ID]['Family_1'] != $Skills[$Characters[$_POST['Character']]['Class']]['Family_1'] 
								and $Skills[$Skill_ID]['Family_1'] > 0  
								and $Skills[$Skill_ID]['Family_1'] < 5) or ($Characters[$_POST['Character']]['Class'] == 0 and $Skills[$Skill_ID]['Family_1'] > 0  
								and $Skills[$Skill_ID]['Family_1'] < 5)
							   ) { 
							} else {
								
								if (
									(
										$Skills[$Skill_ID]['Family_2'] != $Skills[$Characters[$_POST['Character']]['Class']]['Family_2'] 
										or $Skills[$Skill_ID]['Family_2'] == ""
									)
								and $Skills[$Skill_ID]['Family_2'] > 0
								and $Skills[$Skill_ID]['Family_2'] < 9) {
								} else {
									if ($Last_Family_Nr != $Skills[$Skill_ID]['Family_1']) {
										if ($r!=4 and $r!=0) {
											for ($l=$r; $l<=4; $l++) {
												echo "<td></td>";
											}
										}
										$r=0;
										echo '<tr><td style="background-color:#2F4382;color:white;padding-left:10px;" colspan="15">' . $Skill_Family_Names[$Skills[$Skill_ID]['Family_1']] . '</td></tr>';
									}
									if ($r==0) {
										echo "<tr>";
									} else {
										//echo "<td>";
									}
								?>
									<td style="text-align:right;">
											<label for="Skill_<?php echo $Skills[$Skill_ID]['Name']; ?>" class="outField" style="display: block;opacity: 1;"><?php echo str_replace("_"," ",$Skills[$Skill_ID]['Name']); ?></label>
									</td>
									<td title="<?php echo $Skills[$Skill_ID]['Skill_ID']; ?>">
											<input type="text" name="<?php echo $Skill_ID; ?>" id="<?php echo $Skills[$Skill_ID]['Skill_ID']; ?>" value="<?php echo $Skills[$Skill_ID]['Level']; ?>" />
									</td>
									<td style="text-align:center;color:grey;"><?php echo $Skills[$Skill_ID]['Mods']; ?></td>
								<?php 
									$r++;
									if ($r==4) {
										echo "</tr>";
										$r=0;
									} else {
										echo "<td></td>";
									}
									$Last_Family_Nr = $Skills[$Skill_ID]['Family_1'];
								}
							}
						} ?>
						<?php	}	?>
					</table>
				</fieldset>
				<?php	if ($_POST['Character'] != 0) {	?>
				<input class="submit" id="SubmitForm" style="width: 310px;" name="submitbutton" type="submit" value="Save skills" />
				<?php	}	?>
			</form>
		</td>
	</tr>
</table>
<br/>

<script type="text/javascript">
<?php 
		foreach ($Skills as $Skill_ID => $Skill_Name) {
			if (isset($Skills[$Skill_ID]['Mods1'])) {
?>
	$('td[title=<?php echo $Skill_ID; ?>]').qtip({
		content: {
			text:'<?php for ($r=1; $r<= $Skills[$Skill_ID]['Mods']; $r++) { echo $Skills[$Skill_ID]['Mods'.$r] . "<br/>";} ?>',
		title: '<?php echo  str_replace("_", " " , $Skills[$Skill_ID]['Name']); ?> effects!'
		},
		position: {
			my: 'top left',  // Position my top left...
			at: 'top right', // at the bottom right of...
			target: $('td[title=<?php echo $Skill_ID;?>]') // my target
		}
	});
	<?php }} ?>
</script>