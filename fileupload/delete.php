<?php

require '../config.php';

$id = htmlentities( $_GET[ 'id' ] );

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare( "SELECT * FROM attachment WHERE id = ?;" );
    $db_statement->bind_param( 'i', $id );
    $db_statement->execute();
    $db_result = $db_statement->get_result();
    $db_row = $db_result->fetch_assoc();
    $db_statement->close();

    $store = $db_row[ 'store' ];
    unlink( $store );

    $db_statement = $db_connection->prepare( "DELETE FROM attachment WHERE id = ?;" );
    $db_statement->bind_param( 'i', $id );
    $db_statement->execute();
    $db_statement->close();
    $db_connection->close();

    header( 'Location: index.php' );
    die();
}


?>
<html>
<head>
    <title>Delete User</title>

    <link href="../site.css" rel="stylesheet">
</head>
<body>

<main>
    <h2>Delete User</h2>
    <form method="POST">
        <fieldset>
            <label for="filename">Filename</label>
            <input disabled id="filename" name="filename" readonly type="text" value="<?= $filename ?>">
            <label for="store">Store</label>
            <input disabled id="store" name="store" readonly type="store" value="<?= $store ?>">
            <button type="submit">Delete</button>
            <a class="button button-outline" href="index.php">Cancel</a>
        </fieldset>
    </form>
</main>
</body>
</html>