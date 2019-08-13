<?php

require_once '../config.php';

if ( !isset( $_SESSION ) ) session_start();

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $content = htmlentities( $_POST[ 'content' ] );

    // TODO: Place validation here

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare('
        INSERT INTO message (
            sender_user_id,
            recipient_user_id,
            content,
            creation_datetime_utc )
        VALUES (
            ?,
            ?,
            ?,
            UTC_TIMESTAMP() );
    ');
    $db_statement->bind_param( 'iis', $sender_user_id, $recipient_user_id, $content );
    $db_statement->execute();
    $db_statement->close();
    $db_connection->close();
}

?>
        <form action="index.php?r=<?= $recipient_user_id ?>" id="form" method="post" onsubmit="send( event )">
            <input autocomplete="off" id="content" name="content" type="text">
            <button type="submit">Send</button>
        </form>
