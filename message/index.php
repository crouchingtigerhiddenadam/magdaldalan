<?php

    session_start();
    $_SESSION[ 'user_id' ] = 2;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Magdaldalan</title>
</head>
<body>
    <section id="history">
        <?php include '_history.php' ?>
    </section>
    <section id="send">
        <?php include '_send.php' ?>
    </section>
    <script src="index.js"></script>
</body>
</html>