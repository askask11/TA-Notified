<?php
session_start();
// Check if the user is logged in
if (empty($_SESSION['user'])) {
    header('Location: login.php?next=start_meeting.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Meeting</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha256-PI8n5gCcz9cQqQXm3PEtDuPG8qx9oFsFctPg0S5zb8g=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha256-3gQJhtmj7YnV1fmtbVcnAV6eI4ws0Tr48bVZCThtCGQ=" crossorigin="anonymous"></script>
    <style>
        /* Give theme color for this page */
        :root
        {
            --theme-color: #007bff;
            /*background color light blue*/
            --theme-color-light: #cce5ff;
            /*background color lighter blue*/
            --theme-color-lighter: #e7f0ff;
        }
        body {
            background-color: var(--theme-color-lighter);
        }
        #main-container {
            margin-top: 50px;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .text-left
        {
            text-align: left !important;
        }

        .text-right
        {
            text-align: right !important;
        }
        /* The switch - the box around the slider */
        .sw-container {
            width: 51px;
            height: 31px;
            position: relative;
        }

        /* Hide default HTML checkbox */
        .checkbox {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .switch {
            width: 100%;
            height: 100%;
            display: block;
            background-color: #e9e9eb;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease-out;
        }

        /* The slider */
        .slider {
            width: 27px;
            height: 27px;
            position: absolute;
            left: calc(50% - 27px/2 - 10px);
            top: calc(50% - 27px/2);
            border-radius: 50%;
            background: #FFFFFF;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.15), 0px 3px 1px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease-out;
            cursor: pointer;
        }

        .checkbox:checked + .switch {
            background-color: #34C759;
        }

        .checkbox:checked + .switch .slider {
            left: calc(50% - 27px/2 + 10px);
            top: calc(50% - 27px/2);
        }
        #sw-container
        {
            margin: auto auto 18px;
        }
        .status
        {
            font-size: 1rem;
        }
        #loading{
            width: 1rem;
            height: 1rem;
        }


    </style>
</head>
<body>
<?php include 'navbar.inc'; ?>
<div class="container text-center" >
    <div class=" text-center" id="main-container">
        <br><br>
        <h1>Start Meeting</h1>
        <p class="lead">Click the button below to start the meeting.</p>
        <br>
        <div class="sw-container" id="sw-container">
            <input <?= trim(file_get_contents("meeting_status.txt"))=="started"?"checked":"" ?> type="checkbox" class="checkbox" id="checkbox">
            <label class="switch" for="checkbox">
                <span class="slider"></span>
            </label>
        </div>
        <div class="spinner-border text-success"  role="status" id="loading" style="display: none; ">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="status" id="loaded" style="display: none;">
            ✅
        </span>
        <span class="status" id="failed" style="display: none;">
            ❌
        </span>
        <br>
        <!--Test call-->
        <a href="javascript:testCall();"> 📞Test Call </a>
        <br>
        <hr>
        <!--Print the most recent meeting, +-15 mins from now-->
        <h4>
            Upcoming Meeting:
        </h4>
        <?php
        require_once "Timeslots.php";
        // Load and decode the JSON file
        $timeSlotsObject = new Timeslots();
        $nextMeeting = $timeSlotsObject->checkCurrentMeeting();
        $currentDay = date('l');
        $currentTime = date('H:i');
        ?>
        <?php if ($nextMeeting&&!($nextMeeting["is_cancelled"]??false)): ?>
            <div class="text-left">
                <p><strong>Day:</strong> <?= $currentDay ?></p>
                <p><strong>Time:</strong> <?= $currentTime ?></p>
                <p><strong>Meeting ID:</strong> <?= $nextMeeting['meeting_id'] ?></p>
                <p><strong>Link:</strong> <a href="<?= $nextMeeting['link'] ?>" target="_blank"><?= $nextMeeting['link'] ?></a></p>
            </div>
        <?php else: ?>
            <p>No upcoming meeting.</p>
        <p><?= $currentDay. " " . $currentTime ?></p>
        <?php endif; ?>



    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.13.3/dist/sweetalert2.min.css" integrity="sha256-KIZHD6c6Nkk0tgsncHeNNwvNU1TX8YzPrYn01ltQwFg=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.13.3/dist/sweetalert2.all.min.js" integrity="sha256-18N7pC9Qv1ETr7js7sCPAk7fimY5izHX2bFZf5aK8o0=" crossorigin="anonymous"></script>
    <script>
        function testCall()
        {
            // Show a loading spinner
            Swal.fire({
                title: 'Test Call',
                text: 'Making a test call...',
                showConfirmButton: false,
                //show spinner
                didOpen: () => {
                    Swal.showLoading()
                }

            });

            // Send an AJAX request to test_call.php
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'make_call.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Test Call',
                        text: 'The test call has been made successfully. '+xhr.responseText
                    });
                }
            };
            xhr.send();
        }

        // When the switch is clicked, redirect to the start_meeting.php page
        document.getElementById('checkbox').addEventListener('change', function() {
            // If checkbox is checked, send AJAX request to meeting_action.php with action=start
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'meeting_action.php?action='+(this.checked?"start":"stop"), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Hide the switch and show the loading spinner
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('loaded').style.display = 'inline-block';
                    //hide failed
                    document.getElementById('failed').style.display = 'none';
                }
                if(xhr.readyState === 4 && xhr.status !== 200) {
                    // Hide the switch and show the failed icon
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('failed').style.display = 'inline-block';
                    //hide loaded
                    document.getElementById('loaded').style.display = 'none';
                    alert("Failed! " + xhr.responseText);
                }
            };
            xhr.send();
            // Hide the switch and show the loading spinner
            document.getElementById('loading').style.display = 'inline-block';
        });
    </script>


</div>
</body>
</html>

