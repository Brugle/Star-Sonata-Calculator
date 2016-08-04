<script type="text/javascript">
	$(function() {
		//Ber browsere ikke fylle inn informasjon automatisk
		$("input").attr("autocomplete", "off");

		// Jquery UI på skjema knappene
		$("input[type=submit], input[type=button]").button();
		$("select").chosen({allow_single_deselect: true});

		// Setup - add a text input to each footer cell
		$('#myTable tfoot th').each( function () {
				var title = $('#myTable thead th').eq( $(this).index() ).text();
				$(this).html( '<input type="text" style="width:60px;" placeholder="Search..." />' );
		} );
		

		$.fn.dataTable.moment('DD.MM.YYYY HH:mm');
		
		var table = $('#myTable').DataTable( {
			"scrollY": '80vh',
			"scrollX": true,
			"paging": false,
			"order": [[ 5, "desc" ]]
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
	});
</script>
<?php 

$Query = "SELECT * FROM Skills ORDER BY Family_1, Family_2, Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	if (strpos($row['Name'],"_Class") !== FALSE and strpos($row['Name'],"_Classic") === FALSE) {
		$Classes[$row['Excel_Skill_ID']]['Name'] = str_replace("_"," ",$row['Name']);
		$Classes[$row['Excel_Skill_ID']]['ID'] = $row['Excel_Skill_ID'];
		$Classes[$row['Excel_Skill_ID']]['Family'] = $row['Family_2'];
	}
}

$Builds = array();

if ($_POST['Focus'] != 0) {
	$Filter .= " AND Characters.Focus = " . $_POST['Focus'] . " ";
}
if ($_POST['Class'] != 0) {
	$Filter .= "AND Characters.Class = " . $_POST['Class'] . " ";
}
if ($_SESSION['UserID'] <> 1) {
	$Public_Filter = " User_Builds.Public = 0 ";
} else {
	$Public_Filter = ' User_Builds.Public LIKE "%" ';
}
$Query = "SELECT Energy_Bank, Electricity, Shield_Bank, Recovery, DPS, Damage_Type, Speed, Avg_Resistance, Capacity, User_Builds.Public as Public, Updated as Updated_Datetime, User_Builds.Type as Type, User_Builds.Build_ID as Build_ID, User_Builds.Name as Name, Characters.Class as Class, Characters.Focus as Focus, authentication.username as username FROM User_Builds LEFT JOIN authentication ON User_Builds.User_ID = authentication.User_ID LEFT JOIN Characters ON User_Builds.User_ID = Characters.User_ID AND User_Builds.Character_ID = Characters.Character_ID WHERE " . $Public_Filter . $Filter . " ORDER BY User_Builds.Updated DESC, authentication.username, User_Builds.Name";
$Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/>Query: " . $Query); 
while ($row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	$Builds[$row['Build_ID']]['Build_ID'] = $row['Build_ID'];
	$Builds[$row['Build_ID']]['Name'] = ($_SESSION['UserID'] == 1 ? ($row['Public'] == 1 ? "(Private) " : "(Public) ") : "") . $row['Name'];
	$Builds[$row['Build_ID']]['Class'] = $row['Class'];
	$Builds[$row['Build_ID']]['Focus'] = $row['Focus'];
	$Builds[$row['Build_ID']]['username'] = $row['username'];
	$Builds[$row['Build_ID']]['public'] = $row['Public'];
	$Builds[$row['Build_ID']]['Updated'] = strtotime($row['Updated_Datetime']);
	if ($row['Type'] == 0) {
		$Builds[$row['Build_ID']]['Type'] = "Main";
	} else if ($row['Type'] == 1) {
		$Builds[$row['Build_ID']]['Type'] = "Combat Slave";
	} else if ($row['Type'] == 2) {
		$Builds[$row['Build_ID']]['Type'] = "Trade Slave";
	} else if ($row['Type'] == 3) {
		$Builds[$row['Build_ID']]['Type'] = "Base";
	}
	
	$Builds[$row['Build_ID']]['Energy_Bank'] = $row['Energy_Bank'];
	$Builds[$row['Build_ID']]['Electricity'] = $row['Electricity'];
	$Builds[$row['Build_ID']]['Shield_Bank'] = $row['Shield_Bank'];
	$Builds[$row['Build_ID']]['Recovery'] = $row['Recovery'];
	$Builds[$row['Build_ID']]['DPS'] = $row['DPS'];
	$Builds[$row['Build_ID']]['Damage_Type'] = $row['Damage_Type'];
	$Builds[$row['Build_ID']]['Speed'] = $row['Speed'];
	$Builds[$row['Build_ID']]['Avg_Resistance'] = $row['Avg_Resistance'];
	$Builds[$row['Build_ID']]['Capacity'] = $row['Capacity'];

	if ($row['Type'] != 3) {
		$Query2 = "SELECT * FROM User_Build_Items LEFT JOIN Ships ON User_Build_Items.Item_ID = Ships.Ship_ID WHERE User_Build_ID = " . $row['Build_ID'] . " AND Item_Type = 'Ship'";
		$Result2 = mysql_query($Query2, $conn) or die(mysql_error() . "<br/>Query: " . $Query2); 
		while ($row2 = mysql_fetch_array($Result2, MYSQL_ASSOC)) {
			$Builds[$row['Build_ID']]['Ship_Name'] = $row2['Name'];
		}
	} else if ($row['Type'] == 3) {
		$Query2 = "SELECT * FROM User_Build_Items LEFT JOIN Bases ON User_Build_Items.Item_ID = Bases.Base_ID WHERE User_Build_ID = " . $row['Build_ID'] . " AND Item_Type = 'Base'";
		$Result2 = mysql_query($Query2, $conn) or die(mysql_error() . "<br/>Query: " . $Query2); 
		while ($row2 = mysql_fetch_array($Result2, MYSQL_ASSOC)) {
			$Builds[$row['Build_ID']]['Ship_Name'] = $row2['Name'];
		}
	}
}



if (isset($_POST)) {
	
}
?>
<table id="RF1199_FormatTable" style="width:98%;margin:0 auto;">
	<tr>
		<td>
					<table id="myTable" class="display compact" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Account name</th>
								<th>Build name</th>
								<th>Class</th>
								<th>Ship</th>
								<th>Type</th>
								<th>Last updated</th>
								<th>Energy Bank</th>
								<th>Electricity</th>
								<th>Shield Bank</th>
								<th>Recovery</th>
								<th>Max(Crit. DPS)</th>
								<th>Damage type</th>
								<th>Speed</th>
								<th>Avg(Resistance)</th>
								<th>Hull space</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Account name</th>
								<th>Build name</th>
								<th>Class</th>
								<th>Ship</th>
								<th>Type</th>
								<th>Last updated</th>
								<th>Energy Bank</th>
								<th>Electricity</th>
								<th>Shield Bank</th>
								<th>Recovery</th>
								<th>Max(Crit. DPS)</th>
								<th>Damage type</th>
								<th>Speed</th>
								<th>Avg(Resistance)</th>
								<th>Hull space</th>
							</tr>
						</tfoot>
					<tbody>
						<?php 
						$r=0;
						foreach ($Builds as $Build_ID => $Build_array) {
							if ($Build_array['Ship_Name'] != "") {
								echo '<tr>';
								echo "<td>" . $Build_array['username'] . "</td>";
								if ($Build_array['public'] == 1 and $_SESSION['UserID'] <> 1) {
									echo '<td>' . $Build_array['Name'] . "</td>";
								} else {
									echo '<td><a href="?module=skills&amp;content=' . ($Build_array['Type'] == "Base" ? "Station_" : "") . 'Calculator&amp;Build_ID=' . $Build_array['Build_ID'] . '">' . $Build_array['Name'] . "</a></td>";
								}
								echo "<td>" . $Classes[$Build_array['Class']]['Name'] . "</td>";
								echo "<td>" . $Build_array['Ship_Name'] . "</td>";
								echo '<td style="text-align:center;">' . ($Build_array['Type'] == "Main" ? "Main ship" : $Build_array['Type']) . "</td>";
								echo '<td style="text-align:center;">' . (date( 'Y', $Build_array['Updated'])!="-0001" ? date( 'd.m.Y H:i', $Build_array['Updated']) : "") . "</td>";
								echo '<td style="text-align:right;">' . number_format($Build_array['Energy_Bank'],0,".",",") . '</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['Electricity'],0,".",",") . '</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['Shield_Bank'],0,".",",") . '</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['Recovery'],0,".",",") . '</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['DPS'],0,".",",") . '</td>';
								echo '<td style="text-align:right;">' . $Build_array['Damage_Type'] . '</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['Speed'],0,".",",") . '</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['Avg_Resistance']*100,0,".",",") . ' %</td>';
								echo '<td style="text-align:right;">' . number_format($Build_array['Capacity'],0,".",",") . '</td>';								
								echo "</tr>";
							}
							$r++;
						}
						?>
					</tbody>
					</table>
