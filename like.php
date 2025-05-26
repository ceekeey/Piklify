<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];

    // Check if already liked
    $check = mysqli_query($conn, "SELECT * FROM likes WHERE user_id='$user_id' AND post_id='$post_id'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO likes (user_id, post_id) VALUES ('$user_id', '$post_id')");
    } else {
        // Optional: unlike
        mysqli_query($conn, "DELETE FROM likes WHERE user_id='$user_id' AND post_id='$post_id'");
    }
}

header("Location: index.php");
exit();
