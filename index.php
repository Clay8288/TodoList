<?php

const ERROR_REQUIRED = "Veuillez rentrer une todo";
const ERROR_TOO_SHORT = "Veuillez entrer au moins 5 caractères";

$filename = __DIR__ . "/data/todo.json";
$error = "";
$todo = "";
$todos = [];

if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $todo = $_POST["todo"] ?? "";

    if (!$todo) {
        $error = ERROR_REQUIRED;
    } else if (mb_strlen($todo) < 5) {
        $error = ERROR_TOO_SHORT;
    }

    if (!$error) {
        $todos = [...$todos, [
            'name' => $todo,
            'done' => false,
            'id' => time(),
        ]];
        file_put_contents($filename, json_encode($todos));
        header("Location : /");
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<?php require_once __DIR__ . "/templates/header.php" ?>

<body>
    <div class="container">
        <header>
            <div class="logo">TodoList</div>
        </header>
        <div class="content">
            <div class="todo-main">
                <h1>Ma Todo</h1>
                <form action="/" method="POST" class="todo-form">
                    <input value="<?= $todo ?>" name="todo" type="text">
                    <button class="btn">Ajouter</button>
                </form>

                <?php if ($error) : ?>
                    <p><?= $error ?></p>
                <?php endif; ?>

                <ul class="todo-list">
                    <?php foreach ($todos as $todo) : ?>
                        <li class="<?= $todo["done"] ? "todo-done" : "" ?>">
                            <span class="todo-name"><?= $todo['name'] ?></span>
                            <a href="/edit-todo.php?id=<?= $todo['id'] ?>">
                                <button class="btn-validate"><?= $todo["done"] ? "Annuler" : "Valider" ?></button>
                            </a>
                            <a href="/delete-todo.php?id=<?= $todo['id'] ?>">
                                <button class="btn-delete">Supprimer</button>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>

        <?php require_once __DIR__ . "/templates/footer.php" ?>

    </div>

    <script src="/public/js/index.js"></script>
</body>

</html>