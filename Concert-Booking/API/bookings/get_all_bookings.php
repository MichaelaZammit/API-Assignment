<?php
include_once(__DIR__ . '/../Core/initialize.php');

try {
    $query = "
        SELECT 
            b.id AS booking_id,
            u.username,
            c.title AS concert_title,
            c.date AS concert_date,
            b.tickets_booked,
            b.booking_date
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN concerts c ON b.concert_id = c.id
        ORDER BY b.booking_date DESC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $bookings
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
