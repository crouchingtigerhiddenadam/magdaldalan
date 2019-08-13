<?php

require_once '../config.php';

if ( !isset( $_SESSION ) ) session_start();

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare('
    SELECT content
    FROM message
    WHERE ( sender_user_id = ? AND recipient_user_id = ? ) OR
          ( recipient_user_id = ? AND sender_user_id = ? );
');
$db_statement->bind_param( 'iiii', $sender_user_id, $recipient_user_id,
    $sender_user_id, $recipient_user_id );
$db_statement->execute();
$db_messages = $db_statement->get_result();
$db_statement->close();
$db_connection->close();

?>

s: <?= $sender_user_id ?> to r: <?= $recipient_user_id ?>

<?php foreach ( $db_messages as $db_message ) { ?>
        <p><?= $db_message[ 'content' ] ?></p>
<?php } ?>
