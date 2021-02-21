<?php
$starttime = microtime(true);
function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}
function endsWith( $haystack, $needle ) {
   $length = strlen( $needle );
   if( !$length ) {
       return true;
   }
   return substr( $haystack, -$length ) === $needle;
}

$allowed = Array('php','json','css','js');
$dir = new RecursiveDirectoryIterator("./");
$lines = 0;
foreach(new RecursiveIteratorIterator($dir) as $file)
{
    if(in_array(strtolower(substr($file, strrpos($file, '.') + 1)),$allowed)) {
        echo 'Counting '.$file;
        $i = 0;
        $handle = fopen($file, "r");
        while(!feof($handle)){
            $line = fgets($handle);
            if(!startsWith($line,'//') && !endsWith($file,'jquery.js') && !endsWith($file,'count.php')) {
                $i++;
            }
        }
        fclose($handle);
        $lines += $i;
        echo " - " . $i . "\n";
    }
}
$endtime = microtime(true);
echo "================\nTOTAL: ".$lines." lines of code\nCOUNT-TIME: ".round(($endtime - $starttime) * 1000, 2)."ms\n================\n";