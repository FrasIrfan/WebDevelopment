<?php
include 'db.php';
print_r($_POST);die();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    // Debugging: Displaying the received data
    echo "Name: $name, Age: $age, Gender: $gender, Email: $email<br>";

    $sql = "INSERT INTO members (name, age, gender, email) VALUES ('$name', $age, '$gender', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "No data posted with HTTP POST.";
}
?>
