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
    <style>

        @import url('https://fonts.googleapis.com/css?family=Roboto');

        body {
            font-family: 'Roboto', sans-serif;
        }

        .history-message {
            background-color: #eee;
            border-radius: 5px;
            margin: 20px 0;
            padding: 10px 20px;
        }

        .send-form {
            background-color: #eee;
            border-radius: 5px;
            display: flex;
            margin: 20px 0;
            padding: 10px 15px;
        }

        .send-submit {
            align-self: center;
            cursor: pointer;
            border-radius: 5px;
            border: 1px solid #000;
            font-size: 16px;
            padding: 10px 15px;
        }

        .send-content {
            background: transparent;
            border: none;
            font-size: 16px;
            flex-grow: 1;
            outline: none;
        }

    </style>
</head>
<body>
    <h2>Contacts</h2>
<?php foreach ( $db_users as $db_user ) { ?>
<?php if ( $db_user[ 'id' ] == $recipient_user_id ) { ?>
    <strong><?= $db_user[ 'username' ] ?></strong><br>
<?php } else { ?>
    <a href="?r=<?= $db_user[ 'id' ] ?>"><?= $db_user[ 'username' ] ?></a><br>
<?php } ?>
<?php } ?>
<?php if ( !empty( $recipient_user_id ) ) { ?>
    <h2>Chat</h2>
    <section id="history">
<?php include '_history.php' ?>
    </section>
    <section id="send">
<?php include '_send.php' ?>
    </section>
    <script src="index.js"></script>
<?php } ?>
</body>
</html>