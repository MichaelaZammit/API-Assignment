<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit();
}

try {
    // Step 1: Delete bookings related to this user
    $deleteBookings = $db->prepare("DELETE FROM bookings WHERE user_id = ?");
    $deleteBookings->execute([$data['id']]);

    // Step 2: Delete the user
    $deleteUser = $db->prepare("DELETE FROM users WHERE id = ?");
    $deleteUser->execute([$data['id']]);

    echo json_encode(["status" => "success", "message" => "User and related bookings deleted successfully"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
