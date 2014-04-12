<?php

//call library 


date_default_timezone_set("America/Jamaica");
require_once ( dirname(__FILE__) . '/lib_nusoap/nusoap.php');

try
{

//using soap_server to create server object 
    $server = new soap_server;

//register a function that works on server 
    $server->register('get_dbinfo');

//    echo $dbInfo;
// create the function 
    function get_dbinfo($dbInfo)
    {
        /*
          $dbInfox = array("server" => "localhost",
          "database" => "zf2learn",
          "userID" => "root",
          "password" => "password",
          "dbtype" => "mysql",
          "query" => "select * from album");


          print_r($dbInfox);
          exit();
         */


        if (!$dbInfo)
        {
            return new soap_fault('Client', '', 'Put Your Name!');
        }

        $arrDbInfo = json_decode($dbInfo, true);

        //return print_r($arrDbInfo, true);


        $server = trim($arrDbInfo['server']);
        $database = trim($arrDbInfo['database']);
        $userID = trim($arrDbInfo['userID']);
        $password = trim($arrDbInfo['password']);
        $dbtype = trim($arrDbInfo['dbtype']);
        $query = trim($arrDbInfo['query']);
        //$query2 = '"'.$query.'"';
        if (strcmp($dbtype, "mysql") == 0)
        {
            $query_array = explode(" ", $query);
            $key = strtolower($query_array[0]);

            if (strcmp($key, "select") == 0)
            {

                $return_data = array();
                $conn = mysql_connect($server, $userID, $password);
                check($conn, "connect");
                check(mysql_select_db($database), "selecting db");

                $results = mysql_query($query, $conn);
                check($results, "query of $query");

                if (mysql_num_rows($results) > 0)
                {
                    $return_data = array();
                    $number_of_fields = mysql_num_fields($results);
                    while ($row = mysql_fetch_array($results))
                    {
                        $data = array();
                        for ($i = 0; $i < count($row); $i++)
                        {

                            $rowname = trim(mysql_field_name($results, $i));
                            if (strcmp($rowname, " ") == 0 || $rowname == null)
                            {
                                break;
                            }
                            $data[$rowname] = $row[$i];
                            /* for($j=0;$j<count($number_of_fields);$j++)
                              {
                              $rowname = trim(mysql_field_name($results, $j));
                              $data[$rowname]=$row[$j];
                              //array_push($data,$row[$i]);		//mysql_field_name($resul, $i)
                              } */
                        }

                        //$row_json = json_encode($data);
                        array_push($return_data, $data);
                    }

//                    return json_encode($return_data);
                    $debug = array();
                    $debug["data"] = "No Error Occured";
                    $return_json = array();
                    $return_json["code"] = 200;
                    $return_json["data"] = $return_data;
                    $return_json["debug"] = $debug;

//                    print json_encode($return_json);
                    mysql_close($conn);
                    return json_encode($return_json);
                    exit();
                }
                else
                {
                    $debug = array();
                    $debug["data"] = "No Error Occured";
                    $return_json = array();
                    $return_json["code"] = 200;
                    $return_json["data"] = " ";
                    $return_json["debug"] = $debug;

//                    print json_encode($return_json);
                    mysql_close($conn);
                    return json_encode($return_json);
                }
            }

            if (strcmp($key, "insert") == 0 || strcmp($key, "update") == 0 || strcmp($key, "delete") == 0 || strcmp($key, "create") == 0 || strcmp($key, "alter") == 0 || strcmp($key, "drop") == 0)
            {

                $conn = mysql_connect($server, $userID, $password);
                check($conn, "connect");
                check(mysql_select_db($database), "selecting db");

                $results = mysql_query($query , $conn);
                check($results, "query of $query");

                if ($results)
                {
                    $debug = array();
                    $debug["data"] = "No Error Occured";
                    $return_json = array();
                    $return_json["code"] = 200;
                    $return_json["data"] = "Successful";
                    $return_json["debug"] = $debug;

//                    print json_encode($return_json);
                    mysql_close($conn);
                    return json_encode($return_json);
                }
                else
                {
                    $debug = array();
                    $debug["data"] = "No Error Occured";
                    $return_json = array();
                    $return_json["code"] = 200;
                    $return_json["data"] = "Unsuccesful {$query} " . mysql_error() ;
                    $return_json["debug"] = $debug;

//                    print json_encode($return_json);
                    mysql_close($conn);
                    return json_encode($return_json);
                }
            }
        }
        else
        {
            $debug = array();
            $debug["data"] = "Supports only MySql Database Management Systems. try the other connection available";
            $return_json = array();
            $return_json["code"] = 422;
            $return_json["data"] = "Unsuccesful";
            $return_json["debug"] = $debug;

//            print json_encode($return_json);
            mysql_close();
            return json_encode($return_json);
        }
    }

// create HTTP listener 
    $server->service($HTTP_RAW_POST_DATA);
}
catch (Exception$e)
{
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

function check($result, $message)
{
    if (!$result)
    {
//            print "SQL error during $message: " . mysql_error();
        return "SQL error during $message ";
    }
    else
    //print "No Error Occurred";
        return "No Error Occurred";
}

exit();
?> 
