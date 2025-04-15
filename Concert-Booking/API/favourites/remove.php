<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['concert_id'])) {
    try {
        $query = "DELETE FROM favourites WHERE user_id = ? AND concert_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$data['user_id'], $data['concert_id']]);

        echo json_encode(["status" => "success", "message" => "Favourite removed"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "User ID and Concert ID are required"]);
}
?>
