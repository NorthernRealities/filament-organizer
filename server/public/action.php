<?php
include "settings.php";
if(!$sqlite) {
    $conn = new mysqli($url, $user, $pass, $db, $port);
    if($conn -> connect_errno) {
        echo $lang_err_connfail;
        exit();
    }
} else {
    $db = new SQLite3($dbfile);
}
?>
<?php
$action = $_GET["action"];
$date = date_create();
$now = date_timestamp_get($date);
if(!$sqlite) {
// Status table
$sql = "SHOW TABLES LIKE '".$status."';";
$result = $conn->query($sql);
if(!($result->num_rows > 0)) {
	// Table doesn't exist, create it.
	$sql = "CREATE TABLE `".$status."` ( `nfcid` text NOT NULL, `weight` float NOT NULL, `humidity` float NOT NULL, `temp` float NOT NULL, `active` tinyint(1) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	$result = $conn->query($sql);
}
// Catalog table
$sql = "SHOW TABLES LIKE '".$catalog."';";
$result = $conn->query($sql);
if(!($result->num_rows > 0)) {
	// Table doesn't exist, create it.
	$sql = "CREATE TABLE `".$catalog."` ( `id` int(11) NOT NULL AUTO_INCREMENT, `nfcid` text DEFAULT NULL, `name` text NOT NULL, `image` longtext NOT NULL, `color` text NOT NULL, `weight` float NOT NULL, `currentWeight` float NOT NULL, `material` text NOT NULL, `diameter` float NOT NULL, `manufacturer` text NOT NULL, `model` text NOT NULL, `nozzleTemp` float NOT NULL, `bedTemp` float NOT NULL, `addDate` bigint(20) NOT NULL, `comment` text NOT NULL, `setup` tinyint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4";
	$result = $conn->query($sql);
}
} else {
$sql = "CREATE TABLE if not exists `".$status."` ( `nfcid` text NOT NULL, `weight` float NOT NULL, `humidity` float NOT NULL, `temp` float NOT NULL, `active` tinyint(1) NOT NULL );";
$result = $db->querySingle($sql);
$sql = "CREATE TABLE if not exists `".$catalog."` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT, `nfcid` text DEFAULT NULL, `name` text, `image` longtext, `color` text, `weight` float, `currentWeight` float, `material` text, `diameter` float, `manufacturer` text, `model` text, `nozzleTemp` float, `bedTemp` float, `addDate` bigint(20), `comment` text, `setup` tinyint(1) DEFAULT 0 );";
$result = $db->querySingle($sql);
}
switch($action) {
	case 0:
		// [DEPRECATED/NOT SUPPORTED/DON'T USE] Insert item into catalog if it doesn't already exist (Now replaced by Action#6)
		if(!$rolling) {
		if(!$sqlite) {
		if(!isset($_GET["nfc"])) {
			exit($lang_err_undefined);
		} else {
			$nfc = $_GET["nfc"];
			$sql = "SELECT * FROM `".$catalog."` WHERE `nfcid` = '".$nfc."'";
			$result = $conn->query($sql);
			if($result->num_rows > 0) {
				// It already exists.
			} else {
				$sql = "INSERT INTO `".$catalog."`(`nfcid`,`addDate`) VALUES ('".$nfc."', ".$now.")";
				$result = $conn->query($sql);
				// It exists now.
			}
			echo $nfc;
		}
		} else {
		exit($lang_err_sqlite);
		}
		} else {
		exit($lang_err_rolling);
		}
		break;
	case 1:
		// Return info about a profile (human readable)
		if(!$rolling) {
		if(!$sqlite) {
		if(!isset($_GET["id"])) {
			exit($lang_err_undefined);
		} else {
			$id = $_GET["id"];
			$sql = "SELECT * FROM `".$catalog."` WHERE `id` = '".$id."'";
			$result = $conn->query($sql);
			if($result->num_rows > 0) {
				$rows = array();
				while($r = mysqli_fetch_assoc($result)) {
    				$rows[] = $r;
				}
				foreach($rows[0] as $key=>$value) {
					print_r($key.': '.$value."<br />");
				}
			} else {
				exit($lang_err_db);
			}
		}
		} else {
		exit($lang_err_sqlite);
		}
		} else {
		exit($lang_err_rolling);
		}
		break;
	case 2:
		// Return info about a profile
		if(!isset($_GET["id"])) {
			exit($lang_err_undefined);
		} else {
			$id = $_GET["id"];
			$sql = "SELECT * FROM `".$catalog."` WHERE `id` = '".$id."'";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$check = $result->num_rows > 0;
			} else {
				$rows = 0;
				while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    				$rows += 1;
				}
				$check = $rows > 0;
			}
			if($check) {
				$rows = array();
				if(!$sqlite) {
					while($r = mysqli_fetch_assoc($result)) {
						$rows[] = $r;
					}
					} else {
					while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
						$rows[] = $row;
					}
				}
				foreach($noreturn as $val) {
					if($val !== "image") {
						unset($rows[0][$val]);
					}
				}
				foreach($rows[0] as $key => $link) 
				{ 
    				if($link === null) 
    				{ 
        				$rows[0][$key] == "";
    				} 
				} 
				print_r(json_encode($rows));
			} else {
				exit($lang_err_db);
			}
		}
		break;
	case 21:
		// Return info about profile
		if(!isset($_GET["id"])) {
			exit($lang_err_undefined);
		} else {
			$id = $_GET["id"];
			$sql = "SELECT * FROM `".$catalog."` WHERE `id` = '".$id."'";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$check = $result->num_rows > 0;
			} else {
				$rows = 0;
				while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    				$rows += 1;
				}
				$check = $rows > 0;
			}
			if($check) {
				$rows = array();
				if(!$sqlite) {
				while($r = mysqli_fetch_assoc($result)) {
					$rows[] = $r;
				}
				} else {
				while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
					$rows[] = $row;
				}
				}
				foreach($noreturn as $val) {
					unset($rows[0][$val]);
				}
				unset($rows[0]['name']);
				foreach($rows[0] as $key => $link) 
				{ 
    				if($link == '' || $link == '0' || $link == null) 
    				{ 
        				unset($rows[0][$key]); 
    				} 
				} 
				print_r(json_encode($rows));
			} else {
				exit($lang_err_db);
			}
		}
		break;
	case 3:
		// Return a language variable
		if(!$rolling) {
		if(!isset($_GET["var"])) {
			exit($lang_err_undefined);
		} else {
			$var = 'lang_'.$_GET["var"];
			print_r($$var);
		}
		} else {
		exit($lang_err_rolling);
		}
		break;
	case 4:
		// Delete profile
		if(!isset($_GET["id"])) {
			exit($lang_err_undefined);
		} else {
			$id = $_GET["id"];
			$sql = "SELECT * FROM `".$status."` WHERE 1";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$statusRes = $result -> fetch_array(MYSQLI_ASSOC);
			} else {
				$statusRes = $result->fetchArray(SQLITE3_ASSOC);
			}
			$sql = "SELECT * FROM `".$catalog."` WHERE `id`=".$id."";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$catalogRes = $result -> fetch_array(MYSQLI_ASSOC);
			} else {
				$catalogRes = $result->fetchArray(SQLITE3_ASSOC);
			}
			if($statusRes['nfcid'] == $catalogRes['nfcid']) {
				$sql = "UPDATE `".$status."` SET `active`=0 WHERE 1";
				if(!$sqlite) {
					$result = $conn->query($sql);
				} else {
					$result = $db->query($sql);
				}
			}
			$sql = "DELETE FROM `".$catalog."` WHERE `id`=".$id."";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			echo $lang_ok;
			header('Location: ./');
		}
		break;
	case 5:
		// Reset weight
		if(!isset($_GET["id"])) {
			exit($lang_err_undefined);
		} else {
			$id = $_GET["id"];
			$sql = "SELECT * FROM `".$catalog."` WHERE `id` = '".$id."';";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$check = $result->num_rows > 0;
			} else {
				$rows = 0;
				while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    				$rows += 1;
				}
				$check = $rows > 0;
			}
			if($check) {
				if(!$sqlite) {
					$row = $result -> fetch_array(MYSQLI_ASSOC);
				} else {
					$row = $result->fetchArray(SQLITE3_ASSOC);
				}
				$sql = "UPDATE `".$catalog."` SET `weight`=".$row['currentWeight']." WHERE `id`=".$id.";";
				if(!$sqlite) {
					$result = $conn->query($sql);
				} else {
					$result = $db->query($sql);
				}
				echo $lang_ok;
				header('Location: ./');
			} else {
				exit($lang_err_db);
			}
		}
		break;
	case 6:
		// ESP sent info, save it to database
		if(!isset($_GET["nfc"]) || !isset($_GET["temp"]) || !isset($_GET["humidity"]) || !isset($_GET["weight"])) {
			exit($lang_err_undefined);
		} else {
			$nfc = $_GET["nfc"];
			$temp = $_GET["temp"];
			$humidity = $_GET["humidity"];
			$weight = $_GET["weight"];
			$sql = "SELECT * FROM `".$catalog."` WHERE `nfcid` = '".$nfc."';";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$check = $result->num_rows > 0;
			} else {
				$rows = 0;
				while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    				$rows += 1;
				}
				$check = $rows > 0;
			}
			if($check) {
				// It already exists.
				$sql = "UPDATE `".$catalog."` SET `currentWeight`=".$weight." WHERE `nfcid` = '".$nfc."';";
				if(!$sqlite) {
					$result = $conn->query($sql);
				} else {
					$result = $db->query($sql);
				}
			} else {
				// It exists now.
				$sql = "INSERT INTO `".$catalog."`(`nfcid`,`addDate`,`weight`,`currentWeight`) VALUES ('".$nfc."', ".$now.",".$weight.", ".$weight.");";
				if(!$sqlite) {
					$result = $conn->query($sql);
				} else {
					$result = $db->query($sql);
				}
			}
			$sql = "SELECT * FROM `".$status."` WHERE 1;";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$check = $result->num_rows > 0;
			} else {
				$rows = 0;
				while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    				$rows += 1;
				}
				$check = $rows > 0;
			}
			if($check) {
				// It already exists, update it.
				$sql = "UPDATE `".$status."` SET `nfcid`='".$nfc."',`weight`=".$weight.",`humidity`=".$humidity.",`temp`=".$temp.",`active`=1 WHERE 1;";
				if(!$sqlite) {
					$result = $conn->query($sql);
				} else {
					$result = $db->query($sql);
				}
			} else {
				// It exists now.
				$sql = "INSERT INTO `".$status."`(`nfcid`,`weight`,`humidity`,`temp`,`active`) VALUES ('".$nfc."', ".$weight.", ".$humidity.", ".$temp.", 1);";
				if(!$sqlite) {
					$result = $conn->query($sql);
				} else {
					$result = $db->query($sql);
				}
			}
			echo $lang_ok;
		}
		break;
	case 7:
		// Return currently used profile
		// TODO: if not active then display something fancy
		$sql = "SELECT * FROM `".$status."` WHERE 1;";
		if(!$sqlite) {
			$result = $conn->query($sql);
			$row = $result -> fetch_array(MYSQLI_ASSOC);
		} else {
			$results = $db->query($sql);
			$row = $results->fetchArray(SQLITE3_ASSOC);
		}
		if(!isset($row['nfcid'])) {
			print_r($lang_empty);
		} else {
			$active = $row['nfcid'];
			$sql = "SELECT * FROM `".$catalog."` WHERE `nfcid`='".$active."';";
			if(!$sqlite) {
				$result = $conn->query($sql);
			} else {
				$result = $db->query($sql);
			}
			if(!$sqlite) {
				$val = $result -> fetch_array(MYSQLI_ASSOC);
			} else {
				$val = $result -> fetchArray(SQLITE3_ASSOC);
			}
			if(!isset($val)) {
				print_r($lang_err_desync);
			} else {
				print_r($val['manufacturer']."\n".($val['weight']*1000)."g\n".$val['id']."\n".$val['name']);
			}
		}
		break;
	case -1:
		// Easter egg
		?>
<head>
    <title><?php echo $apptitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/list.css" />
    <link rel="stylesheet" href="./css/active.css" />
    <link rel="stylesheet" href="./css/main.css" />
    <script src="./js/jquery.js"></script>
    <script>
    function getRndInteger(min, max) {
        return Math.floor(Math.random() * (max - min + 1) ) + min;
    }
    </script>
</head>
<body>
		<?php
		echo "AppTitle: ".$apptitle."<br />";
		echo "Version: ".$version."<br />";
		$rollingStr = $rolling ? 'true' : 'false';
		echo "Rolling: ".$rollingStr."<br />";
		?>
</body>
		<?php
		break;
	case -2:
		// Used to benchmark database speed
		if(!$rolling) {
		if(!$sqlite) {
		$starttime = microtime(true);
		$sql = "SELECT * FROM `".$status."`";
		$result = $conn->query($sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		$sql = "SELECT * FROM `".$catalog."`";
		$result = $conn->query($sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		$endtime = microtime(true);
		echo round(($endtime - $starttime) * 1000, 2)."ms";
		} else {
		exit($lang_err_sqlite);
		}
		} else {
		exit($lang_err_rolling);
		}
		break;
	default:
		exit($lang_err_foreign);
		break;
}