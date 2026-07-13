<?php
include_once("db_connect.php");

$error = "";
$email = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"] ?? "");
    $password = mysqli_real_escape_string($conn, $_POST["password"] ?? "");

    if ($email == "" || $password == "") {
        $error = "All fields are required.";
    } else {
        $selectQuery = "SELECT * FROM user WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $selectQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($user) {
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid Credentials.";
            }
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}
?>