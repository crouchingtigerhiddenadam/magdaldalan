<?php

require_once '../config.php';

session_start();

if ( !isset( $_SESSION[ 'user_id' ] ) ) {
    header( 'Location: ../index.php' );
    die();
}

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare('
    SELECT id, username
    FROM user
    WHERE id != ?;
');
$db_statement->bind_param( 'i', $sender_user_id );
$db_statement->execute();
$db_users = $db_statement->get_result();
$db_statement->close();
$db_connection->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Magdaldalan</title>
    <link href="index.css" rel="stylesheet">
</head>
<body>
    <section id="contacts">
<?php foreach ( $db_users as $db_user ) { ?>
<?php if ( $db_user[ 'id' ] == $recipient_user_id ) { ?>
        <article>
            <strong><?= $db_user[ 'username' ] ?></strong>
        </article>
<?php } else { ?>
        <article>
            <a href="?r=<?= $db_user[ 'id' ] ?>"><?= $db_user[ 'username' ] ?></a>
        </article>
<?php } ?>
<?php } ?>
    </section>
    <section id="history">
<?php include '_history.php' ?>
    </section>
    <section id="send">
<?php include '_send.php' ?>
    </section>
    <script src="index.js"></script>
<?php if ( !empty( $recipient_user_id ) ) { ?>
<?php } ?>
</body>
</html>