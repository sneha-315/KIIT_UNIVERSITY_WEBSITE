<?php
header('Content-Type: text/plain');

// Database connection with port 3306
$con = mysqli_connect("localhost:3306", "root", "", "college_students");

// Check connection
if (!$con) {
    echo "Database connection failed";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data - NO need for 'id' column anymore
    $student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($con, trim($_POST['student_id'])) : '';
    $full_name = isset($_POST['full_name']) ? mysqli_real_escape_string($con, trim($_POST['full_name'])) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($con, trim($_POST['email'])) : '';
    $phone_no = isset($_POST['phone_no']) ? mysqli_real_escape_string($con, trim($_POST['phone_no'])) : '';
    $course = isset($_POST['course']) ? mysqli_real_escape_string($con, trim($_POST['course'])) : '';
    $year = isset($_POST['year']) ? mysqli_real_escape_string($con, trim($_POST['year'])) : '';
    $dob = isset($_POST['dob']) && !empty($_POST['dob']) ? "'" . mysqli_real_escape_string($con, trim($_POST['dob'])) . "'" : "NULL";
    $gender = isset($_POST['gender']) && !empty($_POST['gender']) ? "'" . mysqli_real_escape_string($con, trim($_POST['gender'])) . "'" : "NULL";
    $hostel = isset($_POST['hostel']) && !empty($_POST['hostel']) ? "'" . mysqli_real_escape_string($con, trim($_POST['hostel'])) . "'" : "NULL";
    $address = isset($_POST['address']) && !empty($_POST['address']) ? "'" . mysqli_real_escape_string($con, trim($_POST['address'])) . "'" : "NULL";
    
    // Validate required fields
    if (empty($student_id) || empty($full_name) || empty($email) || empty($phone_no) || empty($course) || empty($year)) {
        echo "error: All required fields must be filled";
        mysqli_close($con);
        exit;
    }
    
    // NOW this works perfectly because student_id is PRIMARY KEY and UNIQUE
    $sql = "UPDATE students SET 
            full_name = '$full_name',
            email = '$email',
            phone_no = '$phone_no',
            course = '$course',
            year = '$year',
            dob = $dob,
            gender = $gender,
            hostel = $hostel,
            address = $address
            WHERE student_id = '$student_id'";  // This now targets EXACTLY ONE record
    
    if (mysqli_query($con, $sql)) {
        if (mysqli_affected_rows($con) > 0) {
            echo "success";
        } else {
            echo "error: No changes made or student not found";
        }
    } else {
        echo "error: " . mysqli_error($con);
    }
    
} else {
    echo "error: Invalid request method";
}

mysqli_close($con);
?>