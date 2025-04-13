<?php
include_once(__DIR__ . '/../Core/initialize.php');

try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM concerts");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "total" => $count['total']]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
