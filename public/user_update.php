<?php
include('../inc/config.php');

if (isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $mysqli->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Error updating user.');</script>";
    }
}
?>
