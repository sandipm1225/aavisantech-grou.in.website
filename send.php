<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!$name || !$email || !$message) {
        $error = "Please fill all required fields.";
    } else {

        $webhook = "https://hooks.slack.com/services/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";

        $payload = json_encode([
            "text" => "🚀 New Website Inquiry",
            "attachments" => [
                [
                    "color" => "#36a64f",
                    "fields" => [
                        ["title" => "Name", "value" => $name, "short" => true],
                        ["title" => "Email", "value" => $email, "short" => true],
                        ["title" => "Subject", "value" => $subject, "short" => false],
                        ["title" => "Message", "value" => $message, "short" => false],
                        ["title" => "Time", "value" => date("Y-m-d H:i:s"), "short" => false]
                    ]
                ]
            ]
        ]);

        $ch = curl_init($webhook);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        $success = "Message sent successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact - AAVISANTECH IT 360</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { background:#f5f5f5; padding-top:50px; }
        .contact-box {
            background:#fff;
            padding:40px;
            max-width:600px;
            margin:auto;
            border-radius:8px;
            box-shadow:0 5px 20px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background:#007bff;
            border:none;
        }
    </style>
</head>
<body>

<div class="contact-box">
    <h2 class="text-center">Contact Us</h2>
    <hr>

    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control">
        </div>

        <div class="form-group">
            <label>Message *</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            Send Message
        </button>
    </form>
</div>

</body>
</html>