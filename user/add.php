<?php

require '../config.php';

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $email = htmlentities( $_POST[ 'email_address' ] );
    $password = htmlentities( $_POST[ 'password' ] );
    $valid = true;
 
    if ( empty($email) ) {
        $email_error = 'You need to enter an email address';
        $valid = false;
    }

    if ( empty($password) ) {
        $password_error = 'You need to enter a password';
        $valid = false;
    }

    if ( $valid ) {

        $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
        $db_statement = $db_connection->prepare( "INSERT INTO user(email_address, password_hash) VALUES(?, PASSWORD(?));");
        $db_statement->bind_param( 'ss', $email, $password );
        $db_statement->execute();
        $db_statement->close();
        $db_connection->close();

        header( 'Location: index.php' );
        die();
    }
}

?>
<html>
<head>
    <title>Add User</title>
    
    <link href="../site.css" rel="stylesheet">
</head>
<body>

<main>
    <h2>Add User</h2>
    <form method="POST">
        <fieldset>
            <label for="email_address">Email Address</label>
            <?php if ( isset($email_error) ) { ?>
                <span class="error"><?= $email_error ?></span>
                <input class="error" id="email_address" name="email_address" type="text" value="<?= $email ?>">
            <?php } else { ?>
                <input id="email_address" name="email_address" type="text" value="<?= $email ?>">
            <?php } ?>

            <label for="password">Password</label>
            <?php if ( isset($password_error) ) { ?>
                <span class="error"><?= $password_error ?></span>
                <input class="error" id="password" name="password" type="password" value="<?= $password ?>">
            <?php } else { ?>
                <input id="password" name="password" type="password" value="<?= $password ?>">
            <?php } ?>
            <button type="submit">Add</button>
            <a class="button button-outline" href="index.php">Cancel</a>
        </fieldset>
    </form>
</main>
</body>
</html>