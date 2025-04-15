<?php
include_once(__DIR__ . '/../Core/initialize.php');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Concert ID is required"]);
    exit();
}

try {
    $query = "SELECT 
                id, 
                title, 
                artist, 
                date, 
                time, 
                genre, 
                location, 
                tickets_available,
                artist_image,
                artist_genres,
                spotify_url
              FROM concerts
              WHERE id = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$data['id']]);

    $concert = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($concert) {
        // Convert genres from JSON
        if (!empty($concert['artist_genres'])) {
            $concert['artist_genres'] = json_decode($concert['artist_genres'], true);
        }

        echo json_encode(["status" => "success", "data" => $concert]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Concert not found"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
