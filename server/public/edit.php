<?php
include "settings.php";
$starttime = microtime(true);
?>
<head>
    <title><?php echo $apptitle; ?> - Edit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/edit.css" />
    <link rel="stylesheet" href="./css/main.css" />
    <script src="./js/jquery.js"></script>
    <script src="./js/main.js"></script>
</head>
<body>
    <h1><?php echo $apptitle; ?></h1>
    <a href='./'><?php echo $lang_back; ?></a>
    <h2><?php echo $lang_edit; ?> <a href="action.php?action=4&id=<?php echo $_GET['id']; ?>">(<?php echo $lang_remove; ?>)</a></h2>
    <form action="form.php" id="details"></form>
    <script>
        var details = document.getElementById("details");
        var lang = JSON.parse(`<?php echo returnLanguage($unit_weight_str,$unit_length_str,$unit_temp_str); ?>`);
        function getDetails() {
            $.ajax({
                url: 'action.php',
                dataType: "html",
                type: "get",
                data: {
                    action: 2,
                    id: <?php echo $_GET['id']; ?>
                },
                success: function( response )
                {
                    var obj = JSON.parse(response);
                    var newList = document.createElement("ul");
                    var idElement = document.createElement("li");
                    var idInput = document.createElement("input");
                    idInput.value = "<?php echo $_GET['id']; ?>";
                    idInput.name = "id";
                    idInput.hidden = true;
                    idElement.appendChild(idInput);
                    newList.append(idElement);
                    for(var item in obj[0]) {
                        var newElement = document.createElement("li");
                        var newText = document.createElement("label");
                        var inner = document.createTextNode(lang[item] + ":   ");
                        newText.classList.add("left");
                        newText.appendChild(inner);
                        newElement.appendChild(newText);          
                        var spanInput = document.createElement('span');
                        spanInput.classList.add('right');
                        var newInput = document.createElement("input");
                        newInput.type = "text";
                        newInput.value = obj[0][item];
                        newInput.name = item;
                        newInput.id = item;
                        spanInput.appendChild(newInput);
                        newElement.appendChild(spanInput);
                        newList.appendChild(newElement);
                    }
                    var newElement = document.createElement("li");
                    var saveBtn = document.createElement("input");
                    saveBtn.value = "<?php echo $lang_save; ?>";
                    saveBtn.type = "submit";
                    saveBtn.classList.add("savebtn");
                    saveBtn.onclick = "";
                    newElement.appendChild(saveBtn);
                    newList.append(newElement);
                    details.appendChild(newList);
                }
            });
        }
        getDetails();
    </script>
</body>
<?php
$endtime = microtime(true);
echo "<footer><p>Load-time: ".round(($endtime - $starttime) * 1000, 2)."ms<br />Version: ".$version."</p></footer>";
?>