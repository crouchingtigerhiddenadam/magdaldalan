<?php

require_once 'config.php';

session_start();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $content = htmlentities( $_POST[ 'content' ] );

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare('
        SELECT id
        FROM user
        WHERE username=? AND password_hash=PASSWORD(?);
    ');
    $db_statement->bind_param( 'ss', $username, $password );
    $db_statement->execute();
    $db_user = $db_statement->get_result();

    if ( empty( $db_user ) ) {
        $_SESSION[ 'sender_user_id' ] = $db_user[ 'id' ];
        echo 'User found and registered to session';
    }
    else {
        echo 'No user found';
    }

    $db_statement->close();
    $db_connection->close();
}

?>

<form action="login.php" method="post">
    <label for="">Username</label>
    <input name="username" type="text">
    <label for="">Password</label>
    <input name="password" type="password">
    <button type="submit">Login</button>
</form>