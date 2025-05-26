<?php
session_start();
include('includes/db.php');

// Fetch all posts with user info
$sql = "SELECT posts.*, users.username, 
        (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as likes 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PhotoShare Media App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #fef2f8;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #d63384;
            margin-bottom: 20px;
        }

        p a {
            color: #d63384;
            font-weight: bold;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .post {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .post img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .post .caption {
            font-size: 1.1em;
            margin-bottom: 10px;
            color: #444;
        }

        .post-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .post-actions form {
            display: inline;
        }

        .post-actions button {
            background-color: #d63384;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background 0.3s;
        }

        .post-actions button:hover {
            background-color: #a61e63;
        }

        .post-actions a {
            color: #d63384;
            font-size: 0.9em;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 30px 0;
        }

        .welcome {
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome a {
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to PhotoShare!</h1>

        <div class="welcome">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> |
                    <a href="upload.php"><i class="fa fa-upload"></i> Upload</a> |
                    <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
                </p>
            <?php else: ?>
                <p>
                    <a href="login.php"><i class="fa fa-sign-in-alt"></i> Login</a> or
                    <a href="register.php"><i class="fa fa-user-plus"></i> Register</a>
                </p>
            <?php endif; ?>
        </div>

        <hr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="post" id="post-<?php echo $row['id']; ?>">
                <p><strong><?php echo htmlspecialchars($row['username']); ?></strong></p>
                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image">
                <p class="caption"><?php echo htmlspecialchars($row['caption']); ?></p>
                <div class="post-actions">
                    <form method="POST" action="like.php">
                        <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                        <button type="submit"><i class="fa fa-heart"></i> <?php echo $row['likes']; ?></button>
                    </form>
                    <a href="share.php?post_id=<?php echo $row['id']; ?>"><i class="fa fa-share"></i> Share</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>