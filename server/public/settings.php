<?php
/////////////////
//// General ////
/////////////////

$apptitle = "Filament Organizer";
$version = "v1.0";
$rolling = true;

//////////////////
//// Database ////
//////////////////

// If you want to use SQLite instead of MySQL enable this.
$sqlite = true;

if(!$sqlite) {
    $url = "127.0.0.1";
    $port = "3306";
    // Database to be used
    $db = "prusa";
    // User with priviliges to the DB
    $user = "user";
    $pass = "pass";
} else {
    // Don't touch this if you don't need to.
    $dbfile = "../private/database.db";
}

// Catalog Table
$catalog = "catalog";
// Current Status Table
$status = "status";

// Required values on the edit page (Don't mess with it unless you know what you're doing)
$required = array('diameter', 'nozzleTemp', 'bedTemp');
$noreturn = array('setup', 'nfcid', 'id', 'addDate', 'weight', 'currentWeight', 'image');

//////////////////////
//// Localization ////
//////////////////////

$unit_weight = "kg";
$unit_weight_str = "Kilograms";
$unit_temp = "°C";
$unit_temp_str = "Celsius";
$unit_humidity = "%";
$unit_humidity_str = "%";
$unit_length = "cm";
$unit_length_str = "Centimeters";

$lang_profileActive = "Active Profile";
$lang_profileUnknown = "Unknown Profiles";
$lang_profileKnown = "Profiles";
$lang_newItem = "New Item";
$lang_back = ".. (Back)";
$lang_unconfigured = "You need to configure this item with a profile to use. You can configure it by clicking here.";
$lang_edit = "Edit Profile";
$lang_save = "Save Config";
$lang_remove = "Remove";
$lang_resetweight = "Reset Weight";
$lang_empty = "No information yet.";

$lang_ok = "OK";

$lang_err_rolling = "This option is prohibited in a rolling release. If you think this is an error, please contact me on the GitHub issues page.";
$lang_err_undefined = "Required variables unset. Please check you syntax. If you think this is an error, please contact me on the GitHub issues page.";
$lang_err_connfail = "Connection to the database failed. Please check the connection details and try again.";
$lang_err_db = "Database misconfiguration error. Please contact your administrator.";
$lang_err_desync = "Database desync error. This error usually goes away after a retry.";
$lang_err_sqlite = "This action is not supported in SQLite, please use MySQL for this.";
$lang_err_foreign = "Operation not supported.";

///////////////////
//// Functions ////
///////////////////

if($rolling) {
    error_reporting(0);
}

function strictEmpty($var) {
    $var = trim($var);
    if(isset($var) === true && $var === '') {
        return true;
    }
    else {
        return false;
    }
}

function returnLanguage($var1,$var2,$var3) {
    $unlocalized = array('<weight_str>','<length_str>','<temp_str>'); 
    $localized = array($var1,$var2,$var3);
    return str_replace($unlocalized,$localized,file_get_contents('../private/language.json'));
}
?>
