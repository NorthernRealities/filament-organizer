<?php
include "settings.php";
$starttime = microtime(true);
?>
<head>
  <title>
    <?php echo $apptitle; ?>
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/list.css" />
  <link rel="stylesheet" href="./css/active.css" />
  <link rel="stylesheet" href="./css/main.css" />
  <script src="./js/jquery.js">
  </script>
  <script src="./js/main.js">
  </script>
</head>
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
?>
<body>
  <h1>
    <?php echo $apptitle; ?>
  </h1>
  <?php
$sql = "SELECT * FROM `".$status."` WHERE 1";
if(!$sqlite) {
    $result = $conn->query($sql);
} else {
    $result = $db->query($sql);
}
if(!$sqlite) {
    $row = $result -> fetch_array(MYSQLI_ASSOC);
} else {
    $row = $result -> fetchArray(SQLITE3_ASSOC);
}
if(!isset($row['active'])) {
// No active profile yet.
} else {
if($row['active'] == 1) {
$sql = "SELECT * FROM `".$catalog."` WHERE `nfcid`='".$row['nfcid']."'";
if(!$sqlite) {
    $result = $conn->query($sql);
} else {
    $result = $db->query($sql);
}
if(!$sqlite) {
    $catResult = $result -> fetch_array(MYSQLI_ASSOC);
} else {
    $catResult = $result -> fetchArray(SQLITE3_ASSOC);
}
if(!isset($catResult['id']))
{
print_r($lang_err_desync);
} else {
?>
  <div class="active">
    <h2>
      <?php echo $lang_profileActive ?>
    </h2>
    <div style="display: table-row">
      <div class="active-image">
        <img src='<?php
if($catResult['image'] == "") {
    echo "./resources/placeholder.jpg";
} else {
    echo $catResult['image'];
}
              ?>' style='width:100%' />
      </div>
      <div class="active-content">
        <ul>
          <li>
            <h2>[<?php echo $catResult['id']; ?>] 
              <?php echo $catResult['name']; ?> (<?php echo $catResult['weight'] . $unit_weight . "/" . $catResult['currentWeight'] . $unit_weight . " - " . number_format((100-abs((($catResult['currentWeight']-$catResult['weight'])/$catResult['weight'])*100)), 2, '.', '').'%'; ?>)
            </h2>
          </li>
          <li>
            <a href="action.php?action=5&id=<?php echo $catResult['id']; ?>">(<?php echo $lang_resetweight; ?>)
            </a>
          </li>
          <li>
            <p id='active-details'>
            </p>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <?php
}
} else {
// Profile not active.
}
}
?>
  <div id="newitems">
    <?php
$sql = "SELECT * FROM `".$catalog."` WHERE `setup`=0";
if(!$sqlite) {
    $result = $conn->query($sql);
} else {
    $result = $db->query($sql);
}
$title = false;
while((!$sqlite) ? $row = $result->fetch_array(MYSQLI_ASSOC) : $row = $result->fetchArray(SQLITE3_ASSOC)){
//while($row = $result->fetch_array(MYSQLI_ASSOC)){
if(!$title) {
?>
    <h2>
      <?php echo $lang_profileUnknown; ?>
    </h2>
    <div id="wrapper">
      <div id="main">
        <div class="inner">
          <section class="tiles">
            <?php
$title = true;
}
if($row['setup'] == 0) {
    
?>
            <article class="style1">
              <span class="image">
                <img src="./resources/placeholder.jpg" alt=""><?php
echo '<h2>['.$row['id'].'] '.$lang_newItem.'</h2>';
echo '<b>'.$row['weight'] . $unit_weight. '</b><br />';
?>
                <?php echo $lang_unconfigured; ?>
                </img>
              </span>
            <a href="edit.php?id=<?php echo $row['id'] ?>">
            </a>
            </article>
          <?php
} 
}
if($title) {
?>
          </section>
      </div>
    </div>
  </div>
  </div>
<?php
}
?>
<div id="items">
  <?php
$sql = "SELECT * FROM `".$catalog."` WHERE `setup`=1";
if(!$sqlite) {
    $result = $conn->query($sql);
} else {
    $result = $db->query($sql);
}
$title = false;
while((!$sqlite) ? $row = $result->fetch_array(MYSQLI_ASSOC) : $row = $result->fetchArray(SQLITE3_ASSOC)){
if(!$title) {
?>
  <h2>
    <?php echo $lang_profileKnown; ?>
  </h2>
  <div id="wrapper">
    <div id="main">
      <div class="inner">
        <section class="tiles">
          <?php
$title = true;
}
if($row['setup'] == 1) {
?>
          <article class="style1">
            <span class="image">
              <img src="<?php
if($row['image'] == "") {
    echo "./resources/placeholder.jpg";
} else {
    echo $row['image'];
}
              ?>" alt="">
              <?php
echo '<h2>['.$row['id'].'] '.$row['name'].'</h2>';
echo '<b>'.$row['weight'].$unit_weight.' / '.$row['currentWeight'].$unit_weight.'</b>';
?>
              <p id='details-<?php echo $row['id']; ?>'>
              </p>
              <script>
                var detailsObj<?php echo $row['id'];
                ?> = document.getElementById("details-<?php echo $row['id']; ?>");
                $.ajax({
                  url: 'action.php',
                  dataType: "html",
                  type: "get",
                  data: {
                    action: 21,
                    id: <?php echo $row['id'];
                    ?>
                  }
                  ,
                  success: function( response )
                  {
                    var obj = JSON.parse(response);
                    var newList = document.createElement("ul");
                    for(var item in obj[0]) {
                      var newElement = document.createElement("li");
                      var newText = document.createElement("label");
                      var inner = document.createTextNode(lang[item] + ":   " + obj[0][item]);
                      newText.appendChild(inner);
                      newElement.appendChild(newText);
                      newList.appendChild(newElement);
                    }
                    var newElement = document.createElement("li");
                    detailsObj<?php echo $row['id'];
                    ?>.appendChild(newList);
                  }
                }
                      );
              </script>
              <?php
?>
              </img>
            </span>
          <a href="edit.php?id=<?php echo $row['id'] ?>">
          </a>
          </article>
        <?php
} 
}
if($title) {
?>
        </section>
    </div>
  </div>
</div>
</div>
<?php
}
?>
<script>
  var details = document.getElementById("active-details");
<?php
if(!$rolling) {
?>
  function testItem() {
    $.ajax({
      url: 'action.php',
      dataType: "html",
      type: "get",
      data: {
        action: 0,
        nfc: getRndInteger(10,99) + ':' + getRndInteger(10,99) + ':' + getRndInteger(10,99) + ':' + getRndInteger(10,99)
      }
      ,
      success: function( response )
      {
        document.location.reload(true);
      }
    }
          );
  }
<?php
}
?>
  var lang = JSON.parse(`<?php echo returnLanguage($unit_weight_str,$unit_length_str,$unit_temp_str); ?>`);
<?php
if(isset($catResult['id'])) {
?>
  function getDetails() {
    $.ajax({
      url: 'action.php',
      dataType: "html",
      type: "get",
      data: {
        action: 21,
        id: <?php echo $catResult['id'];
        ?>
      }
      ,
      success: function( response )
      {
        var obj = JSON.parse(response);
        var newList = document.createElement("ul");
        for(var item in obj[0]) {
          var newElement = document.createElement("li");
          var newText = document.createElement("label");
          var inner = document.createTextNode(lang[item] + ":   " + obj[0][item]);
          newText.appendChild(inner);
          newElement.appendChild(newText);
          newList.appendChild(newElement);
        }
        var newElement = document.createElement("li");
        details.appendChild(newList);
      }
    }
          );
  }
  getDetails();
<?php
}
?>
</script>
<?php
$endtime = microtime(true);
echo "<footer><p>Load-time: ".round(($endtime - $starttime) * 1000, 2)."ms<br />Version: ".$version."</p></footer>";
?>
</body>