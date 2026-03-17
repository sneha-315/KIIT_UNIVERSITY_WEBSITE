<?php
session_start();
header("Content-Type: application/json");
require_once '../config/database.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(array("success" => false, "message" => "Unauthorized access"));
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get all students or single student
        if(isset($_GET['id'])) {
            $query = "SELECT * FROM students WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $_GET['id']);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(array("success" => true, "data" => $student));
            } else {
                echo json_encode(array("success" => false, "message" => "Student not found"));
            }
        } else {
            $query = "SELECT * FROM students ORDER BY created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array("success" => true, "data" => $students));
        }
        break;
        
    case 'POST':
        // Add new student
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->student_id) && !empty($data->full_name) && !empty($data->email) && 
           !empty($data->course) && !empty($data->year)) {
            
            $query = "INSERT INTO students (student_id, full_name, email, phone, course, year, dob, gender, hostel, address) 
                     VALUES (:student_id, :full_name, :email, :phone, :course, :year, :dob, :gender, :hostel, :address)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(":student_id", $data->student_id);
            $stmt->bindParam(":full_name", $data->full_name);
            $stmt->bindParam(":email", $data->email);
            $stmt->bindParam(":phone", $data->phone);
            $stmt->bindParam(":course", $data->course);
            $stmt->bindParam(":year", $data->year);
            $stmt->bindParam(":dob", $data->dob);
            $stmt->bindParam(":gender", $data->gender);
            $stmt->bindParam(":hostel", $data->hostel);
            $stmt->bindParam(":address", $data->address);
            
            if($stmt->execute()) {
                echo json_encode(array(
                    "success" => true, 
                    "message" => "Student added successfully",
                    "id" => $db->lastInsertId()
                ));
            } else {
                echo json_encode(array("success" => false, "message" => "Failed to add student"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Required fields missing"));
        }
        break;
        
    case 'PUT':
        // Update student
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->student_id) && !empty($data->full_name)) {
            
             $query = "UPDATE students SET 
                      student_id = :student_id,
                      full_name = :full_name,
                      email = :email,
                      phone = :phone,
                      course = :course,
                      year = :year,
                      dob = :dob,
                      gender = :gender,
                      hostel = :hostel,
                      address = :address
                      WHERE id = :id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(":id", $data->id);
            $stmt->bindParam(":student_id", $data->student_id);
            $stmt->bindParam(":full_name", $data->full_name);
            $stmt->bindParam(":email", $data->email);
            $stmt->bindParam(":phone", $data->phone);
            $stmt->bindParam(":course", $data->course);
            $stmt->bindParam(":year", $data->year);
            $stmt->bindParam(":dob", $data->dob);
            $stmt->bindParam(":gender", $data->gender);
            $stmt->bindParam(":hostel", $data->hostel);
            $stmt->bindParam(":address", $data->address);
            
            if($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "Student updated successfully"));
            } else {
                echo json_encode(array("success" => false, "message" => "Failed to update student"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Required fields missing"));
        }
        break;
        
    case 'DELETE':
        // Delete student
        if(isset($_GET['id'])) {
            $query = "DELETE FROM students WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $_GET['id']);
            
            if($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "Student deleted successfully"));
            } else {
                echo json_encode(array("success" => false, "message" => "Failed to delete student"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Student ID required"));
        }
        break;
        
    default:
        echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>