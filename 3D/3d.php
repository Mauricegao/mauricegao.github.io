<?php
include "config.php";

$dbname = "edmonton";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die ("Unable to connect to MySQL: " . mysqli_error());

// $station = $_GET['station'];

$query = "select month, day, hour, count(*) from cyeg" . 
        " where wx = 'FG' or wx = 'FZFG' or wx = 'BR'" .
        " group by month, day, hour;";

$ttc = "select month, day, hour, count(*) from cyeg" .
            " group by month, day, hour;";

$cursor = mysqli_query($conn, $query);
$result = mysqli_fetch_all($cursor);

$cursor = mysqli_query($conn, $ttc);
$total = mysqli_fetch_all($cursor);

$data = array_fill(0,12,array());

// 3d array: data[month][day][hour] = count
for ($i = 0; $i < 12; $i++){
    $data[$i] = array_fill(0,31,array());
    for ($j = 0; $j < 31; $j++){
        $data[$i][$j] = array_fill(0,24,array());
        for ($k = 0; $k < 24; $k++){
            $data[$i][$j][$k] = 0.0;
        }
    }
}

foreach($result as $row){
    $data[(int)$row[0]-1][(int)$row[1]-1][(int)$row[2]] = (int)$row[3];
}

foreach($total as $row){
    $data[(int)$row[0]-1][(int)$row[1]-1][(int)$row[2]] = (float)number_format(round(100 * $data[(int)$row[0]-1][(int)$row[1]-1][(int)$row[2]] / (int)$row[3], 2), 2);
}

for ($i = 0; $i < 24; $i++){
    $data[1][28][$i] /= 4;
}


// var_dump($data);

$retVal = "";
$gb = 0;
$line = "";

for ($i = 0; $i < 12; $i++){
    for ($j = 0; $j < 31; $j++){
        for ($k = 0; $k < 24; $k++){
            $line .= $data[$i][$j][$k] . ",";
        }
        $line = rtrim($line, ",");
        $line = "$gb," . $line;
        $retVal .= $line;
        $retVal .= "\n";
        $gb++;
        $line = "";
    }
    $retVal = rtrim($retVal, "\n");
}
$retVal = rtrim($retVal, ",");

$header = "";

for ($i = 0; $i < 24; $i++){
    $header .= "$i,";
}
$header = rtrim($header, ",");

$filename = "C:\Users\GaoY\Desktop\WindroseData\ testing.csv";
$file = fopen($filename, "w") or die("Unable to create file!");

fwrite($file, ",$header\n");
fwrite($file, $retVal);


// echo $header;
// echo $retVal;
mysqli_close($conn);
fclose($file);
var_dump($retVal);
?>



<!-- CREATE TEMPORARY TABLE t1
(
SELECT month,day,hour,count(*)
FROM `cyeg`
where wx = 'FG' or wx = 'FZFG' or wx = 'BR'
group by month,day,hour
    );

CREATE TEMPORARY TABLE t2
(
SELECT month,day,hour,count(*)
FROM `cyeg`
group by month,day,hour
    );
    


CREATE TEMPORARY TABLE t1
(
SELECT month,day,hour,count(*) as occurrence
FROM `cyeg`
where wx = 'FG' or wx = 'FZFG' or wx = 'BR'
group by month,day,hour
    );
CREATE TEMPORARY TABLE t2
(
SELECT month,day,hour,count(*) as total
FROM `cyeg`
group by month,day,hour
    );
    
 select t1.occurrence / t2.total from t1,t2; -->