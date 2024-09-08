<?php
// Set to your appropriate timezone
date_default_timezone_set('America/Los_Angeles');
session_start();
require "Timeslots.php";

// Load the timeslots
$timeSlotsObj = new Timeslots();
$timeSlots = $timeSlotsObj->getTimeSlots();
$slotDetails = $timeSlotsObj->checkCurrentMeeting();
//if parameter forceresult is set to false, remove the slotdetails
if (isset($_GET['forceresult']) && $_GET['forceresult'] == 'false' && (!empty($_SESSION['user']))) {
    $slotDetails = false;
}
//if parameter forceresult is set to true, force the slotdetails with the first slot there
if (isset($_GET['forceresult']) && $_GET['forceresult'] == 'true' && (!empty($_SESSION['user']))) {
    //$slotDetails = $timeSlots[array_key_first($timeSlots)][array_key_first($timeSlots[array_key_first($timeSlots)])];
    //Get the first one that's not cancelled
    foreach ($timeSlots as $day => $slots) {
        foreach ($slots as $time => $details) {
            if (!($details['is_cancelled'] ?? false)) {
                $slotDetails = $details;
                break;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Hours Link Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Give theme color for this page */
        :root {
            --theme-color: #007bff;
            /*background color light blue*/
            --theme-color-light: #cce5ff;
            /*background color lighter blue*/
            --theme-color-lighter: #e7f0ff;
        }

        body {
            background-color: var(--theme-color-lighter);
        }

        .main-container {
            margin-top: 50px;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }


        /* Spinner */
        .container-sp {
            position: relative;
            width: 200px;
            height: 200px;
            margin: auto;
        }

        .wheel {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 15px solid transparent;
            border-top: 15px solid darkblue;

            animation: spin 20s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .countdown {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2em;
            font-family: Arial, sans-serif;
            color: #000;
        }

        #spinner {
            height: 100%;
        }

        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        html, body {
            height: 100%;
        }

        footer {
            margin-top: auto;
            background-color: #fff;
            padding: 10px;
            text-align: center;
        }

        #bigbigcontainer {
            margin-bottom: 50px;
        }
    </style>
    <script>
        let meetingLink = "";
    </script>
</head>
<body>

<div class="wrapper">

    <div class="container text-center" id="bigbigcontainer">

        <div class="jumbotron text-center">
            <br><br>
            <h1>Johnson's Open Lab Hours 🔬</h1>
        </div>

        <div class="main-container">

            <!---- if there's an slot now and it's not cancelled--->
            <?php if ($slotDetails && !($slotDetails["is_cancelled"] ?? false)): ?>
                <!--Make the alert closable-->
                <!--Make a super obivous button wich animation to make people click-->
                <div id="tryjoin-container">
                    <h3 class="text-center">
                        Welcome! You are in the right place. ✅
                    </h3>
                    <br>
                    <button onclick="tryJoinMeeting();" class="btn btn-primary btn-lg"> 🎦 Join Zoom Open Lab Hours
                    </button>
                    <br>
                </div>
                <div id="container-sp" style="display: none;">

                    <h4>Please wait, learning assistant will be with you soon.</h4>
                    <p><strong>Your Queue Position: <span id="queue_position"></span></strong></p>
                    <br>
                    <div class="container-sp">
                        <div id="spinner">
                            <div class="wheel"></div>
                            <div class="countdown" id="countdown" style="font-size:5em;"></div>
                        </div>
                    </div>
                </div>

                <!--- Display the meeting ID and password is webDev2024 --->
                <div id="meeting-detail" style="display: none;">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong> ✅Welcome! Learning Assistant is available.</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <p class="">Zoom Meeting ID: <?= $slotDetails['meeting_id'] ?></p>
                    <p class="">Password: webDev2024</p>
                    <p class="">Link: <a href="<?= $slotDetails["link"] ?>"><?= $slotDetails["link"] ?></a></p>
                    <span>⭐️ If there's no one in the Zoom room, please wait for a few minutes. I will be with you shortly. ⭐️</span>
                </div>

            <br>

                <script>
                    meetingLink = "<?= $slotDetails['link'] ?>";
                </script>

            <?php elseif ($slotDetails && ($slotDetails["is_cancelled"] ?? false)): ?>
                <!--Make the alert closable-->
                <h3 class="text-center">
                    🕒 Open Lab Hours have changed! 🕒
                </h3>
            <br>
                <div class="alert alert-warning fade show" role="alert">
                    <strong><?= $slotDetails["type"] ?></strong>

                </div>


            <?php else: ?>

                <!--Make the alert closable-->
                <div class="alert alert-warning fade show" role="alert">
                    <strong>🕒 Open Lab Hours are closed now! 🕒</strong>

                </div>

            <?php endif; ?>
            <div>
                <h3>
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#scheduleTable"
                            aria-expanded="false" aria-controls="scheduleTable">
                        📅 Johnson's Open Lab Hours Schedule
                    </button>
                </h3>

                <div id="scheduleTable" class="collapse">
                    <!-- Display the weekly schedule in a nicely formatted bootstrap 5 table -->
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Day of Week</th>
                            <th>Time</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($timeSlots as $day => $slots): ?>
                            <?php foreach ($slots as $time => $details): ?>
                                <?php if ($details['is_cancelled'] ?? false) {
                                    continue;
                                } ?>
                                <tr>
                                    <td><?= $day ?></td>
                                    <td><?= $time ?></td>
                                    <td><?= $details['type'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!--Valid between yyyy/mm/dd-->
                    <div class="text-muted">
                        The schedule above is valid from <?= date('m/d/Y', strtotime('last Monday')) ?>
                        to <?= date('m/d/Y', strtotime('next Sunday')) ?>
                    </div>

                    <br>
                    <p>For the availability of other learning assistants, please refer to the course website. The
                        schedule may change without notice.</p>
                    <br>
                </div>

            </div>

        </div>

    </div>


    <footer>
        Made with ❤️ by Johnson <br>
        <a href="login.php">Edit meeting</a> |
        <a href="start_meeting.php">Start meeting</a>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>

    function getRandomNumber() {
        return Math.floor(Math.random() * 99999999);
    }
    //get queue position
    function getQueuePosition() {
        //make ajax request to get the queue position
        $.ajax({
            url: 'queue_position.php',
            type: 'GET',
            success: function (data) {
                $("#queue_position").text(data);
            }
        });
    }

    function tryJoinMeeting() {
        // Make an AJAX request to meeting_status.txt to check if the meeting is available. If not, make AJAX request to make_call and show the loading spinner and check meeting_status.txt every second
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'meeting_status.txt'+getRandomNumber(), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // If the meeting is available, redirect to the meeting link
                if (xhr.responseText.trim() === 'started') {
                    //show meeting details
                    //document.getElementById('tryjoin-container').style.display = 'none';
                    $("#tryjoin-container").fadeOut("fast");
                    //document.getElementById('meeting-detail').style.display = 'block';
                    $("#meeting-detail").fadeIn("slow");
                    openLink(meetingLink, '_blank');
                } else {
                    // If the meeting is not available, make an AJAX request to make_call.php
                    startCountdown();

                    const xhr2 = new XMLHttpRequest();
                    xhr2.open('GET', 'make_call.php', true);//make call!
                    xhr2.onreadystatechange = function () {
                        if (xhr2.readyState === 4 && xhr2.status === 200) {
                            // Show the loading spinner
                            //document.getElementById('container-sp').style.display = 'block';
                            $("#tryjoin-container").fadeOut("slow")
                            $("#container-sp").fadeIn("slow")
                            // Hide the switch
                            // document.getElementById('tryjoin-container').style.display = 'none';

                            // Check the meeting status every second
                            let x = setInterval(function () {
                                const xhr3 = new XMLHttpRequest();
                                xhr3.open('GET', 'meeting_status.txt'+getRandomNumber(), true);
                                xhr3.onreadystatechange = function () {
                                    if (xhr3.readyState === 4 && xhr3.status === 200) {
                                        // If the meeting is available, redirect to the meeting link
                                        if (xhr3.responseText === 'started') {
                                            clearInterval(x);
                                            //show meeting details
                                            //document.getElementById('meeting-detail').style.display = 'block';
                                            $("#meeting-detail").fadeIn("slow");
                                            //document.getElementById('container-sp').style.display = 'none';
                                            $("#container-sp").fadeOut("slow");
                                            openLink(meetingLink, '_blank');
                                        }
                                    }
                                };
                                xhr3.send();
                            }, 1000);
                        }
                    };
                    xhr2.send();
                    getQueuePosition();
                }
            }
        };

        xhr.send();

    }

    function startCountdown() {
        let countdown = 30; // Start from 30 seconds
        const countdownElement = document.getElementById("countdown");
        let countback = false;
        const interval = setInterval(() => {
            countdownElement.textContent = countdown;
            if (countdown <= 0 || countback) {
                /*clearInterval(interval);
                countdownElement.textContent = "Time's up!";*/
                countback = true;
                countdown++;
            } else {
                countdown--;
            }

        }, 1000);
    }

    //if host is localhost don't open anything, else open the link
    function openLink(link) {
        if (window.location.hostname !== 'localhost') {
            window.open(link, '_blank');
        }
    }

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

