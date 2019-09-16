<?php

require '../config.php';

$id = htmlentities( $_GET[ 'id' ] );

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'GET' ) {
  $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
  $db_statement = $db_connection->prepare( "SELECT * FROM user WHERE id = ?;" );
  $db_statement->bind_param( 'i', $id );
  $db_statement->execute();
  $db_result = $db_statement->get_result( MYSQLI_ASSOC );
  $db_row = $db_result->fetch_assoc();
  $db_statement->close();
  $db_connection->close();
  $email = $db_row[ 'email' ];
}

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
  $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
  $db_statement = $db_connection->prepare( "DELETE FROM user WHERE id = ?;" );
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
      <label for="email_address">Email Address</label>
      <input disabled id="email" name="email" readonly type="text" value="<?= $email ?>">
      <button type="submit">Delete</button>
      <a class="button button-outline" href="index.php">Cancel</a>
    </fieldset>
  </form>
</main>
</body>
</html>