<?php

require_once (dirname(__FILE__) . '/lib_nusoap/nusoap.php');

date_default_timezone_set("America/Jamaica");

//Give it value at parameter 
$arrParam = array("server" => "localhost",
    "database" => "zf2learn",
    "userID" => "root",
    "password" => "password",
    "dbtype" => "mysql",
    "query" => "select * from album");

$param = array("dbInfo" => json_encode($arrParam));
//Create object that referer a web services 
$client = new nusoap_client('http://localhost/misc/web_services_church/nu_soap_server.php');

//echo "hello - PPP";
//Call a function at server and send parameters too 
$response = $client->call('get_dbinfo', $param);
//Process result 
if ($client->fault)
{
    echo "FAULT: <p>Code: (" . $client->faultcode . "</p>";
    echo "String: " . $client->faultstring;
}
else
{
    echo $response;
}
?> 