<?php
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
	"Homing_Beacon" => "Homing_Beacons",
	"Base" => "Bases",
	"Base_Augmenter" => "Base_Augmenters",
	"Base_Aura_Generator" => "Base_Aura_Generators",
	"Base_Capacitor" => "Base_Capacitors",
	"Base_Diffuser" => "Base_Diffusers",
	"Base_Energy" => "Base_Energies",
	"Base_Extractor" => "Base_Extractors",
	"Base_Hull_Expander" => "Base_Hull_Expanders",
	"Base_Overloader" => "Base_Overloaders",	
	"Base_Radar" => "Base_Radars",
	"Base_Shield" => "Base_Shields",
	"Base_Shield_Charger" => "Base_Shield_Chargers",
	"Base_Solar_Panel" => "Base_Solar_Panels",
	"Base_Weapon" => "Base_Weapons"
);

if (isset($_POST['Item_Type'])) {
	$Type = $_POST['Item_Type'];
	$Type_Plural = $Item_Names[$_POST['Item_Type']];
} else {
	$Type = "Radar";
	$Type_Plural = "Radars";
}

if (isset($_SESSION['UserID'])) {
	$Query = "SELECT * FROM User_Builds WHERE Type != 3 AND User_ID = " . $_SESSION['UserID'];
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
	}
$Disable = 1;

if (isset($_POST['Build']) and $_POST['Build'] != "" and $_POST['Build'] != 0) {
		$Disable = 0;
	
		$Query = "SELECT * FROM User_Builds WHERE Build_ID = " . $_POST['Build'];
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Character_ID = $row['Character_ID'];
			$User_ID = $row['User_ID'];
		}

		$Query = "SELECT * FROM User_Skills WHERE User_ID = " . $User_ID . " and Character_ID = " . $Character_ID;
		$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
		while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$Skills[$row['Skill_ID']]['Level'] = $row['Skill_Level'];
		}
	
	if ($Type != "Ship" and substr($Type,0,4) != "Base") {
		$Query = "SELECT Item_ID FROM User_Build_Items WHERE User_Build_ID = " . $_POST['Build'] . ' AND Item_Type = "Ship"';
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
	$Mod_Names[] = $Type . "_ID";
	$Mod_Names[] = "Name";
	$Mod_Names[] = "Status";
}

$Query = "SELECT * FROM $Type_Plural ORDER BY Tech, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
  $IDs[] = $row[$Type . '_ID'];
	if ($Type_Plural == "Weapons" and ($row['Damage_Max']*1) <> 0 and ($row['Recoil']*1) <> 0) {
		$row['DPS'] = round( (($row['Damage_Max']+$row['Damage_Min'])/2)*$row['Projectiles'] / $row['Recoil'],2);
		if ($row['Electricity'] <> 0) {
			$row['EPS'] = round($row['Electricity'] / $row['Recoil'],2);
			$row['DPS/EPS'] = round($row['DPS'] / $row['EPS'],2);
		}
		$row['Damage_P'] = ($row['Damage_Max']+$row['Damage_Min'])/2 . "x" . $row['Projectiles'];
		$row['Damage'] = ($row['Damage_Max']+$row['Damage_Min'])/2 * $row['Projectiles'];
		$row['Range'] = $row['W_Range'];
		unset($row['Damage_Max'], $row['Damage_Min'], $row['Projectiles'], $row['W_Range']);
	}
	if ($Type_Plural == "Shields" and ($row['Reg_Elec']*1) <> 0 and ($row['Regeneration']*1) <> 0) {
		$row['Reg/Elec'] = round($row['Regeneration'] / ($row['Reg_Elec']+$row['Electricity']),2);
	}
	if ($Type_Plural == "Shields" and ($row['Bank']*1) <> 0 and ($row['Size']*1) <> 0) {
		$row['Bank/Size'] = round($row['Bank'] / ($row['Size']),2);
	}
	
	//
	//	Check if disabled
		if ($row['Require_Ship'] == "Industrial Freighter" or $row['Require_Ship'] == "Support Freighter") {
				$row['Require_Ship'] = "Freighter";	
		}
		$Item_Information[$row[$Type . '_ID']]['Disabled'] = "FALSE";

		if (($row['Require_Skill_ID'] != 0 and $Skills[$row['Require_Skill_ID']]['Level'] < $row['Require_Skill_Level'])) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Req. higher skill";
		}
		if($row['Require_Ship'] != "" and $row['Require_Ship'] != $Ship_Type) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Req. " . $row['Require_Ship'] . "";
		}
		if ($Type == "Augmenter" and $row['Tech'] > $Ship_Tech) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Aug Tech > Ship Tech";
		} 
		if ($Type == "Ship" and $row['Tech'] > $Skills[1]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Piloting";
		} 
		if ($Type == "Weapon" and $row['Tech'] > $Skills[2]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Weaponry";
		} 
		if ($Type == "Shield" and $row['Tech'] > $Skills[3]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = " (Tech > Shielding)";
		} 
		if ($Type == "Engine" and $row['Tech'] > $Skills[4]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Engine skill";
		} 
		if ($Type == "Radar" and $row['Tech'] > $Skills[5]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Radar skill";
		} 
		if ($Type == "Cloak" and $row['Tech'] > $Skills[6]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Cloak skill";
		} 
		if ($Type == "Energy" and $row['Tech'] > $Skills[7]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Electrical engineering";
		} 
		if ($Type == "Tractor" and $row['Tech'] > $Skills[8]['Level']) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "TRUE";
			$Item_Information[$row[$Type . '_ID']]['Status'] = "Tech > Tractoring";
		} 
		
		if ($Disable == 1) {
			$Item_Information[$row[$Type . '_ID']]['Disabled'] = "FALSE";
		}
	unset($row['Require_Skill_ID'], $row['Require_Skill_Level'], $row['Require_Ship']);
	
	foreach ($row as $field_name => $field_value) {
		$Item_Information[$row[$Type . '_ID']][$field_name] = $field_value;
    $Mod_Names[] = $field_name;
	}
}
$Query = "SELECT * FROM " . $Type . "_Mods LEFT JOIN Mods ON " . $Type . "_Mods.Excel_Mod_ID = Mods.Excel_Mod_ID ORDER BY Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
  $Item_Information[$row[$Type . '_ID']][' ' . $row['Name']] = $row['Value'];
  $Mod_Names[] = ' ' . $row['Name'];
}
$Mod_Names = array_unique($Mod_Names);
$Mod_Names = array_values($Mod_Names);

for ($l=0; $l < count($Mod_Names); $l++) {
  if ($Mod_Names[$l] == "Tech") {
		$Tech_Column = $l;
		break;
	}
}

?>

<script type="text/javascript">
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[<?php echo $Tech_Column; ?>] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);	
	
$(document).ready(function(){
	$("#Item_Type").chosen({allow_single_deselect: true});
	$("#Character").chosen({allow_single_deselect: true});
	$("#Build").chosen({allow_single_deselect: true});
	$("input[type=submit], input[type=button]").button();
	
	// Setup - add a text input to each footer cell
	$('#myTable tfoot th').each( function () {
			var title = $('#myTable thead th').eq( $(this).index() ).text();
			$(this).html( '<input type="text" style="width:60px;" placeholder="Search..." />' );
	} );
	
	
  var table = $('#myTable').DataTable( {
    "scrollY": '60vh',
    "scrollX": true,
    "paging": false,
    "aoColumnDefs": [
      { "bVisible": true, "aTargets": [1, 2, 3, 4, 5, 6] },
      { "bVisible": false, "aTargets": ['_all'] }
     ],
     dom: 'Bfrtip',
     buttons: [
      'colvis'
     ]
    });
	
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that.search( this.value ).draw();
            }
        } );
    } );
	
	    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup( function() {
        table.draw();
    } );
});
</script>

<?php
echo "<pre>";
// print_r($Mod_Names);
// print_r($Item_Information);
echo "</pre>";
echo '<p style="width:500px;margin:0 auto;padding:10px;">To sort on multiple columns hold in shift while clicking on the columns you want to sort. </br>To select which information is visible in the table click on the [Column Visibility] button.</p>';
echo '<div style="width:90%;margin:0 auto;">';
?>
<form action="?module=skills&amp;content=item_viewer" method="post" id="formID">
		<fieldset>
				<table class="innerTable">
					<tr>
						<td colspan="2">
							<select id="Build" class="select" style="width:310px;" data-placeholder="Filter on a build..." name="Build" class="">
								<option value="0"></option>
								<?php 
									foreach ($Builds as $Item => $Items) {
										echo '<option value="'.$Item.'" ' . ($Item == $_POST['Build'] ? 'selected="selected"' : "") . '>' . $Items['Name'] . '</option>';
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<select id="Item_Type" class="select" style="width:310px;" data-placeholder="Please select a item type..." name="Item_Type" class="">
								<?php 
									foreach ($Item_Names as $Item => $Items) {
										echo '<option value="'.$Item.'" ' . ($Item == $Type ? 'selected="selected"' : "") . ($Item == $_POST['Item_Type'] ? 'selected="selected"' : "") . '>'.str_replace("_"," ",$Items).'</option>';
									}
								?>
							</select>
						</td>
						<td>
							&nbsp;<input class="submit" style="padding:3.5px 15px;" id="SubmitForm" type="submit" name="OpenBuild" value="Select Item Type" />
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
			</table>
	</fieldset>
</form>
<table border="0" cellspacing="5" cellpadding="5">
   	<tbody>
					<tr>
            <td>Minimum tech:</td>
            <td>
							<input type="text" id="min" name="min">
						</td>
        </tr>
        <tr>
            <td>Maximum tech:</td>
            <td>
							<input type="text" id="max" name="max">
					</td>
        </tr>
    </tbody>
</table>
<?php
echo '<table id="myTable" class="display compact" cellspacing="0" width="100%">';

echo "<thead>";
echo "<tr>";
for ($l=0; $l < count($Mod_Names); $l++) {
  echo "<th>" . $Mod_Names[$l] . "</th>";
}
echo "</tr>";
echo "</thead>";

echo "<tbody>";
for ($r=0; $r <= count($IDs); $r++) {
	if ($Item_Information[$IDs[$r]]['Name'] != "") {
		echo "<tr " . ($Item_Information[$IDs[$r]]['Disabled'] == "TRUE" ? 'class="Row_Disabled"' : "").">";
		for ($l=0; $l < count($Mod_Names); $l++) {
			$Center = FALSE;
			if (substr($Mod_Names[$l],0,1) == " ") {
				$Center = TRUE;
				$Item_Information[$IDs[$r]][$Mod_Names[$l]] = ($Item_Information[$IDs[$r]][$Mod_Names[$l]]*100) . " %";
				if (($Item_Information[$IDs[$r]][$Mod_Names[$l]]*1) == 0 and is_numeric($Item_Information[$IDs[$r]][$Mod_Names[$l]]*1)) {
					$Item_Information[$IDs[$r]][$Mod_Names[$l]] = "";
				}
			}
			if (is_numeric($Item_Information[$IDs[$r]][$Mod_Names[$l]])) {
				$Center = TRUE;
			}
			echo '<td ' . ($Center ? 'style="text-align:center;"' : "") . '>' . $Item_Information[$IDs[$r]][$Mod_Names[$l]] . "</td>";
		}
		echo "</tr>";
	}
}
echo "</tbody>";

echo "<tfoot>";
echo "<tr>";
for ($l=0; $l < count($Mod_Names); $l++) {
  echo "<th>" . $Mod_Names[$l] . "</th>";
}
echo "</tr>";
echo "</tfoot>";
echo "</table>";
echo "</div>";
?>