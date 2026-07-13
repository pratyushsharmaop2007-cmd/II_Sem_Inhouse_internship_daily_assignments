<?php
include_once("db_connect.php");

$error = "";

$name = "";
$email = "";
$password = "";
$confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"] ?? "");
    $email = mysqli_real_escape_string($conn, $_POST["email"] ?? "");
    $password = mysqli_real_escape_string($conn, $_POST["password"] ?? "");
    $confirmPassword = mysqli_real_escape_string($conn, $_POST["confirmPassword"] ?? "");

    if ($name == "" || $email == "" || $password == "" || $confirmPassword == "") {
        $error = "All fields are required.";
    } elseif ($password != $confirmPassword) {
        $error = "Password does not match.";
    } else {
        $insertQuery = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $password);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: success.php");
                exit();
            } else {
                $error = "Error occurred while storing data.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}
?>