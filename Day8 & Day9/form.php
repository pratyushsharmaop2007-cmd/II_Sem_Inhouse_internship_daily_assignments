<?php
$errors = [];
$successMessage = "";

$student_id = $name = $email = $college = $branch = $mobile_number = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = trim($_POST["student_id"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $college = trim($_POST["college"] ?? "");
    $branch = trim($_POST["branch"] ?? "");
    $mobile_number = trim($_POST["mobile_number"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($student_id) || !preg_match('/^[A-Za-z0-9_-]+$/', $student_id)) {
        $errors[] = "ID is required and may contain only letters, numbers, underscores, or hyphens.";
    }

    if (strlen($name) < 2 || !preg_match('/^[A-Za-z ]+$/', $name)) {
        $errors[] = "Name must be at least 2 letters and contain only letters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($college)) {
        $errors[] = "College name is required.";
    }

    if (empty($branch)) {
        $errors[] = "Branch name is required.";
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile_number)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (empty($errors)) {
        require_once __DIR__ . '/db_connect.php';

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO students (student_id, Name, Email, College_Name, Branch, Phone_Number, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sssssss', $student_id, $name, $email, $college, $branch, $mobile_number, $password_hash);
            if (mysqli_stmt_execute($stmt)) {
                $successMessage = "Registration successful! Welcome, " . htmlspecialchars($name) . ".";
                $student_id = $name = $email = $college = $branch = $mobile_number = $password = "";
            } else {
                $errors[] = "Database error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f3f7ff, #dfefff);
            color: #1f2937;
        }

        .container {
            max-width: 460px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 10px;
            text-align: center;
            color: #1d4ed8;
        }

        .subtitle {
            text-align: center;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .message {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .message.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
        }

        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Registration</h1>
        <p class="subtitle">Please fill in your details carefully.</p>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="message success">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <form action="form.php" method="post">
            <label for="student_id">ID</label>
            <input type="text" id="student_id" name="student_id" placeholder="Enter your ID" value="<?php echo htmlspecialchars($student_id); ?>" required>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="college">College Name</label>
            <input type="text" id="college" name="college" placeholder="Enter your college" value="<?php echo htmlspecialchars($college); ?>" required>

            <label for="branch">Branch</label>
            <input type="text" id="branch" name="branch" placeholder="Enter your branch" value="<?php echo htmlspecialchars($branch); ?>" required>

            <label for="mobile_number">Phone Number</label>
            <input type="text" id="mobile_number" name="mobile_number" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($mobile_number); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" value="<?php echo htmlspecialchars($password); ?>" required>

            <button type="submit">Submit Form</button>
        </form>
    </div>
</body>
</html>