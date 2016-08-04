<div id="Ship_Window" class="Ship_Window" style="display:none;border: 1px solid #EEE;margin-bottom:5px;">
		<div style="float:left;padding:0px;">
		<div style="float:left;">
			<table id="Weapons_Table" style="display:none;">
				<tr>
					<td id="Weapon_Slots_Cell" class="header" colspan="10"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:center;">T</td>
					<td class="header2">Weapon name</td>
					<td class="header2">Sustainable Crit. DPS</td>
					<td class="header2">Crit. DPS</td>
					<td class="header2">EPS</td>
					<td class="header2">DPE</td>
					<td class="header2">Damage</td>
					<td class="header2">Recoil</td>
					<td class="header2">Range</td>
					<td class="header2">Sustainable</td>
				</tr>
				<tr id="Weapon_Table_Row_Template" style="display:none;">
					<td id="W_Image" style="background-color: #2F4382; text-align:center; width:14px;"></td>
					<td id="W_Name"></td>
					<td id="W_Sustainable_Crit_DPS" style="text-align:right;"></td>
					<td id="W_Critical_DPS" style="text-align:right;"></td>
					<td id="W_EPS" style="text-align:right;"></td>
					<td id="W_DPE" style="text-align:right;"></td>
					<td id="W_Damage" style="text-align:right;"></td>
					<td id="W_Recoil" style="text-align:right;"></td>
					<td id="W_Range" style="text-align:right;"></td>
					<td id="W_Sustainable" style="text-align:right;" style="text-align:right;"></td>
				</tr>
			</table>
		</div>
		<table id="Tractor_Table" style="display:none;">
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
					<td id="T_Image1" style="background-color: #2F4382; text-align:center; width:14px;"><img style="width:9px;" src="img/SS_img/Tractor.png"/></td>
					<td id="T_Name1"></td>
					<td id="T_Strength1" style="text-align:right;" title="Tractor_Strength"></td>
					<td id="T_Density1" style="text-align:right;" title="Tractor_Density"></td>
					<td id="T_Range1" style="text-align:right;" title="Tractor_Range"></td>
					<td id="T_Electricity1" style="text-align:right;" title="Tractor_Electricity"></td>
					<td id="T_Rest_Length1" style="text-align:right;" title="Tractor_Rest_Length"></td>
					<td id="T_SPE1" style="text-align:right;" title="Tractor_SPE"></td>
					<td id="T_Sustainable1" style="text-align:right;"></td>
				</tr>
			</table>
		</div>
		<div style="float:left;width:520px;">
		<div style="float:left;">
			<table id="Ship_Information">
				<tr>
					<td class="header" colspan="2" style="text-align:center;"><b id="Ship_Name"><?php echo $Build_Items["Ship"]['Name']; ?></b></td>
				</tr>
				<tr>
					<td class="header" colspan="2" style="text-align:center;"><?php echo "Tech ".$Build_Items["Ship"]['Tech']." ".$Build_Items["Ship"]['Ship_Type']; ?></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Top speed:</td>
					<td id="Speed" class="value" title="Speed" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Hull space:</td>
					<td id="Capacity" class="value" title="Capacity" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Used space:</td>
					<td id="Used_Capacity" class="value" title="Used_Capacity" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Mass (Weight):</td>
					<td id="Weight" class="value" title="Weight" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Shield Bank:</td>
					<td id="Shield_Bank" class="value" title="Shield_Bank" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Shield Recovery:</td>
					<td id="Recovery" class="value" title="Recovery" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Energy Bank:</td>
					<td id="Energy_Bank" class="value" title="Energy_Bank" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Electricity:</td>
					<td id="Electricity" class="value" title="Electricity" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Electricity (w/Sun):</td>
					<td id="Electricity_w_sun" class="value" title="Electricity_w_sun" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Turning:</td>
					<td id="Turning" class="value" title="Turning" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Acceleration:</td>
					<td id="Acceleration" class="value" title="Acceleration" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Emissive visibility (w/Cloak):</td>
					<td id="Visibility" class="value" title="Visibility" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Reflectivity:</td>
					<td id="Reflectivity" class="value" title="Reflectivity" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Radar (Vision):</td>
					<td id="Vision" class="value" title="Vision" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:right;">Radar (Detection):</td>
					<td id="Detection" class="value" title="Detection" style="text-align:right;"></td>
				</tr>
				<tr>
					<td class="header2" style="text-align:center;color:black;" id="show_AI_damage" colspan="2">Click to show AI damage</td>
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
							$Resistance = array("Physical", "Surgical", "Radiation", "Mining", "Transference", "Heat", "Laser", "Energy");
							for ($r=0;$r<=count($Resistance)-1;$r++) {
						?>
							<tr>
							<td style="background-color: #2F4382; width:14px; text-align:center;"><img style="width:9px;position:relative;top:1px;" src="img/SS_img/Diffusers.png"/></td>
							<td style="text-align:right;"><?php echo $Resistance[$r]; ?></td>
							<td id="Resistance_<?php echo $Resistance[$r]; ?>" title="Resistance_<?php echo $Resistance[$r]; ?>" style="text-align:right;"></td>
					</tr>
						<?php } ?>
				</table>
			</div>
			<div style="float:left;">
				<table id="Temporary_Table">
					<tr>
						<td class="header" colspan="4">Temporary Bonuses</td>
					</tr>
					<tr>
						<td class="informationtd" colspan="4">Neuro Tweaking <?php echo round($Skill_Mods[41]['Value'],4)*100; ?> %</td>
					</tr>
					<tr>
						<td class="header2" style="text-align:center;">T</td>
						<td class="header2">Bonus name</td>
						<td class="header2">Value</td>
					</tr>
					<tr id="Temporary_Self_Bonus_Template" style="display:none;">
						<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"></td>
						<td style="text-align:right;"></td>
						<td style="text-align:right;"></td>
					</tr>
				</table>
			</div>
		<div style="float:left;">
				<table id="Field_Generation_Table">
					<tr>
						<td class="header" colspan="4">Field Generation (Auras)</td>
					</tr>
					<tr>
						<td class="informationtd" colspan="4">Field Generation Power <?php echo round($Skill_Mods[124]['Value'],4)*100; ?> %</td>
					</tr>
					<tr>
						<td class="header2" style="text-align:center;">T</td>
						<td class="header2">Bonus name</td>
						<td class="header2">Value</td>
						<td class="header2">Targets</td>
					</tr>
					<tr id="Temporary_Bonus_Template" style="display:none;">
						<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"></td>
						<td style="text-align:right;"></td>
						<td style="text-align:right;"></td>
						<td style="text-align:center;"></td>
					</tr>
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
				<tr id="Permanent_Bonus_Template" style="display:none;">
						<td style="background-color: #2F4382; text-align:center; width:14px;padding:0 2px 0 2px;"></td>
						<td style="text-align:left;"></td>
						<td style="text-align:right;"></td>
				</tr>
			</table>
		</div>
		<table style="width:100%; display:none;" id="AI_Damage">
			<thead>
				<tr>
					<td class="header" style="width:16%; vertical-align:middle;" rowspan="2">Ship Name</td>
					<td class="header center" style="width:12%" colspan="7">Critical DPS pr. Damage type</td>
				</tr>
				<tr>
					<td class="header center" style="width:12%">Laser</td>
					<td class="header center" style="width:12%">Energy</td>
					<td class="header center" style="width:12%">Heat</td>
					<td class="header center" style="width:12%">Physical</td>
					<td class="header center" style="width:12%">Radiation</td>
					<td class="header center" style="width:12%">Surgical</td>
					<td class="header center" style="width:12%">Mining</td>
				</tr>
			</thead>
			<tbody id="AI_Damage_Body">
			</tbody>
		</table>
	</div>