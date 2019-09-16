<?php

require_once '../config.php';

if ( !isset( $_SESSION ) ) {
  session_start();
}

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );

// Get users and unread message count
$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare("
  SELECT
    user.id,
    user.email,
    COUNT( unread_message.id ) AS unread_count
  FROM user
  LEFT JOIN message AS unread_message
  ON
    user.id = unread_message.sender_user_id AND
    unread_message.recipient_user_id = ? AND
    unread_message.read_datetime_utc IS NULL
  WHERE
    user.id != ?
  GROUP BY
    user.id, user.email;");
$db_statement->bind_param( 'ii', $sender_user_id, $sender_user_id );
$db_statement->execute();
$db_result = $db_statement->get_result();
$db_contacts = $db_result->fetch_all( MYSQLI_ASSOC );
$db_statement->close();

// Get last 10 messages
$db_statement = $db_connection->prepare("
  SELECT *
  FROM (
    SELECT
      message.id AS id,
      message.sender_user_id AS sender_user_id,
      sender_user.email AS sender,
      message.content AS content,
      message.read_datetime_utc AS read_datetime_utc
    FROM
      message
    JOIN user AS sender_user
    ON
      message.sender_user_id = sender_user.id
    WHERE
      (message.sender_user_id = ? AND message.recipient_user_id = ?) OR
      (message.recipient_user_id = ? AND message.sender_user_id = ?)
    ORDER BY
      message.id DESC LIMIT 10
  ) AS last_ten_messages
  ORDER BY
    id ASC;");
$db_statement->bind_param(
  'iiii',
  $sender_user_id,
  $recipient_user_id,
  $sender_user_id,
  $recipient_user_id
);
$db_statement->execute();
$db_result = $db_statement->get_result();
$db_messages = $db_result->fetch_all( MYSQLI_ASSOC );
$db_statement->close();

// Update 'read/unread' status
$db_statement = $db_connection->prepare("
  UPDATE
    message
  SET
    read_datetime_utc = UTC_TIMESTAMP()
  WHERE
    read_datetime_utc IS NULL AND
    recipient_user_id = ? AND
    sender_user_id = ?;
");
$db_statement->bind_param( 'ii', $sender_user_id, $recipient_user_id );
$db_statement->execute();
$db_statement->close();
$db_connection->close();

?> 
  <section id="contacts">
<?php foreach ( $db_contacts as $db_contact ) { ?>
<?php if ( $db_contact[ 'id' ] == $recipient_user_id ) { ?>
    <article>
      <strong><?= $db_contact[ 'email' ] ?></strong>
    </article>
<?php } else { ?>
    <article>
      <a href="?r=<?= $db_contact[ 'id' ] ?>"><?= $db_contact[ 'email' ] ?></a>
<?php if ( $db_contact[ 'unread_count' ] != 0 ) { ?>
      <small><?= $db_contact[ 'unread_count' ] ?></small>
<?php } ?>
    </article>
<?php } ?>
<?php } ?>
  </section>
  <section id="messages">
<?php if ( empty( $recipient_user_id ) ) { ?>
    Select a user from the left hand side
<?php } else { ?>
<?php foreach ( $db_messages as $db_message ) { ?>
<?php if ( $db_message[ 'sender_user_id' ] == $recipient_user_id ) { ?>
    <article class="received">
      <h1><?= $db_message[ 'sender' ] ?></h1>
      <p><?=  $db_message[ 'content' ] ?></p>
    </article>
<?php } else { ?>
    <article>
      <h1><?= $db_message[ 'sender' ] ?></h1>
      <p><?=  $db_message[ 'content' ] ?></p>
<?php if ( isset( $db_message[ 'read_datetime_utc' ] ) ) { ?>
      <small>seen</small>
<?php } else { ?>
      <small>unseen</small>
<?php } ?>
    </article>
<?php } ?>
<?php } ?>
<?php } ?>
  </section>