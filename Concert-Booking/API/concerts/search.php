<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

$search = $data['keyword'] ?? '';

try {
    $query = "SELECT * FROM concerts WHERE title LIKE :search OR artist LIKE :search OR genre LIKE :search ORDER BY date ASC";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':search', "%$search%");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $results]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
