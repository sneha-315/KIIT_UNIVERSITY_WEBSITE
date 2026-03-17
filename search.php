<?php
header('Content-Type: application/json');

// Database connection with port 3306
$con = mysqli_connect("localhost:3306", "root", "", "college_students");

if (!$con) {
    echo json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

$student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($con, trim($_POST['student_id'])) : '';

if (empty($student_id)) {
    echo json_encode(["error" => "Please enter a Student ID"]);
    mysqli_close($con);
    exit;
}

// NO need to select 'id' anymore - student_id is the primary key
$sql = "SELECT 
            student_id,
            full_name,
            email,
            phone_no,
            course,
            year,
            dob,
            gender,
            hostel,
            address
        FROM students 
        WHERE student_id = '$student_id' 
        LIMIT 1";

$result = mysqli_query($con, $sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . mysqli_error($con)]);
    mysqli_close($con);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Return ONLY the fields that exist in your table
    echo json_encode([
        "student_id" => $row['student_id'],
        "full_name" => $row['full_name'],
        "email" => $row['email'],
        "phone_no" => $row['phone_no'],
        "course" => $row['course'],
        "year" => $row['year'],
        "dob" => $row['dob'],
        "gender" => $row['gender'],
        "hostel" => $row['hostel'],
        "address" => $row['address']
    ]);
} else {
    echo json_encode(["error" => "Student not found with ID: " . $student_id]);
}

mysqli_close($con);
?>