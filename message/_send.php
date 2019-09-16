<?php

require_once '../config.php';

if ( !isset( $_SESSION ) ) {
  session_start();
}

$sender_user_id = $_SESSION[ 'user_id' ];

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

  $content_value = htmlentities( $_POST[ 'content' ] );
  if ( empty( $content_value ) )  {
    $is_valid = false;
  }

  $recipient_user_id = htmlentities( $_GET[ 'r' ] );
  if ( empty( $recipient_user_id )) {
    $is_valid = false;
  }

  if ( !isset( $is_valid ) ) {
    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare("
      INSERT INTO message (
        sender_user_id,
        recipient_user_id,
        content,
        creation_datetime_utc )
      VALUES (?, ?, ?, UTC_TIMESTAMP());
    ");
    $db_statement->bind_param( 'iis', $sender_user_id, $recipient_user_id, $content_value );
    $db_statement->execute();
    $db_statement->close();
    $db_connection->close();
  }
}

?>
  <hr>
  <form action="index.php?r=<?= $recipient_user_id ?>" class="send-form" method="post" onsubmit="send( event )">
    <input autocomplete="off" class="send-content" id="content" name="content" type="text">
    <button class="send-submit" type="submit">Send</button>
  </form>
