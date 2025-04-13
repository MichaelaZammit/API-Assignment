<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once("../../Core/initialize.php");

$concert = new Concert($db);
$result = $concert->read();
$num = $result->rowCount();

if($num > 0) {
    $concerts_arr = array();
    $concerts_arr["data"] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $concert_item = array(
            "concert_id" => $concert_id,
            "title" => $title,
            "date" => $date,
            "time" => $time,
            "genre" => $genre
        );
        array_push($concerts_arr["data"], $concert_item);
    }

    echo json_encode($concerts_arr);
} else {
    echo json_encode(["message" => "No concerts found."]);
}
?>
