<?php
    
require '../config.php';

$id = htmlentities( $_GET[ 'id' ] );

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
        $db_statement = $db_connection->prepare( "UPDATE user SET email_address = ?, password_hash = IFNULL(PASSWORD(?),password_hash) WHERE id = ?;" );
        $db_statement->bind_param( 'ssi', $email, $password, $id );
        $db_statement->execute();
        $db_statement->close();
        $db_connection->close();
    
        header( 'Location: index.php' );
        die();
    }
}
else {
    
    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare( "SELECT * FROM user WHERE id = ?;" );
    $db_statement->bind_param( 'i', $id );
    $db_statement->execute();
    $db_result = $db_statement->get_result();
    $db_row = $db_result->fetch_assoc();
    $db_statement->close();
    $db_connection->close();
    
    $email = $db_row[ 'email_address' ];
    $password = $db_row[ 'password_hash' ];
}

?>
<html>
<head>
    <title>Edit User</title>
    
    <link href="../site.css" rel="stylesheet">
</head>
<body>

<main>
    <h2>Edit User</h2>
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
            <button type="submit">Update</button>
            <a class="button button-outline" href="index.php">Cancel</a>
        </fieldset>
    </form>
</main>
</body>
</html>