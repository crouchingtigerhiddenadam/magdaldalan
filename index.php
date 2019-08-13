<?php

require_once 'config.php';

session_destroy();
session_start();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $username = htmlentities( $_POST[ 'username' ] );
    $password = htmlentities( $_POST[ 'password' ] );

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare('
        SELECT id
        FROM user
        WHERE username = ? AND password_hash = PASSWORD( ? );
    ');
    $db_statement->bind_param( 'ss', $username, $password );
    $db_statement->execute();
    $db_users = $db_statement->get_result();
    $db_user = $db_users->fetch_assoc();
    $db_statement->close();
    $db_connection->close();

    if ( isset( $db_user ) ) {

        $_SESSION[ 'user_id' ] = $db_user[ 'id' ];

        header( 'Location: contacts.php' );
        die();
    }
}

?>

<form action="index.php" method="post">
    <label for="username">Username</label>
    <input id="username" name="username" type="text">
    <label for="password">Password</label>
    <input id="password" name="password" type="password">
    <button type="submit">Login</button>
</form>