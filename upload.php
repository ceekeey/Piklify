<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $image_name = basename($_FILES["image"]["name"]);
    $target_dir = "uploads/";
    $target_file = $target_dir . time() . "_" . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO posts (user_id, image_path, caption) VALUES ('$user_id', '$target_file', '$caption')";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            $message = "Error saving post.";
        }
    } else {
        $message = "Error uploading image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload - PhotoShare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #d63384;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="file"],
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background-color: #d63384;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #a61e63;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #d63384;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fa fa-upload"></i> Upload a Photo</h2>

        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <textarea name="caption" placeholder="Say something..." required></textarea>
            <button type="submit"><i class="fa fa-cloud-upload-alt"></i> Upload</button>
        </form>

        <div class="back-link">
            <p><a href="index.php"><i class="fa fa-arrow-left"></i> Back to Home</a></p>
        </div>
    </div>
</body>

</html>