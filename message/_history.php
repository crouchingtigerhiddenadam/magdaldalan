<?php

    require_once '../config.php';

    session_start();
    $user_id = $_SESSION[ 'user_id' ];

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare( 'SELECT * FROM message WHERE user_id = ?;' );
    $db_statement->bind_param( 'i', $user_id );
    $db_statement->execute();
    $db_messages = $db_statement->get_result();
    $db_statement->close();

    $db_connection->close();

?>

<?php foreach ( $db_messages as $db_message ) { ?>
<p>
    <?= $db_message[ 'content' ] ?>
</p>
<?php } ?>