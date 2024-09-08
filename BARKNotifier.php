<?php
require_once "config/secret.inc";
class BARKNotifier
{
    public static function notify($message,$type="active",$sound="calypso")//type=active, passive, timeSensitive
    {
        $message = htmlspecialchars($message);
        $type = htmlspecialchars($type);
        $url = BART_BASE_URL . $message . "?level=" . $type."&sound=" . $sound;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
    }

    //timeSensitive notify
    public static function notifyTimeSensitive($message)
    {
        self::notify($message, "timeSensitive","update");
    }

}