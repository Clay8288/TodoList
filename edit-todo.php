<?php
//print_r($_GET);

$filename = __DIR__ . "/data/todo.json";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET["id"] ?? " ";

if($id) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];

    if(count($todos)) {
        $todoId = (int)array_search($id, array_column($todos, "id"));
        $todos[$todoId]["done"] = !$todos[$todoId]["done"];
        file_put_contents($filename, json_encode($todos));
    }
}

header("Location: /");