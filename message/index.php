<?php

    session_start();

    if ( !isset( $_SESSION[ 'user_id' ] ) ) {
        header( 'Location: ../index.php' );
        die();
    }

    $_SESSION[ 'recipient_user_id' ] = $_GET[ 'r' ];

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