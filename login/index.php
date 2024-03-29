<?php

require_once '../config.php';

if ( isset( $_SESSION ) ) {
  session_destroy();
}

session_start();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
  $email_value = htmlentities( $_POST[ 'email' ] );
  $password_value = htmlentities( $_POST[ 'password' ] );
  $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
  $db_statement = $db_connection->prepare("
    SELECT id
    FROM user
    WHERE email = ? AND password_hash = PASSWORD( ? ); ");
  $db_statement->bind_param( 'ss', $email_value, $password_value );
  $db_statement->execute();
  $db_users = $db_statement->get_result();
  $db_user = $db_users->fetch_assoc();
  $db_statement->close();
  $db_connection->close();

  if ( isset( $db_user ) ) {
    $_SESSION[ 'user_id' ] = $db_user[ 'id' ];
    header( 'Location: ../message/' );
    die();
  }
  else {
    echo 'Email address or password is incorrect';
  }
}

?>
<html>
<head>
    <title></title>
    <link href="../site.css" rel="stylesheet">
</head>
<body>
<main>
  <form action="index.php" method="post">
    <label for="email">Email Address</label>
    <input id="email" name="email" type="text" required>
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required>
    <button type="submit">Login</button>
  </form>
  <a href="#">Forgot password?</a>
</main>
</body>
</html>