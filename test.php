<?php
require "Timeslots.php";
require "BARKNotifier.php";

/*Read the JSON file
$timeSlotsObj = new Timeslots();
$timeSlots = $timeSlotsObj->getTimeslots();

//Write the method to check if the current time is within the lab hours down
$currentDay = date('l'); // Current day of the week
$currentTime = date('H:i'); // Current time

foreach ($timeSlots as $day => $slots) {
    foreach ($slots as $time => $details) {
        if ($day === $currentDay) {
            list($startTime, $endTime) = explode('-', $time);
            $startTime = date('H:i', strtotime($startTime . ' -15 minutes'));
            $endTime = date('H:i', strtotime($endTime . ' +15 minutes'));

            echo $startTime . " " . $endTime . " " . $currentTime . "\n";
            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                echo "yes";
            }
        }
    }
}

echo "no";*/
BARKNotifier::notifyTimeSensitive("START THE MEETING NOW");
echo "OK";
