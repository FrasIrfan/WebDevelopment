<?php
include "config.php"; // Include your database connection

// Get today's date
$today = date('Y-m-d');

// Function to mark all students as absent
function markAllStudentsAbsent($mysqli, $today) {
    // Insert absent records for all students who don't already have a record for today
    $sql = "INSERT IGNORE INTO Attendances (UserID, AttendanceDate, Status)
            SELECT UserID, '$today', 'Absent'
            FROM Users
            WHERE NOT EXISTS (
                SELECT 1 FROM Attendances
                WHERE UserID = Users.UserID
                AND AttendanceDate = '$today'
            )";
    
    if ($mysqli->query($sql)) {
        echo "All students have been marked as absent for today.";
    } else {
        echo "Error: " . $mysqli->error;
    }
}

// Call the function to mark all students as absent
markAllStudentsAbsent($mysqli, $today);
?>
