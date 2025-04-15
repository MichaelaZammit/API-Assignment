<?php
// Get access token from auth.php
$tokenResponse = file_get_contents('http://localhost:8888/Concert-Booking/API/spotify/auth.php');
$token = json_decode($tokenResponse, true)['access_token'] ?? null;

if (!$token || !isset($_GET['artist'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing artist name or token"]);
    exit();
}

$artistName = urlencode($_GET['artist']);
$apiUrl = "https://api.spotify.com/v1/search?q=$artistName&type=artist&limit=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$artist = json_decode($response, true)['artists']['items'][0] ?? null;

if ($artist) {
    echo json_encode([
        "status" => "success",
        "name" => $artist['name'],
        "genres" => $artist['genres'],
        "image" => $artist['images'][0]['url'] ?? null,
        "spotify_url" => $artist['external_urls']['spotify']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Artist not found"]);
}
?>
