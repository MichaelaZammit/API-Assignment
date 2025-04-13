<?php
include_once(__DIR__ . '/../Core/initialize.php');

try {
    $query = "SELECT id, username, email, created_at FROM users ORDER BY created_at DESC";
    $stmt = $db->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $users]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
