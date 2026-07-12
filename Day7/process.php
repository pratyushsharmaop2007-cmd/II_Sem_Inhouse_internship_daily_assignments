<?php
require 'db.php';

// 1. CREATE (Insert Data)
if (isset($_POST['submit']) && !isset($_POST['id'])) {
    $name = $_POST['name'];
    $college = $_POST['college'];
    $branch = $_POST['branch'];

    if (!empty($name) && !empty($college) && !empty($branch)) {
        $sql = "INSERT INTO students (name, college, branch) VALUES (:name, :college, :branch)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'college' => $college, 'branch' => $branch]);
        
        header("Location: index.php?msg=Data Inserted Successfully");
        exit();
    }
}

// 2. UPDATE (Edit Existing Data)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $college = $_POST['college'];
    $branch = $_POST['branch'];

    $sql = "UPDATE students SET name = :name, college = :college, branch = :branch WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'college' => $college, 'branch' => $branch, 'id' => $id]);

    header("Location: index.php?msg=Data Updated Successfully");
    exit();
}

// 3. DELETE (Remove Data)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM students WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    header("Location: index.php?msg=Data Deleted Successfully");
    exit();
}
?>