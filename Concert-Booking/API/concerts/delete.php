<?php
// Include DB connection setup
include_once(__DIR__ . '/../Core/initialize.php');

// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Check if ID is provided
if (isset($data['id'])) {
    $id = $data['id'];

    try {
        $query = "DELETE FROM concerts WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Concert deleted successfully"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Concert ID is required"]);
}
?>
