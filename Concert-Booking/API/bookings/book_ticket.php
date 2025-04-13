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

    try {
        // Check if enough tickets are available
        $checkQuery = "SELECT tickets_available FROM concerts WHERE id = ?";
        $stmt = $db->prepare($checkQuery);
        $stmt->execute([$concert_id]);
        $concert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$concert) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Concert not found"]);
            exit();
        }

        if ($concert['tickets_available'] < $tickets_requested) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Not enough tickets available"]);
            exit();
        }

        // Book the ticket
        $bookQuery = "INSERT INTO bookings (user_id, concert_id, tickets_booked) VALUES (?, ?, ?)";
        $stmt = $db->prepare($bookQuery);
        $stmt->execute([$user_id, $concert_id, $tickets_requested]);

        // Update tickets_available
        $updateQuery = "UPDATE concerts SET tickets_available = tickets_available - ? WHERE id = ?";
        $stmt = $db->prepare($updateQuery);
        $stmt->execute([$tickets_requested, $concert_id]);

        echo json_encode(["status" => "success", "message" => "Tickets booked successfully"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Booking failed: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
