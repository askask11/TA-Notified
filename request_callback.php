<?php

require_once "BARKNotifier.php";
$name= $_POST["name"];
$contact= $_POST["contact"];

BARKNotifier::notify("New callback request: $name, $contact","active","calypso");

echo "OK";
