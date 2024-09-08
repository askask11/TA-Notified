<?php

//check logged in
session_start();
if (empty($_SESSION['user'])) {
    //set status code to 401
    http_response_code(401);
    echo "Please login first";
    exit();
}

//Store the current meeting started status in a file, so that it can be accessed by other scripts
$meetingStatusFile = 'meeting_status.txt';

//If the action is start, write "started" to the file, else write "stopped"
if ($_GET['action'] === 'start') {
    file_put_contents($meetingStatusFile, 'started');
} else {
    file_put_contents($meetingStatusFile, 'stopped');
}

echo "OK";