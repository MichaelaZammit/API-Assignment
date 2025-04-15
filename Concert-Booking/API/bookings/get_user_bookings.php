<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit();
}

try {
    $query = "
        SELECT 
            b.id AS booking_id,
            c.title AS concert_title,
            c.date AS concert_date,
            b.tickets_booked,
            b.booking_date
        FROM bookings b
        JOIN concerts c ON b.concert_id = c.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$data['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $bookings]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
