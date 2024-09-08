<?php
date_default_timezone_set('America/Los_Angeles');
class Timeslots
{
    public $timeSlots;
    final public static function readTimeslots()
    {
        $jsonFile = 'timeslot.json';
        $jsonStr = file_get_contents($jsonFile);
        return json_decode($jsonStr, true);
    }
    public function getTimeslots()
    {
        return $this->timeSlots;
    }

    // Returns the details of the current time slot
    final public static function checkCurrentMeetingGivenSlots($timeSlots, $beforeTime=1, $afterTime=1)
    {
        $currentDay = date('l'); // Current day of the week
        $currentTime = date('H:i'); // Current time

        foreach ($timeSlots as $day => $slots) {
            foreach ($slots as $time => $details) {
                if ($day === $currentDay) {
                    list($startTime, $endTime) = explode('-', $time);
                    $startTime = date('H:i', strtotime($startTime . ' -'.$beforeTime.' minutes'));
                    $endTime = date('H:i', strtotime($endTime . ' +'.$afterTime.' minutes'));

                    if ($currentTime >= $startTime && $currentTime <= $endTime) {
                        return $details;
                    }
                }
            }
        }
        return false;
    }

    public function checkCurrentMeeting($beforeTime=1, $afterTime=1)
    {
        return self::checkCurrentMeetingGivenSlots($this->timeSlots, $beforeTime, $afterTime);
    }

    public function __construct()
    {
        $this->timeSlots = self::readTimeslots();
    }

    public function __destruct()
    {
        $this->timeSlots = null;
    }

    public function __toString()
    {
        return json_encode($this->timeSlots);
    }




}