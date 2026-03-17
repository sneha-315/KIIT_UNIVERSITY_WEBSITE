<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_students";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed");
}

// Get form data
$student_id = $_POST['student_id'] ?? '';
$full_name  = $_POST['full_name'] ?? '';
$email      = $_POST['email'] ?? '';
$phone      = $_POST['phone_no'] ?? '';  // This is the form field name
$course     = $_POST['course'] ?? '';
$year       = $_POST['year'] ?? '';
$dob        = $_POST['dob'] ?? '';
$gender     = $_POST['gender'] ?? '';
$hostel     = $_POST['hostel'] ?? '';
$address    = $_POST['address'] ?? '';

// Clean phone - keep only numbers
$phone = preg_replace('/[^0-9]/', '', $phone);

// Validate
if (strlen($phone) < 10) {
    echo "Error: Phone must be at least 10 digits";
    exit();
}

// IMPORTANT: Since column is now VARCHAR, phone will be stored as text
// Escape all inputs
$student_id = mysqli_real_escape_string($conn, $student_id);
$full_name  = mysqli_real_escape_string($conn, $full_name);
$email      = mysqli_real_escape_string($conn, $email);
$phone      = mysqli_real_escape_string($conn, $phone);  // This is TEXT now
$course     = mysqli_real_escape_string($conn, $course);
$year       = intval($year);  // Year stays as number
$dob        = mysqli_real_escape_string($conn, $dob);
$gender     = mysqli_real_escape_string($conn, $gender);
$hostel     = mysqli_real_escape_string($conn, $hostel);
$address    = mysqli_real_escape_string($conn, $address);

// Insert query - phone is in quotes, so it's stored as string
$sql = "INSERT INTO students 
        (student_id, full_name, email, phone_no, course, year, dob, gender, hostel, address)
        VALUES 
        ('$student_id', '$full_name', '$email', '$phone', '$course', $year, '$dob', '$gender', '$hostel', '$address')";

if (mysqli_query($conn, $sql)) {
    echo "success";
    
    // Optional: Verify what was stored
    error_log("Phone stored as string: " . $phone);
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>