<?php
include_once(__DIR__ . '/../Core/initialize.php');

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['title']) &&
    isset($data['artist']) &&
    isset($data['date']) &&
    isset($data['time']) &&
    isset($data['genre']) &&
    isset($data['location']) &&
    isset($data['tickets_available'])
) {
    $title = $data['title'];
    $artist = $data['artist'];
    $date = $data['date'];
    $time = $data['time'];
    $genre = $data['genre'];
    $location = $data['location'];
    $tickets = $data['tickets_available'];

    // 🔗 CALL SPOTIFY
    $spotify = json_decode(file_get_contents("http://localhost:8888/Concert-Booking/API/spotify/search_artist.php?artist=" . urlencode($artist)), true);

    // Defaults if not found
    $artist_image = $spotify['image'] ?? null;
    $artist_genres = isset($spotify['genres']) ? json_encode($spotify['genres']) : null;
    $spotify_url = $spotify['spotify_url'] ?? null;

    try {
        $query = "INSERT INTO concerts 
        (title, artist, date, time, genre, location, tickets_available, artist_image, artist_genres, spotify_url) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->execute([
            $title, $artist, $date, $time, $genre, $location, $tickets,
            $artist_image, $artist_genres, $spotify_url
        ]);

        echo json_encode(["status" => "success", "message" => "Concert created successfully"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
