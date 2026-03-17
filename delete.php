<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_students";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get student_id from POST - check all possible parameter names
    $student_id = '';
    
    // Try different parameter names that JavaScript might send
    $possible_params = ['student_id', 'id', 'delete_student_id', 'studentId'];
    
    foreach ($possible_params as $param) {
        if (isset($_POST[$param]) && !empty(trim($_POST[$param]))) {
            $student_id = trim($_POST[$param]);
            break;
        }
    }
    
    // Debug: Log what we received
    error_log("Received POST data: " . print_r($_POST, true));
    
    if (empty($student_id)) {
        echo "Error: No valid Student ID provided. Received: " . print_r($_POST, true);
        mysqli_close($conn);
        exit();
    }
    
    // Clean the input
    $student_id = mysqli_real_escape_string($conn, $student_id);
    
    // Check if student exists first
    $check_sql = "SELECT student_id FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $check_sql);
    
    if (!$result) {
        echo "Error checking student: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($result) == 0) {
        echo "Error: Student with ID '$student_id' not found in database";
    } else {
        // Student exists, proceed with deletion
        $sql = "DELETE FROM students WHERE student_id = '$student_id'";
        
        if (mysqli_query($conn, $sql)) {
            $affected_rows = mysqli_affected_rows($conn);
            if ($affected_rows > 0) {
                echo "success";
            } else {
                echo "Error: Deletion failed - no rows affected";
            }
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }
} else {
    echo "Invalid request method. Use POST.";
}

mysqli_close($conn);
?>