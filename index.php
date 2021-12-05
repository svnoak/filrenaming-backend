<?php
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    // Tillåt alla (origins) och alla headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    exit();
}
header("Access-Control-Allow-Origin: *");

    if( $_SERVER["REQUEST_METHOD"] == "POST" ){

    $upload = $_POST;
    $files = $_FILES;

    //$folderName = uniqid();
    $zip = new ZipArchive();
    $zip_name = uniqid() . ".zip";
    $path_to_zip = "zip/$zip_name";
    $zip->open("$path_to_zip", ZIPARCHIVE::CREATE);
    foreach( $files as $index=>$file ){
        $tmp_names = $file["tmp_name"];
        $name = $upload[$index];
        $error = $file["error"];
        //mkdir("./uploads/$folderName");
        //moveImages($tmp_names, $name, $error, $folderName);
        for( $i = 0; $i < count($tmp_names); $i++ ){
            $err = $error[$i];
            if( $err === 0 ){
                $number = $i+1;
                $tmp_name = $tmp_names[$i];
                $imgName = "$name-$number.png";
                $zip->addFile($tmp_name, $imgName);
                //move_uploaded_file($tmp_name, "uploads/$folderName/$imgName.png");
            } else {
                //sendJSON(["message"=>"One or more images failed uploading, please try again"], 400);
                //exit();
            }
        }
    }
    $zip->close();
        if (is_readable($path_to_zip)) {
            $path = $_SERVER["DOCUMENT_ROOT"];
            sendJSON(["value"=>"http://localhost:7000/" . "$path_to_zip", "message"=>"Success"], 200);
            exit();
        }
        else{
            sendJSON(["message"=>"Something went wrong"], 400);
            exit();
        }
    }
    elseif ($_SERVER["REQUEST_METHOD"] == "GET"){
        sendJSON(["message"=>"DOWNLOAD STARTED"], 200);
    }


function sendJSON($message, $statusCode) {
    //is used whenever something is successful 
    // or goes wrong. exits the code

    header("Content-Type: application/json");
    http_response_code($statusCode);
    $jsonMessage = json_encode($message);

    echo($jsonMessage);
}