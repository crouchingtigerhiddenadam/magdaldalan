<?php

require_once '../config.php';

if ( !isset( $_SESSION ) ) session_start();

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare('
    SELECT
        u.id,
        u.username,
        COUNT( rm.id ) AS unread_count
    FROM user AS u
    LEFT JOIN message AS rm ON
        u.id = rm.sender_user_id AND
        rm.recipient_user_id = ? AND
        rm.read_datetime_utc IS NULL
    WHERE u.id != ?
    GROUP BY u.id, u.username;
');
$db_statement->bind_param( 'ii', $sender_user_id, $sender_user_id );
$db_statement->execute();
$db_contacts = $db_statement->get_result();
$db_statement->close();
$db_connection->close();

?>
<?php foreach ( $db_contacts as $db_contact ) { ?>
<?php if ( $db_contact[ 'id' ] == $recipient_user_id ) { ?>
        <article>
            <strong><?= $db_contact[ 'username' ] ?></strong>
        </article>
<?php } else { ?>
        <article>
            <a href="?r=<?= $db_contact[ 'id' ] ?>"><?= $db_contact[ 'username' ] ?></a>
<?php if ( $db_contact[ 'unread_count' ] != 0 ) { ?>
            <small><?= $db_contact[ 'unread_count' ] ?></small>
<?php } ?>
        </article>
<?php } ?>
<?php } ?>