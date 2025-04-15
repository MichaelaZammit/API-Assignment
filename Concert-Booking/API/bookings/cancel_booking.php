<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['booking_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Booking ID is required"]);
    exit();
}

try {
    // Get booking info before deletion
    $select = $db->prepare("SELECT concert_id, tickets_booked FROM bookings WHERE id = ?");
    $select->execute([$data['booking_id']]);
    $booking = $select->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Booking not found"]);
        exit();
    }

    // Delete the booking
    $delete = $db->prepare("DELETE FROM bookings WHERE id = ?");
    $delete->execute([$data['booking_id']]);

    // Restore ticket count
    $restore = $db->prepare("UPDATE concerts SET tickets_available = tickets_available + ? WHERE id = ?");
    $restore->execute([$booking['tickets_booked'], $booking['concert_id']]);

    echo json_encode(["status" => "success", "message" => "Booking cancelled and tickets restored"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Cancel failed: " . $e->getMessage()]);
}
?>
