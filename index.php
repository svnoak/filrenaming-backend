<?php

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    // Tillåt alla (origins) och alla headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    exit();
} 

header("Access-Control-Allow-Origin: *");


if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $files = getJSON("php://input");

    sendJSON(["data"=>$files], 200);
} else {
    sendJSON(["message"=>"INVALID REQUEST"], 400);
}

function sendJSON($message, $statusCode) {
    //is used whenever something is successful 
    // or goes wrong. exits the code

    header("Content-Type: application/json");
    http_response_code($statusCode);
    $jsonMessage = json_encode($message);

    echo($jsonMessage);
    exit();
}

function getJSON($filename){
    return json_decode(file_get_contents($filename), true);
}