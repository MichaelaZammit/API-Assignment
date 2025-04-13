<?php
include_once(__DIR__ . '/../Core/initialize.php');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit();
}

$fields = ['username', 'email', 'password'];
$setParts = [];
$params = [];

foreach ($fields as $field) {
    if (isset($data[$field])) {
        if ($field === 'password') {
            $data[$field] = password_hash($data[$field], PASSWORD_DEFAULT);
        }
        $setParts[] = "$field = :$field";
        $params[":$field"] = $data[$field];
    }
}

if (empty($setParts)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No fields to update"]);
    exit();
}

$params[":id"] = $data['id'];
$query = "UPDATE users SET " . implode(", ", $setParts) . " WHERE id = :id";

try {
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    echo json_encode(["status" => "success", "message" => "User updated successfully"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
