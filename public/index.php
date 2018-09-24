<?php
//Temp site config
$site = [
    "title" => "Freshers Hackathon", 
    "mode" => "development"
];
$_SERVER['SERVER_NAME'] = "compsoc.crablab.co";

//Development server
if($site['mode'] === "development"){
    //Turn on error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

//Load Config 
require("../server/servertools/config.php"); 

//Get the request url in an array
$sitePageURLArray = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));

//strip if there is a payload
function payloadStrip($string) {
  return strpos($string, '?') === false;
}

$sitePageURLArray = array_map("strtolower", array_map("htmlspecialchars", array_filter($sitePageURLArray, 'payloadStrip')));

//sanitize inputs
$_POST = array_map("htmlspecialchars", $_POST);
$_GET = array_map("htmlspecialchars", $_GET);


if(!empty($sitePageURLArray)){
    //Static content retrieval
    switch (strtolower($sitePageURLArray[0])) {
        case 'api':
            $apiStr = $sitePageURLArray;
            unset($apiStr[0]);

            $fileStr = "";

            foreach ($apiStr as $key => $value) {
                $fileStr = $fileStr . $value . ".";
            }


            $request = getcwd() . "/api/" . $fileStr . "php";
    
            if(file_exists($request)){
                require($request);
            } else {
                http404();
            }
            exit();
            #end case
            break;

        case 'assets':
            $request = "../server/assets/" . strtolower($sitePageURLArray[1]) . "/" . strtolower($sitePageURLArray[2]);
            if(file_exists($request)){
                if($sitePageURLArray[1] == 'css'){
                    $head = "Content-type: text/css";
                } elseif($sitePageURLArray[1] == 'js'){
                    $head = "Content-type: application/javascript";
                } elseif($sitePageURLArray[1] == 'webfonts'){
                    $head = "";
                } elseif($sitePageURLArray[1] == 'json'){
                    $head = "Content-Type: application/json";
                }

                #Output
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Service-Worker-Allowed: /');
                header($head);
                echo(file_get_contents($request));
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "404 - Not found";
            }
            break;
        default:
            loadCMS($sitePageURLArray, $site);
    }
} else {
    loadCMS($sitePageURLArray, $site);
}

function loadCMS($sitePageURLArray, $site){
    //load header
    require("../server/includes/header.php");
    //load cms pages
    if(!empty($sitePageURLArray)){
        switch ($sitePageURLArray[0]) {
            case 'stage1':
                require("../server/includes/stage1.php");
                break;
            case 'stage2':
                require("../server/includes/stage2.php");
                break;
            case 'stage3':
                require("../server/includes/stage3.php");
                break;
            case 'stage4':
                require("../server/includes/stage4.php");
                break;
            case 'stage5':
                require("../server/includes/stage5.php");
                break;
            case 'stage6':
                require("../server/includes/stage6.php");
                break;
            default:
                http404();
                break;
        }
    } else {
        require("../server/includes/stage0.php");      
    }
}


//HTTP status codes
function http401(){ 
    header("HTTP/1.0 401 Unauthorized");
    echo "401 - Unauthorized";
    exit();
}

function http404(){
    header("HTTP/1.0 404 Not Found");
    echo "404 - Not found";
    exit();
}

function http400(){
    header("HTTP/1.0 400 Bad Request");
    echo "400 - Bad Request";
    exit();
}

function http418(){
    header("HTTP/1.0 418 I'm a teapot");
    echo "418 - I'm a teapot";
    exit();
}

function http503(){
    header("HTTP/1.0 503 Service Unavailable");
    echo "503 - Service Unavailable";
    exit();
}

function http500(){
    header("HTTP/1.0 500 Internal Server Error");
    echo "500 - We fucked up </3";
    exit();
}

?>