<?php

// Retrieving username/password for checking
$receive = file_get_contents('php://input');
$decoded = json_decode(file_get_contents('php://input'), true);

// Checking valid username/password with NJIT
//set URL for checking with njit
$url = 'https://cp4.njit.edu/cp/home/login';
//setting values to username and password
$ucid = $decoded[name];
$pass = $decoded[pass];
$test = "pass=$pass&user=$ucid&uuid=0xACA021";

//open connection
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $test);

//save result from NJIT login attempt & close connection
$output = curl_exec($ch);
curl_close($ch);
//echo $output;

if ($output) {
   echo nl2br("\nNJIT Doesn't Know You\n");
} else {
    echo nl2br("\nNJIT Knows You\n");
}

// Checking valid username/password with back-end database
//set URL to database side & open connection
$url = "https://web.njit.edu/~dnp39/CS490/connect_json.php";
$ch = curl_init();

//set options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $decoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

//save result from Database & close connection
$databaseresult = curl_exec($ch);
curl_close($ch);
$result = json_decode($databaseresult, true);

if ($result['db']) {
   echo "Database Knows You";
} else {
    echo "Database Doesn't Know You";
}
?>
