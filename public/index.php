<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\OAuth;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>(Alpha) Co twoim zdanime było trudniejsze?</title>
    <link rel="stylesheet" href="/styles/style.main.css">
    <?php require_once('./styles/style-imports.php') ?>
</head>
<body>
    <?php 
        !OAuth::should_reauthenticate() && require('./components/logout-button.php')
    ?>

    <div class="main-container">
    <?php 
        OAuth::should_reauthenticate() ? 
            require('./components/login-with-usos.php') :
            require('./components/choose-option.php')
    ?>
    </div>
</body>
</html>