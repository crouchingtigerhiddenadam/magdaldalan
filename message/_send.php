<?php

    require_once '../config.php';

    $user_id = 2;
    
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        $content = htmlentities( $_POST[ 'content' ] );

        $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
        $db_statement = $db_connection->prepare( 'INSERT INTO message(user_id, content, creation_datetime_utc) VALUES(?, ?, UTC_DATE());' );
        $db_statement->bind_param( 'is', $user_id, $content );
        $db_statement->execute();
        $db_statement->close();
        $db_connection->close();
    }

?>

<form action="index.php" id="form" method="post" onsubmit="send( event )">
    <input id="content" name="content" type="text">
    <button type="submit">Send</button>
</form>