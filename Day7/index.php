<?php 
require 'db.php'; 

// Fetch all records (READ)
$stmt = $pdo->query("SELECT * FROM students");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if we are in "Edit Mode"
$edit_mode = false;
$edit_data = ['id' => '', 'name' => '', 'college' => '', 'branch' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-group { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-delete { color: red; }
    </style>
</head>
<body>

    <h1>Student Management (CRUD)</h1>
    
    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green;"><strong><?= htmlspecialchars($_GET['msg']) ?></strong></p>
    <?php endif; ?>

    <form action="process.php" method="post">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>

        <div class="form-group"> 
            <input type="text" name="name" placeholder="enter your name" value="<?= htmlspecialchars($edit_data['name']) ?>" required> 
        </div>
        <div class="form-group"> 
            <input type="text" name="college" placeholder="enter your college" value="<?= htmlspecialchars($edit_data['college']) ?>" required> 
        </div>
        <div class="form-group"> 
            <input type="text" name="branch" placeholder="enter your branch" value="<?= htmlspecialchars($edit_data['branch']) ?>" required> 
        </div>

        <div> 
            <?php if ($edit_mode): ?>
                <input type="submit" name="update" value="Update Data">
                <a href="index.php">Cancel</a>
            <?php else: ?>
                <input type="submit" name="submit" value="Submit">
            <?php endif; ?>
        </div>
    </form>

    <hr>

    <h2>Submitted Records</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>College</th>
                <th>Branch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $student['id'] ?></td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['college']) ?></td>
                    <td><?= htmlspecialchars($student['branch']) ?></td>
                    <td>
                        <a href="index.php?edit=<?= $student['id'] ?>">Edit</a> | 
                        <a href="process.php?delete=<?= $student['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>