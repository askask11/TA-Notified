<?php
//check logged in
session_start();
if (empty($_SESSION['user'])) {
    //set status code to 401
    http_response_code(401);
    echo "Please login first";
    exit();
}
// Save changes to the time slot data, each time slot is identified by the day of the week and the time of the day.
//Read the JSON file
$jsonFile = 'timeslot.json';
$jsonStr = file_get_contents($jsonFile);
$timeSlots = json_decode($jsonStr, true);

// Get time slot data from the URL
$day = $_GET['day'];
$time = $_GET['time'];
$type = $_GET['type'];
$meeting_id = $_GET['meeting_id'];
$deletion_date = $_GET['deletion_date'];
$link = $_GET['link'];
$is_cancelled = $_GET['is_cancelled']=='true';
// Given day of the week, time slot, and type of the time slot, add a new time slot to the existing time slots,
// sort the time slots by day of the week and time slot (Monday first, then Tuesday, then Wedensday, etc.), then by time of the day, and save the updated time slots to the JSON file.
$timeSlots[$day][$time] = ['type' => $type, 'link' => $link, 'meeting_id' => $meeting_id, 'deletion_date' => $deletion_date, 'is_cancelled' => $is_cancelled];

/**Sort the days of the week**/
// Define the custom order of the days
$dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

// Sort the array by the keys using the custom order
uksort($timeSlots, function($a, $b) use ($dayOrder) {
    $posA = array_search($a, $dayOrder);
    $posB = array_search($b, $dayOrder);
    return $posA - $posB;
});

/**Sort the time slots by time**/
// Sort the time slots by time
foreach ($timeSlots as $day => $slots) {
    uksort($slots, function($a, $b) {
        return strtotime($a) - strtotime($b);
    });
    $timeSlots[$day] = $slots;
}


$jsonData = json_encode($timeSlots, JSON_PRETTY_PRINT);
file_put_contents($jsonFile, $jsonData);
echo "Time slot data has been saved.";
