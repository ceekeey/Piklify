<?php
session_start();
include('includes/db.php');

if (!isset($_GET['post_id'])) {
    header("Location: index.php");
    exit();
}

$post_id = intval($_GET['post_id']);

// Fetch post info
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = $post_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    echo "Post not found.";
    exit();
}

// Build share URL (adjust domain as needed)
$share_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php#post-" . $post_id;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Share Post - PhotoShare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff0f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h2 {
            color: #d63384;
            margin-bottom: 20px;
        }

        img {
            max-width: 100%;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .caption {
            font-style: italic;
            margin-bottom: 20px;
            color: #666;
        }

        input.share-link {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        button.copy-btn {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        button.copy-btn:hover {
            background-color: #a61e63;
        }

        .social-buttons a {
            display: inline-block;
            margin: 0 10px;
            color: white;
            font-size: 1.4em;
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .social-buttons a.facebook {
            background: #3b5998;
        }

        .social-buttons a.twitter {
            background: #1da1f2;
        }

        .social-buttons a.whatsapp {
            background: #25d366;
        }

        .social-buttons a.facebook:hover {
            background: #2d4373;
        }

        .social-buttons a.twitter:hover {
            background: #0d95e8;
        }

        .social-buttons a.whatsapp:hover {
            background: #1ebea5;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fa fa-share-alt"></i> Share This Post</h2>

        <p><strong>By: <?php echo htmlspecialchars($post['username']); ?></strong></p>
        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post image" />
        <p class="caption"><?php echo htmlspecialchars($post['caption']); ?></p>

        <input class="share-link" type="text" readonly value="<?php echo htmlspecialchars($share_url); ?>"
            id="shareLink" />
        <button class="copy-btn" onclick="copyLink()">Copy Link</button>

        <div class="social-buttons">
            <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>"
                target="_blank" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
            <a class="twitter" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($share_url); ?>"
                target="_blank" title="Share on Twitter"><i class="fab fa-twitter"></i></a>
            <a class="whatsapp" href="https://api.whatsapp.com/send?text=<?php echo urlencode($share_url); ?>"
                target="_blank" title="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>

    <script>
        function copyLink() {
            const copyText = document.getElementById('shareLink');
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value).then(() => {
                alert('Link copied to clipboard!');
            }, () => {
                alert('Failed to copy link.');
            });
        }
    </script>
</body>

</html>