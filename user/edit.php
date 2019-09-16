<?php

require '../config.php';

$id = htmlentities( $_GET[ 'id' ] );

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'GET' ) {
  $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
  $db_statement = $db_connection->prepare( "SELECT email FROM user WHERE id = ?;" );
  $db_statement->bind_param( 'i', $id );
  $db_statement->execute();
  $db_result = $db_statement->get_result( MYSQLI_ASSOC );
  $db_row = $db_result->fetch_assoc();
  $db_statement->close();
  $db_connection->close();
  $email_value = $db_row[ 'email' ];
  $password_value = $db_row[ 'password_hash' ];
}

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

  $email_value = htmlentities( $_POST[ 'email' ] );
  if ( empty( $email_value ) ) {
    $email_error = 'You need to enter an email address';
    $valid = false;
  }

  $password_value = htmlentities( $_POST[ 'password' ] );

  if ( !isset( $valid ) ) {
    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare("
      UPDATE
        user
      SET
        email = ?,
        password_hash = COALESCE( NULLIF(PASSWORD(?), ''), password_hash)
      WHERE
        id = ?;
    ");
    $db_statement->bind_param(
      'ssi',
      $email_value,
      $password_value,
      $id );
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
  <title>Edit User</title>
  <link href="../site.css" rel="stylesheet">
</head>
<body>
<main>
  <h2>Edit User</h2>
  <form action="edit.php?id=<?= $id ?>" method="POST">
  <fieldset>
    <label for="email_address">Email Address</label>
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
    <button type="submit">Update</button>
    <a class="button button-outline" href="index.php">Cancel</a>
  </fieldset>
  </form>
</main>
</body>
</html>