<?php

    require_once '../config.php';

    $user_id = 2;
    $content = htmlentities( $_POST[ 'content' ] );

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        $db_statement = $db_connection->prepare( 'INSERT INTO message(user_id, content, creation_datetime_utc) VALUES(?, ?, UTC_DATE());' );
        $db_statement->bind_param( 'is', $user_id, $content );
        $db_statement->execute();
        $db_statement->close();
    }

    $db_statement = $db_connection->prepare( 'SELECT * FROM message WHERE user_id = ?;' );
    $db_statement->bind_param( 'i', $user_id );
    $db_statement->execute();
    $db_messages = $db_statement->get_result();
    $db_statement->close();

    $db_connection->close();

?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <?php foreach ( $db_messages as $db_message ) { ?>
    <p>
        <?= $db_message[ 'content' ] ?>
    </p>
    <?php } ?>
    <form method="post">
        <textarea name="content"></textarea>
        <button type="submit">Send</button>
    </form>
</body>
</html>