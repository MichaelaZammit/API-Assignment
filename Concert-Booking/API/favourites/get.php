<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit();
}

try {
    $query = "SELECT c.* FROM favourites f
              JOIN concerts c ON f.concert_id = c.id
              WHERE f.user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$data['user_id']]);
    $favourites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $favourites]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
