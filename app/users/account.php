<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST["oldpassword"], $_POST["password"], $_POST["passwordrepeat"])) {
    $oldPassword = $_POST["oldpassword"];
    $newPassword = $_POST["password"];
    $newPasswordRepeat = $_POST["passwordrepeat"];

    $statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $statement->execute([
        "id" => $_SESSION["user"]["id"]
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    $oldPasswordInfo = $user["password"];

    if (password_verify($oldPassword, $oldPasswordInfo) && $newPassword === $newPasswordRepeat && $newPassword !== $oldPassword) {
        $changeQuery = $pdo->prepare("UPDATE users SET password = :newpassword WHERE id = :id");
        $changeQuery->execute([
            ":newpassword" => password_hash($newPassword, PASSWORD_DEFAULT),
            ":id" => $_SESSION["user"]["id"]
        ]);

        redirect("/app/users/logout.php");
    } else {
        die(var_dump($pdo->errorInfo()));
    }
}
