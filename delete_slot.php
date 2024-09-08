<?php
//delete time slot in JSOn file by day and time
//check if user logged in
session_start();
if (empty($_SESSION['user'])) {
    //set status code to 401
    http_response_code(401);
    echo "Please login first";
    exit();
}

if (isset($_GET['day']) && isset($_GET['time'])) {
    $day = $_GET['day'];
    $time = $_GET['time'];

    // Load and decode the JSON file
    $jsonFile = 'timeslot.json';
    $jsonStr = file_get_contents($jsonFile);
    $timeSlots = json_decode($jsonStr, true);

    // Remove the time slot
    unset($timeSlots[$day][$time]);

    // Convert the array to JSON format
    $jsonData = json_encode($timeSlots, JSON_PRETTY_PRINT);

    // Write JSON data to the file
    file_put_contents($jsonFile, $jsonData);

    echo "Time slot for $day at $time has been deleted.";
}
