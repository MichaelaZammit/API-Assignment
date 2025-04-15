<?php
include_once(__DIR__ . '/../Core/initialize.php');

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
              ORDER BY date ASC";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $concerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Decode genres JSON into arrays for frontend use
    foreach ($concerts as &$concert) {
        if (!empty($concert['artist_genres'])) {
            $concert['artist_genres'] = json_decode($concert['artist_genres'], true);
        }
    }

    echo json_encode(["status" => "success", "data" => $concerts]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
