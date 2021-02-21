<?php
include "settings.php";
?>
<link rel="stylesheet" href="./css/main.css" />
<?php
if(!$sqlite) {
    $conn = new mysqli($url, $user, $pass, $db, $port);
    if($conn -> connect_errno) {
        echo $lang_err_connfail;
        exit();
    }
} else {
    $db = new SQLite3($dbfile);
}
$id = 99999;
foreach ($_GET as $key => $value) {
    $formatted = $value;
    if($key !== "image") {
        $formatted = substr($formatted, 0, 128);
    }
    if($key !== "id") {
        if(in_array($key, $required)) {
            if(strictEmpty($value)) {
                $formatted = 0;
            }
        }
        if($key == "name" && $value == "") {
            $formatted = "Unnamed";
        }
        $sql = "UPDATE `".$catalog."` SET `".$key."`='".$formatted."' WHERE id=".$id.";";
        if(!$sqlite) {
            $result = $conn->query($sql);
        } else {
            $result = $db->query($sql);
        }
    } else {
        $id = $value;
    }
    print_R($key.': '.$formatted."<br />");
}
$sql = "UPDATE `".$catalog."` SET `setup`=1 WHERE id=".$id.";";
if(!$sqlite) {
    $result = $conn->query($sql);
} else {
    $result = $db->query($sql);
}
header('Location: ./');
?>