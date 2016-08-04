<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/StarSonata/includes/initiate.php');
//
//	Full item list
unset($Item_Names);
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
  "Aura_Generator_Ally" => "Aura_Generators_Ally",
  "Aura_Generator_Ally_2" => "Aura_Generators_Ally_2",
  "Augmenter" => "Augmenters",
  "Controlbot" => "Controlbots",
  "Exterminator" => "Exterminators",
  "Homing_Beacon" => "Homing_Beacons"
);

//
//	Update build
if (isset($_REQUEST['submit']) and $_REQUEST['Build'] != "") {
  $Query = "UPDATE User_Builds SET Updated = NULL, Name = '" . $_REQUEST['BuildName'] . "', Character_ID = " . $_REQUEST['Character'] . ", Public = " . $_REQUEST['Public_Build'] . ", Type = " . $_REQUEST['Type'] . ", Disable = " . $_REQUEST['Disable'] . ", Temp_Mods = " . $_REQUEST['Temp_Mods'] . "  WHERE Build_ID = " . $_REQUEST['Build'] . " AND User_ID = " . $_REQUEST['User_ID'];
  $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");

  unset($Builds);
  $Query = "SELECT * FROM User_Builds WHERE User_ID = " . $_REQUEST['User_ID'];
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

  //
  //
  //	Delete Items
  $Query = "DELETE FROM User_Build_Items WHERE User_Build_ID = " . $_REQUEST['Build'] . ";";
  $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");

  //
  //
  //	Delete Mods
  $Query = "DELETE FROM User_Build_Item_Mods WHERE Build_ID = " . $_REQUEST['Build'] . ";";
  $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");

  //
  //
  //	Prepear Mod Arrays
  if (!isset($_REQUEST['Shield_Mods'])) {$_REQUEST['Shield_Mods']=array();}
  if (!isset($_REQUEST['Energy_Mods'])) {$_REQUEST['Energy_Mods']=array();}
  if (!isset($_REQUEST['Engine_Mods'])) {$_REQUEST['Engine_Mods']=array();}
  if (!isset($_REQUEST['Radar_Mods'])) {$_REQUEST['Radar_Mods']=array();}
  if (!isset($_REQUEST['Scoop_Mods'])) {$_REQUEST['Scoop_Mods']=array();}
  if (!isset($_REQUEST['Cloak_Mods'])) {$_REQUEST['Cloak_Mods']=array();}
  if (!isset($_REQUEST['Homing_Beacon_Mods'])) {$_REQUEST['Homing_Beacon_Mods']=array();}
  if (!isset($_REQUEST['Controlbot_Mods'])) {$_REQUEST['Controlbot_Mods']=array();}
  if (!isset($_REQUEST['Tractor_Mods'])) {$_REQUEST['Tractor_Mods']=array();}
  if (!isset($_REQUEST['Shield_Charger_Mods'])) {$_REQUEST['Shield_Charger_Mods']=array();}
  if (!isset($_REQUEST['Exterminator_Mods'])) {$_REQUEST['Exterminator_Mods']=array();}
  if (!isset($_REQUEST['Aura_Generator_Mods'])) {$_REQUEST['Aura_Generator_Mods']=array();}
  for ($r=1; $r<=$_REQUEST['Hull_Expander_Slots']; $r++) {
    if (!isset($_REQUEST['Hull_Expander'.$r.'_Mods'])) {$_REQUEST['Hull_Expander'.$r.'_Mods']=array();}
  }
  for ($r=1; $r<=$_REQUEST['Weapon_Slots']; $r++) {
    if (!isset($_REQUEST['Weapon'.$r.'_Mods'])) {$_REQUEST['Weapon'.$r.'_Mods']=array();}
  }
  for ($r=1; $r<=$_REQUEST['Capacitor_Slots']; $r++) {
    if (!isset($_REQUEST['Capacitor'.$r.'_Mods'])) {$_REQUEST['Capacitor'.$r.'_Mods']=array();}
  }

  //
  //
  //	Insert Items and Mod Items in User Build
  foreach ($Item_Names as $Item => $Items) {
    if (isset($_REQUEST[$Item . '_Slots']) and $_REQUEST[$Item . '_Slots'] > 0) {
      for ($r=1; $r<=$_REQUEST[$Item . '_Slots']; $r++) {
        if ($_REQUEST[$Item.$r] != "") {
          $Query = "INSERT INTO User_Build_Items (User_Build_ID, Item_Type, Item_ID, Amount) VALUES (" . $_REQUEST['Build'] . ", '" . $Item . $r . "', ".$_REQUEST[$Item.$r].", " . ($_REQUEST[$Item . $r.'_Amount'] > 1 ? $_REQUEST[$Item . $r.'_Amount'] : 1) . ");";
          $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
        }
        if (isset($_REQUEST[$Item . $r . '_Mods'])) {
          foreach ($_REQUEST[$Item.$r.'_Mods'] as $Mod_ID => $Mod_Name) {
            $Query = "INSERT INTO User_Build_Item_Mods (Build_ID, Item_Type, Item_Mod_ID) VALUES (" . $_REQUEST['Build'] . ", '" . $Item . $r . "', $Mod_Name);";
            $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
          }
        }
      }
    } else {
      if ($_REQUEST[$Item] != "") {
        $Query = "INSERT INTO User_Build_Items (User_Build_ID, Item_Type, Item_ID, Amount) VALUES (" . $_REQUEST['Build'] . ", '" . $Item . "', ".$_REQUEST[$Item].", " . ($_REQUEST[$Item.'_Amount'] > 1 ? $_REQUEST[$Item.'_Amount'] : 1) . ");";
        $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
      }
      if (isset($_REQUEST[$Item . '_Mods'])) {
        foreach ($_REQUEST[$Item . '_Mods'] as $Mod_ID => $Mod_Name) {
          $Query = "INSERT INTO User_Build_Item_Mods (Build_ID, Item_Type, Item_Mod_ID) VALUES (" . $_REQUEST['Build'] . ", '" . $Item . "', $Mod_Name);";
          $Result = mysql_query($Query, $conn) or die(mysql_error() . "<br/><br/> Query: $Query");
        }
      }
    }
  }
  echo json_encode("Success");
}
?>