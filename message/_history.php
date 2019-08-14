<?php

require_once '../config.php';

if ( !isset( $_SESSION ) ) session_start();

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare('
    SELECT
        m.content AS content,
        m.sender_user_id AS sender_user_id,
        su.username AS sender
    FROM message AS m
    JOIN user AS su ON m.sender_user_id = su.id
    WHERE ( m.sender_user_id = ? AND m.recipient_user_id = ? ) OR
          ( m.recipient_user_id = ? AND m.sender_user_id = ? );
');
$db_statement->bind_param( 'iiii', $sender_user_id, $recipient_user_id,
    $sender_user_id, $recipient_user_id );
$db_statement->execute();
$db_messages = $db_statement->get_result();
$db_statement->close();
$db_connection->close();

?>
<?php if ( empty( $recipient_user_id ) ) { ?>
        Select a user from the left hand side
<?php } else { ?>
<?php foreach ( $db_messages as $db_message ) { ?>
<?php if ( $db_message[ 'sender_user_id' ] == $recipient_user_id ) { ?>
        <article class="received">
            <h1><?= $db_message[ 'sender' ] ?></h1>
            <?= $db_message[ 'content' ] ?> 
        </article>
<?php } else { ?>
        <article>
            <h1><?= $db_message[ 'sender' ] ?></h1>
            <?= $db_message[ 'content' ] ?>* 
        </article>
<?php } ?>
<?php } ?>
<?php } ?>
