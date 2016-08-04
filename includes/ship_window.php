<div id="Ship_Window" class="Ship_Window">
		<div style="float:left;padding:0px;">
		<div style="float:left;">
			<table id="Weapons_Table">
				<tr>
					<td class="header" colspan="10">Weapon stats [Weapon slots: <?php echo $N_Weapons . "/". $Ship_Weapon_Slots . "]"; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:center;">T</td>
					<td class="header2">Weapon name</td>
					<td class="header2">Sustainable (w/Sun)</td>
					<td class="header2">DPS (DPS w/crit)</td>
					<td class="header2">Electricity</td>
					<td class="header2">DPE</td>
					<td class="header2">Damage</td>
					<td class="header2">Recoil</td>
					<td class="header2">Range</td>
					<td class="header2">Sustainable</td>
				</tr>
				<?php 
	$m=0;
	for ($r=1;$r<=$Ship_Weapon_Slots;$r++) {
		if ($Build_Items["Weapon" . $r]['Name'] != "") {
?>
				<tr>
					<td style="background-color: #2F4382; text-align:center; width:14px;"><img style="width:9px;" src="img/SS_img/<?php echo $Build_Items["Weapon" . $r]['Type']; ?>-Damage.png"/></td>
					<td><?php echo ($Build_Items["Weapon" . $r]['Multifire'] > 1 ? "[Multifire x" . $Build_Items["Weapon" . $r]['Multifire'] . "] " . $Build_Items["Weapon" . $r]['Name'] : $Build_Items["Weapon" . $r]['Name']); ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_Sustainable_DPS<?php echo $r; ?>_Tooltip"><?php echo number_format($Calculated_Sustainable_DPS[$r],0,","," ") . " (" . number_format($Calculated_Sustainable_DPS_wSun[$r],0,","," ") . ")"; ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_DPS<?php echo $r; ?>_Tooltip"><?php echo number_format($Calculated_DPS[$r],0,","," ") . " (" . number_format($Calculated_Crit_DPS[$r],0,","," ") . ")"; ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_Electricity<?php echo $r; ?>_Tooltip"><?php echo number_format($Calculated_Weapon_Electricity[$r],0,","," ");; ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_DPE<?php echo $r; ?>_Tooltip"><?php echo number_format($Calculated_DPE[$r],2,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_Damage<?php echo $r; ?>_Tooltip"><?php echo floor($Calculated_Damage[$r]); ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_Recoil<?php echo $r; ?>_Tooltip"><?php echo number_format($Calculated_Weapon_Recoil[$r],2,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_Range<?php echo $r; ?>_Tooltip"><?php echo number_format($Calculated_Range[$r],0,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Weapon_Sustainable<?php echo $r; ?>_Tooltip" style="text-align:right;"><?php echo (is_numeric($Calculated_Sustainable[$r]) ? number_format($Calculated_Sustainable[$r],0,","," ") . " sec" : $Calculated_Sustainable[$r]); ?></td>
				</tr>
				<?php 
		}
	} 
				?>
			</table>
		</div>
			<?php if ($Build_Items["Tractor"]['Name'] != "") { ?>
<table id="Tractor_Table">
				<tr>
					<td class="header" colspan="10">Tractor stats</td>
				</tr>
				<tr>
					<td class="header2" style="text-align:center;">T</td>
					<td class="header2">Tractor name</td>
					<td class="header2">Strength</td>
					<td class="header2">Density</td>
					<td class="header2">Range</td>
					<td class="header2">Electricity</td>
					<td class="header2">Rest Length</td>
					<td class="header2">SPE</td>
					<td class="header2">Sustainable</td>
				</tr>
				<tr>
					<td style="background-color: #2F4382; text-align:center; width:14px;"><img style="width:9px;" src="img/SS_img/Tractor.png"/></td>
					<td><?php echo $Build_Items["Tractor"]['Name']; ?></td>
					<td style="text-align:right;" title="Calculated_Tractor_Strength_Tooltip"><?php echo number_format($Build_Items["Tractor"]['Strength'],0,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Tractor_Density_Tooltip"><?php echo number_format($Build_Items["Tractor"]['Density'],2,","," ");; ?></td>
					<td style="text-align:right;" title="Calculated_Tractor_Range_Tooltip"><?php echo number_format($Build_Items["Tractor"]['T_Range'],0,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Tractor_Electricity_Tooltip"><?php echo number_format($Build_Items["Tractor"]['Electricity'],0,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Tractor_Rest_Length_Tooltip"><?php echo number_format($Build_Items["Tractor"]['Rest_Length'],0,","," "); ?></td>
					<td style="text-align:right;" title="Calculated_Tractor_SPE_Tooltip"><?php echo number_format($Build_Items["Tractor"]['SPE'],2,","," "); ?></td>
					<td style="text-align:right;"><?php echo (is_numeric($Build_Items["Tractor"]['Sustainable']) ? number_format($Build_Items["Tractor"]['Sustainable'],1,","," ") . " sec" : $Build_Items["Tractor"]['Sustainable']); ?></td>
				</tr>
			</table>
		</div>
				<?php } ?>
		<div style="float:left;width:520px;">
		<div style="float:left;">
			<table id="Ship_Information">
				<tr>
					<td class="header" colspan="2" style="text-align:center;"><b><?php echo $Build_Items["Ship"]['Name']; ?></b></td>
				</tr>
				<tr>
					<td class="header" colspan="2" style="text-align:center;"><?php echo "Tech ".$Build_Items["Ship"]['Tech']." ".$Build_Items["Ship"]['Ship_Type']; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Top speed:</td>
					<td class="value" title="Calculated_Speed_Tooltip" style="text-align:right;"><?php echo number_format(floor($Calculated_Speed),0,","," "); ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Hull space:</td>
					<td class="value" title="Calculated_Capacity_Tooltip" style="text-align:right;"><?php echo number_format($Total_Size,0,","," ") . " / " . number_format(floor($Calculated_Hull_Space),0,","," ");	?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Mass (Weight):</td>
					<td class="value" title="Calculated_Weight_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Weight,0,","," "); ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Shield Bank:</td>
					<td class="value" title="Calculated_Shield_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Shield_Bank,0,","," "); ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Shield Recovery:</td>
					<td class="value" title="Calculated_Recovery_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Recovery,0,","," ") . " /s"; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Energy Bank:</td>
					<td class="value" title="Calculated_Energy_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Energy_Bank,0,","," "); ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Electricity:</td>
					<td class="value" title="Calculated_Electricity_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Electricity,0,","," ") . " /s"; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Electricity (w/Sun):</td>
					<td class="value" title="Calculated_Electricity_w_sun_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Electricity_Sun,0,","," ") . " /s"; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Turning:</td>
					<td class="value" title="Calculated_Turning_Tooltip" style="text-align:right;"><?php echo number_format(floor($Calculated_Turning),0,","," ") . " degrees/s"; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Acceleration:</td>
					<td class="value" title="Calculated_Acceleration_Tooltip" style="text-align:right;"><?php echo number_format(floor($Calculated_Acceleration),0,","," ") . " /s^2"; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Emissive visibility (w/Cloak):</td>
					<td class="value" title="Calculated_Visibility_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Visibility,0,","," "); ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Reflectivity:</td>
					<td class="value" title="Calculated_Reflectivity_Tooltip" style="text-align:right;"><?php echo number_format($Build_Items["Ship"]['Reflectivity']*100,0,","," "); ?> %</td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Radar (Vision):</td>
					<td class="value" title="Calculated_Vision_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Vision,2,","," "); ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Radar (Detection):</td>
					<td class="value" title="Calculated_Detection_Tooltip" style="text-align:right;"><?php echo number_format($Calculated_Detection,0,","," "); ?></td>
				</tr>
			</table>
		</div>
		<div style="display:table;">
			<div style="float:left;">
				<table id="Resistance_Table">
					<tr>
						<td class="header" colspan="4">Resistances</td>
					</tr>
					<tr>
						<td class="header2" style="text-align:center;">T</td>
						<td class="header2">Resistances</td>
						<td class="header2">Value</td>
					</tr>
						<?php
							for ($r=1;$r<=count($Resistance);$r++) {
						?>
							<tr>
							<td style="background-color: #2F4382; width:14px; text-align:center;"><img style="width:9px;position:relative;top:1px;" src="img/SS_img/Diffusers.png"/></td>
							<td style="text-align:right;"><?php echo $Resistance[$r]['Name']; ?></td>
							<td title="Calculated_Resistance_<?php echo $Resistance[$r]['Name']; ?>_Tooltip" style="text-align:right;"><?php echo floor($Resistance[$r]['Value']); ?> %</td>
					</tr>
						<?php } ?>
				</table>
			</div>
				<?php if (count($Calculated_Temp_Bonus) != 0) { ?>
			<div style="float:left;">
				<table id="Temporary_Table">
					<tr>
						<td class="header" colspan="4">Temporary Bonuses</td>
					</tr>
					<tr>
						<td class="informationtd" colspan="4">Temporary bonus (On/Off): <input style="margin:0; padding:0; height:9px;" type="checkbox" id="Temp_Bonus_State" <?php if ($Include_Temp_Bonus === TRUE) {echo 'checked="checked"';} ?> /></td>
					</tr>
					<tr>
						<td class="informationtd" colspan="4">Neuro Tweaking <?php echo round($Skill_Mods[41]['Value'],4)*100; ?> %</td>
					</tr>
					<tr>
						<td  class="header2" style="text-align:center;">T</td>
						<td class="header2">Bonus name</td>
						<td class="header2">Value</td>
					</tr>
						<?php
							for ($r=1; $r<=6; $r++) {
								if ($Calculated_Temp_Bonus[$r]['Value'] <> 0 and $Calculated_Temp_Bonus[$r]['Self'] == "Yes") {
									if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
						?>
						<tr>
							<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"><img style="width:12px;position:relative;top:2px;" src="img/SS_img/<?php echo ($Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] != "" ? $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] : "Hullspace");?>.png"/></td>
							<td style="text-align:right;"><?php echo $Calculated_Temp_Bonus[$r]['Name']; ?></td>
							<td title="Temp_Mods<?php echo $Calculated_Temp_Bonus[$r]['Name']; ?>_Calculation" style="text-align:right;"><?php echo round($Calculated_Temp_Bonus[$r]['Value']*100,2); ?> %</td>
						</tr>
								<?php } else {
						?>
						<tr>
							<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"><img style="width:12px;position:relative;top:2px;" src="img/SS_img/<?php echo ($Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] != "" ? $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] : "Hullspace");?>.png"/></td>
							<td style="text-align:right;"><?php echo substr($Calculated_Temp_Bonus[$r]['Name'], 5); ?></td>
							<td title="Temp_Mods<?php echo $Calculated_Temp_Bonus[$r]['Name']; ?>_Calculation" style="text-align:right;"><?php echo round($Calculated_Temp_Bonus[$r]['Value'],2); ?></td>
						</tr>
								<?php	
									} } } ?>
				</table>
			</div>
				<?php } ?>
		<div style="float:left;">
				<table id="Field_Generation_Table">
					<tr>
						<td class="header" colspan="4">Field Generation (Auras)</td>
					</tr>
					<tr>
						<td class="informationtd" colspan="4">Field Generation Power <?php echo round($Skill_Mods[124]['Value'],4)*100; ?> %</td>
					</tr>
					<tr>
						<td  class="header2" style="text-align:center;">T</td>
						<td class="header2">Bonus name</td>
						<td class="header2">Value</td>
						<td class="header2">Targets</td>
					</tr>
						<?php
							for ($r=1; $r<=6; $r++) {
								if ($Calculated_Temp_Bonus[$r]['Value'] <> 0 and $Calculated_Temp_Bonus[$r]['Targets'] != "Self") {
									if (strpos($Calculated_Temp_Bonus[$r]['Name'],"Bonus") === FALSE) {
						?>
						<tr>
							<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"><img style="width:12px;position:relative;top:2px;" src="img/SS_img/<?php echo ($Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] != "" ? $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] : "Hullspace");?>.png"/></td>
							<td style="text-align:right;"><?php echo $Calculated_Temp_Bonus[$r]['Name']; ?></td>
							<td title="Temp_Mods<?php echo $Calculated_Temp_Bonus[$r]['Name']; ?>_Calculation" style="text-align:right;"><?php echo round($Calculated_Temp_Bonus[$r]['Value'],3)*100; ?> %</td>
							<td style="text-align:center;"><?php echo $Calculated_Temp_Bonus[$r]['Targets']; ?></td>
						</tr>
								<?php } else {
						?>
						<tr>
							<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"><img style="width:12px;position:relative;top:2px;" src="img/SS_img/<?php echo ($Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] != "" ? $Mods[$Calculated_Temp_Bonus[$r]['ID']]['Img'] : "Hullspace");?>.png"/></td>
							<td style="text-align:right;"><?php echo substr($Calculated_Temp_Bonus[$r]['Name'],5); ?></td>
							<td title="Temp_Mods<?php echo $Calculated_Temp_Bonus[$r]['Name']; ?>_Calculation" style="text-align:right;"><?php echo $Calculated_Temp_Bonus[$r]['Value']; ?></td>
							<td style="text-align:center;"><?php echo $Calculated_Temp_Bonus[$r]['Targets']; ?></td>
						</tr>
								<?php	
									} } } ?>
				</table>
			</div>
			</div>
		</div>
		<div style="float:left;">
			<table id="Permanent_Bonus_Table">
				<tr>
					<td class="header" colspan="3">Permanent Bonuses</td>
				</tr>
				<tr>
					<td title="Augmenter_Effect_Calculation" class="informationtd" colspan="3">Augmenter Tweaking <?php echo round($Skill_Mods[40]['Value'],4)*100; ?> %</td>
				</tr>
				<tr>
					<td class="header2" style="text-align:center;">T</td>
					<td class="header2">Effect</td>
					<td class="header2">Value</td>
				</tr>
					<?php
						foreach ($Mods as $Mod_ID => $Mod_Name) {
							if ($Mods[$Mod_ID]['Value']<>1 and strpos($Mods[$Mod_ID]['Name'],"Capital") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Fighter") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Freighter") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Augmenter") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Slave") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Resistance_") === FALSE and $Mod_ID <> 910 and strpos($Mods[$Mod_ID]['Name'],"Bonus") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Neuro") === FALSE  and strpos($Mods[$Mod_ID]['Name'],"Station") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Permanent") === FALSE and strpos($Mods[$Mod_ID]['Name'],"Projectil") === FALSE)  {
					?>
				<tr>
						<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"><img style="width:12px;position:relative;top:2px;" src="img/SS_img/<?php echo ($Mods[$Mod_ID]['Img'] != "" ? $Mods[$Mod_ID]['Img'] : "Hullspace");?>.png"/></td>
						<td style="text-align:left;"><?php echo $Mods[$Mod_ID]['Name']; ?></td>
						<td title="<?php echo str_replace(" ","_", $Mods[$Mod_ID]['Name']); ?>_Calculation" style="text-align:right;"><?php echo number_format(round($Mods[$Mod_ID]['Value']-1,5)*100,2,","," "); ?> %</td>
				</tr>
				<?php 		
							} else if ((strpos($Mods[$Mod_ID]['Name'],"Bonus") !== FALSE or strpos($Mods[$Mod_ID]['Name'],"Projectil") !== FALSE) and $Mods[$Mod_ID]['Value'] <> 0 and strpos($Mods[$Mod_ID]['Name'],"Slave") === FALSE) {
				?>
				<tr>
						<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"><img style="width:12px;position:relative;top:2px;" src="img/SS_img/<?php echo ($Mods[$Mod_ID]['Img'] != "" ? $Mods[$Mod_ID]['Img'] : "Hullspace");?>.png"/></td>
						<td style="text-align:left;"><?php echo str_replace("Bonus", "Max",$Mods[$Mod_ID]['Name']); ?></td>
						<td title="<?php echo str_replace(" ","_", $Mods[$Mod_ID]['Name']); ?>_Calculation" style="text-align:right;"><?php echo number_format(round($Mods[$Mod_ID]['Value'],5),0,","," "); ?></td>
				</tr>
				<?php
							}
						} 
				?>
			</table>
		</div>
	</div>
	</div>
<pre><?php 
//print_r($Mods);
?></pre>

<script type="text/javascript">
<?php 
		foreach ($Mods as $Mod_ID => $Mod_Name) {
?>
	$('td[title=<?php echo str_replace(" ", "_", $Mods[$Mod_ID]['Name']); ?>_Calculation]').qtip({
		content: {
			text:'<?php echo  str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . " multiplier: " . number_format($Mods[$Mod_ID]['Value'],3) . "<br/><br/>" . str_replace(" ", "_", $Mods[$Mod_ID]['Name']) . " calculation: " . $Mods[$Mod_ID]['ToT_Calculation'] . "<br/><br/>"; for ($r=0; $r<= ${$Mod_ID . "r"}; $r++) { echo str_replace("'","",$Mods[$Mod_ID]['Calculation_'.$r]) . "<br/>";} ?>',
		title: '<?php echo  str_replace("_", " " , $Mods[$Mod_ID]['Name']); ?> calculation!'
		},
		position: {
			my: 'top right',  // Position my top left...
			at: 'top left', // at the bottom right of...
			target: $('td[title=<?php echo str_replace(" ", "_", $Mods[$Mod_ID]['Name']); ?>_Calculation]') // my target
		}
	});
	<?php } ?>
<?php 
		foreach ($Calculated_Tooltip as $Stat_Name => $Stat_Part) {
?>
	$('td[title=Calculated_<?php echo $Stat_Name; ?>_Tooltip]').qtip({
		content: {
			text:'<?php foreach ($Stat_Part as $Line_Number => $Line_Text) {echo str_replace("'","",$Line_Text) . "<br/>";} ?>',
			title: '<?php echo $Stat_Name; ?> calculation!'
		},
		position: {
			my: 'top right',  // Position my top left...
			at: 'top left', // at the bottom right of...
			target: $('td[title=Calculated_<?php echo $Stat_Name; ?>_Tooltip]') // my target
		}
	});
	<?php } ?>
</script>