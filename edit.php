<?php
session_start();
// Check if the user is logged in
if (empty($_SESSION['user'])) {
    header('Location: login.php?next=edit.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Time Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #is_cancelled_box
        {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-content: center;
            justify-content: center;
            align-items: center;
            column-gap: 5%;
        }

    </style>
</head>
<body>
<?php include 'navbar.inc'; ?>
<div class="container text-center">


    <br><br>
    <h2>Manage Time Slots</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Day of Week</th>
            <th>Time</th>
            <th>Meeting ID</th>
            <!--<th>Auto Delete</th>-->
            <!--<th>Note</th>-->
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Load and decode the JSON file
        require_once 'Timeslots.php';
        $timeSlotsObj = new Timeslots();
        $timeSlots = $timeSlotsObj->getTimeSlots();
        $cancelledSlots=[];
        ?>

        <?php foreach ($timeSlots as $day => $slots): ?>
            <?php foreach ($slots as $time => $details):
                if($details['is_cancelled']??false) {
                $cancelledSlots[$day][$time]=$details;continue;
                }; ?>


                <tr>
                    <td>

                        <?php echo $day; ?>


                    </td>
                    <td>

                        <?php echo $time; ?>
                    </td>
                    <td>
                        <?php echo $details['meeting_id']; ?>

                    </td>
                    <!--<td><?php echo $details["deletion_date"]??''; ?></td>-->

                    <td>
                        <button class="btn btn-primary btn-sm" onclick="edit('<?php echo $day; ?>','<?php echo $time; ?>','<?php echo $details['link']; ?>','<?php echo $details['deletion_date']??''; ?>','<?php echo $details['meeting_id']; ?>','<?php echo $details['type']; ?>',<?= $details['is_cancelled']??false?>);">Edit</button>
                        <button onclick="deleteTimeSlot('<?php echo $day; ?>', '<?php echo $time; ?>')"
                                class="btn btn-danger btn-sm">Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
    <button class="btn btn-success" onclick="addSlot();">Add New Time Slot</button>

    <br><br><br><hr/>
        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#cancelledSlotsTable" aria-expanded="false" aria-controls="cancelledSlotsTable">
           Show Cancelled Time Slots
        </button>

<br>
    <div id="cancelledSlotsTable" class="collapse">
        <br>
        <h3>
            Cancelled Time Slots
        </h3>
        <table class="table">
            <thead>
            <tr>
                <th>Day of Week</th>
                <th>Time</th>
                <th>Meeting ID</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cancelledSlots as $day => $slots): ?>
                <?php foreach ($slots as $time => $details): ?>
                    <tr>
                        <td>
                            <del><?php echo $day; ?></del>
                        </td>
                        <td>
                            <del><?php echo $time; ?></del>
                        </td>
                        <td>
                            <del><?php echo $details['meeting_id']; ?></del>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="edit('<?php echo $day; ?>','<?php echo $time; ?>','<?php echo $details['link']; ?>','<?php echo $details['deletion_date']??''; ?>','<?php echo $details['meeting_id']; ?>','<?php echo $details['type']; ?>',<?= $details['is_cancelled']??false?>);">Edit</button>
                            <button onclick="deleteTimeSlot('<?php echo $day; ?>', '<?php echo $time; ?>')"
                                    class="btn btn-danger btn-sm">Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.13.2/dist/sweetalert2.all.min.js" integrity="sha256-M8Pl9/5V5HxCVCtvJX5h4KMX5GqtB6RXstTvR2bMwK8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.13.2/dist/sweetalert2.min.css" integrity="sha256-KIZHD6c6Nkk0tgsncHeNNwvNU1TX8YzPrYn01ltQwFg=" crossorigin="anonymous">
<script>
    function edit(day="", time="", link="", deletion_date="", meeting_id="", type="", is_cancelled=false) {
        // Edit a time slot with Swal popup for input, use swal for better UI
        Swal.fire({
            title: (day===null?"Add":"Save")+` Time Slot`,
            html: `<!--An option combobox of Monday-Sunday-->
    <select id="day" class="form-select">
        <option value="Monday" ${day === 'Monday' ? 'selected' : ''}>Monday</option>
        <option value="Tuesday" ${day === 'Tuesday' ? 'selected' : ''}>Tuesday</option>
        <option value="Wednesday" ${day === 'Wednesday' ? 'selected' : ''}>Wednesday</option>
        <option value="Thursday" ${day === 'Thursday' ? 'selected' : ''}>Thursday</option>
        <option value="Friday" ${day === 'Friday' ? 'selected' : ''}>Friday</option>
        <option value="Saturday" ${day === 'Saturday' ? 'selected' : ''}>Saturday</option>
        <option value="Sunday" ${day === 'Sunday' ? 'selected' : ''}>Sunday</option>
    </select>
                   <input id="time" class="swal2-input" value="${time}" placeholder="hh:mm-hh:mm in 24hrs" >
                   <input id="meeting_id" class="swal2-input" value="${meeting_id}" placeholder="Zoom Meeting ID">
                   <input id="link" class="swal2-input" value="${link}" placeholder="Zoom Link">
                   <input id="type" class="swal2-input" value="${type}" placeholder="Note">
<div id="is_cancelled_box" class="swal2-input"><label for="is_cancelled">Cancelled?</label>
                    <input id="is_cancelled" type='checkbox' class="" ${is_cancelled?'checked':''} placeholder="Is Cancelled" ></div>
                   <input id="deletion_date" type="date" class="swal2-input" value="${deletion_date}" placeholder="Deletion Date(Optional)">
`,
            showCancelButton: true,
            confirmButtonText: 'Save',
            preConfirm: () => {
                const day = Swal.getPopup().querySelector('#day').value;
                const time = Swal.getPopup().querySelector('#time').value;
                const type = Swal.getPopup().querySelector('#type').value;
                const meeting_id = Swal.getPopup().querySelector('#meeting_id').value;
                const link = Swal.getPopup().querySelector('#link').value;
                const deletion_date = Swal.getPopup().querySelector('#deletion_date').value;
                const is_cancelled = Swal.getPopup().querySelector('#is_cancelled').checked;
                // Call the saveSlot function
                saveSlot(day, time, type,deletion_date, meeting_id, link,is_cancelled);
            }
        });
    }



    function addSlot()
    {
        edit();
    }
    function saveSlot(day, time, type, deletion_date,meeting_id, link,is_cancelled) {
        // Do a AJAX request to `save_slot.php?day=${day}&time=${time}&type=${type}`;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `save_slot.php?day=${day}&time=${time}&type=${type}&deletion_date=${deletion_date}&is_cancelled=${is_cancelled}&meeting_id=${meeting_id}&link=${link}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Show success message, show count down bar for 3 seconds, then reload the page
                Swal.fire({
                    icon: 'success',
                    title: 'Time slot saved successfully',
                    showConfirmButton: false,
                    timer: 800,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    //show progress bar for 3 seconds

                }).then(() => {
                    location.reload();
                });
            }
        };
        xhr.send();
    }

    function deleteTimeSlot(day, time) {
        // Confirm the deletion, use swal for better UI
        Swal.fire({
            title: 'Are you sure?',
            text: "Time slot for deletion: " + day + " at " + time + ". This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call the deleteSlot function
                deleteSlot(day, time);
            }
        });

        function deleteSlot(day, time) {
            //Do a AJAX request to `delete_slot.php?day=${day}&time=${time}`;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `delete_slot.php?day=${day}&time=${time}`, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Show success message, show count down bar for 3 seconds, then reload the page
                    Swal.fire({
                        icon: 'success',
                        title: 'Time slot deleted successfully',
                        showConfirmButton: false,
                        timer: 900,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    }).then(() => {
                        location.reload();
                    });
                }
            };
            xhr.send();
        }
    }


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
