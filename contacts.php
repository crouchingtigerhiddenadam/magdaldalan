<?php

require_once 'config.php';

session_start();

if ( !isset( $_SESSION[ 'user_id' ] ) ) {
    header( 'Location: index.php' );
    die();
}

$sender_user_id = $_SESSION[ 'user_id' ];

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare('
    SELECT id, username
    FROM user;
');
$db_statement->execute();
$db_users = $db_statement->get_result();
$db_statement->close();
$db_connection->close();

?>
<?php foreach ( $db_users as $db_user ) { ?>
        <a href="message/?r=<?= $db_user[ 'id' ] ?>"><?= $db_user[ 'username' ] ?></a><br>
<?php } ?>
