<!--A bootstrap 5 navbar that contains<a href="start_meeting.php">🎦 Start Meeting</a>
        <a href="index.php" >🏠 Back to Home</a>
        <a href="logout.php" >🚪 Logout</a>, and will highlight what page I am in -->
<?php
// Get the current filename to highlight the active link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Bootstrap 5 Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Open Lab Hours Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'edit.php') echo 'active'; ?>" href="edit.php">📝 Edit Timeslot</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'start_meeting.php') echo 'active'; ?>" href="start_meeting.php">🎦 Start Meeting</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'index.php') echo 'active'; ?>" href="index.php">🏠 Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'logout.php') echo 'active'; ?>" href="logout.php">🚪 Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End of Bootstrap 5 Navbar -->


