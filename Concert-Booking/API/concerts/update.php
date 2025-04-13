<?php
// Include DB connection setup
include_once(__DIR__ . '/../Core/initialize.php');

// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Check for required fields
if (
    isset($data['id']) &&
    isset($data['title']) &&
    isset($data['artist']) &&
    isset($data['date']) &&
    isset($data['time']) &&
    isset($data['genre']) &&
    isset($data['location']) &&
    isset($data['tickets_available'])
) {
    // Assign fields to variables
    $id = $data['id'];
    $title = $data['title'];
    $artist = $data['artist'];
    $date = $data['date'];
    $time = $data['time'];
    $genre = $data['genre'];
    $location = $data['location'];
    $tickets = $data['tickets_available'];

    try {
        $query = "UPDATE concerts SET title = ?, artist = ?, date = ?, time = ?, genre = ?, location = ?, tickets_available = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$title, $artist, $date, $time, $genre, $location, $tickets, $id]);

        echo json_encode(["status" => "success", "message" => "Concert updated successfully"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
