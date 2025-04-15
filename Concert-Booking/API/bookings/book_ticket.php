<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['user_id']) &&
    isset($data['concert_id']) &&
    isset($data['tickets_booked'])
) {
    $user_id = $data['user_id'];
    $concert_id = $data['concert_id'];
    $tickets_requested = $data['tickets_booked'];

    if ($tickets_requested <= 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Ticket quantity must be greater than zero"]);
        exit();
    }

    try {
        // Check if concert exists and get tickets
        $checkQuery = "SELECT * FROM concerts WHERE id = ?";
        $stmt = $db->prepare($checkQuery);
        $stmt->execute([$concert_id]);
        $concert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$concert) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Concert not found"]);
            exit();
        }

        // Check if user already booked this concert
        $checkDuplicate = $db->prepare("SELECT * FROM bookings WHERE user_id = ? AND concert_id = ?");
        $checkDuplicate->execute([$user_id, $concert_id]);
        if ($checkDuplicate->rowCount() > 0) {
            http_response_code(409);
            echo json_encode(["status" => "error", "message" => "You already booked tickets for this concert"]);
            exit();
        }

        // Check if enough tickets available
        if ($concert['tickets_available'] < $tickets_requested) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Not enough tickets available"]);
            exit();
        }

        // Insert booking
        $bookQuery = "INSERT INTO bookings (user_id, concert_id, tickets_booked) VALUES (?, ?, ?)";
        $stmt = $db->prepare($bookQuery);
        $stmt->execute([$user_id, $concert_id, $tickets_requested]);
        $booking_id = $db->lastInsertId();

        // Update available tickets
        $updateQuery = "UPDATE concerts SET tickets_available = tickets_available - ? WHERE id = ?";
        $stmt = $db->prepare($updateQuery);
        $stmt->execute([$tickets_requested, $concert_id]);

        // Get updated concert info
        $concertInfo = $db->prepare("SELECT * FROM concerts WHERE id = ?");
        $concertInfo->execute([$concert_id]);
        $updatedConcert = $concertInfo->fetch(PDO::FETCH_ASSOC);

        // Response
        echo json_encode([
            "status" => "success",
            "message" => "Tickets booked successfully",
            "booking_id" => $booking_id,
            "concert" => $updatedConcert
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Booking failed: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
