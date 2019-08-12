<?php

    $user_id = 1;
    $content = htmlentities( $_POST[ 'content' ] );

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare( '' );
    $db_statement->bind_param( '', '' );
    $db_statement->execute();
    $db_messages = $db_statement->get_result();
    $db_statement->close();
    $db_connection->connection();
    
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
        $db_statement = $db_connection->prepare( '' );
        $db_statement->bind_param( '', '' );
        $db_statement->execute();
        $db_statement->close();
        $db_connection->connection();
    }

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