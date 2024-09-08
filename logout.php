<?php

session_start();
//unset the user
unset($_SESSION['user']);
//redirect to login page
header('Location: login.php');