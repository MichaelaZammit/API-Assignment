<?php
include_once(__DIR__ . '/../Core/initialize.php');

try {
    $query = "SELECT * FROM concerts ORDER BY date ASC";
    $stmt = $db->query($query);
    $concerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $concerts]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
