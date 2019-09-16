<?php

require '../config.php';

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

  $email_value = htmlentities( $_POST[ 'email' ] );
  $password_value = htmlentities( $_POST[ 'password' ] );
  $valid = true;

  if ( empty($email_value) ) {
    $email_error = 'You need to enter an email address';
    $valid = false;
  }

  if ( empty($password_value) ) {
    $password_error = 'You need to enter a password';
    $valid = false;
  }

  if ( $valid ) {
    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare("
        INSERT INTO user( email, password_hash )
        VALUES( ?, PASSWORD(?) ); ");
    $db_statement->bind_param( 'ss', $email_value, $password_value );
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
  <form action="add.php" method="POST">
  <fieldset>
    <label for="email">Email Address</label>
    <?php if ( isset($email_error) ) { ?>
      <span class="error"><?= $email_error ?></span>
      <input class="error" id="email" name="email" type="text" value="<?= $email_value ?>">
    <?php } else { ?>
        <input id="email" name="email" type="text" value="<?= $email_value ?>">
    <?php } ?>

    <label for="password">Password</label>
    <?php if ( isset($password_error) ) { ?>
      <span class="error"><?= $password_error ?></span>
      <input class="error" id="password" name="password" type="password" value="<?= $password_value ?>">
    <?php } else { ?>
      <input id="password" name="password" type="password" value="<?= $password_value ?>">
    <?php } ?>
    <button type="submit">Add</button>
    <a class="button button-outline" href="index.php">Cancel</a>
  </fieldset>
  </form>
</main>
</body>
</html>