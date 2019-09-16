<?php

require_once '../config.php';

if ( !isset( $_SESSION[ 'user_id' ] ) ) {
  header( 'Location: ../index.php' );
  die();
}

$sender_user_id = $_SESSION[ 'user_id' ];
$recipient_user_id = htmlentities( $_GET[ 'r' ] );
$db_connection = new mysqli( 
  $db_server,
  $db_username,
  $db_password,
  $db_name );

// Get users and unread message count
$db_statement = $db_connection->prepare("
  SELECT
    user.id,
    user.username,
    COUNT( unread_message.id ) AS unread_count
  FROM user AS user
  LEFT JOIN unread_message AS unread ON
    user.id = unread_message.sender_user_id AND
    unread_message.recipient_user_id = ? AND
    unread_message.read_datetime_utc IS NULL
  WHERE user.id != ?
  GROUP BY user.id, user.username; ");
$db_statement->bind_param(
  'ii',
  $sender_user_id,
  $sender_user_id );
$db_statement->execute();
$db_contacts = $db_statement->get_result();
$db_statement->close();

// Get last 10 messages
$db_statement = $db_connection->prepare("
  SELECT *
  FROM (
    SELECT
      m.id AS id,
      m.sender_user_id AS sender_user_id,
      su.username AS sender,
      m.content AS content,
      m.read_datetime_utc AS read_datetime_utc
    FROM message AS m
    JOIN user AS su ON m.sender_user_id = su.id
    WHERE ( m.sender_user_id = ? AND m.recipient_user_id = ? )
      OR  ( m.recipient_user_id = ? AND m.sender_user_id = ? )
    ORDER BY m.id DESC LIMIT 10
  ) AS last_ten_messages
  ORDER BY id ASC; ");
$db_statement->bind_param(
  'iiii',
  $sender_user_id,
  $recipient_user_id,
  $sender_user_id,
  $recipient_user_id
);
$db_statement->execute();
$db_messages = $db_statement->get_result();
$db_statement->close();

// Update 'read/unread' status
$db_statement = $db_connection->prepare('
  UPDATE message
  SET read_datetime_utc = UTC_TIMESTAMP()
  WHERE
    read_datetime_utc IS NULL AND
    recipient_user_id = ? AND
    sender_user_id = ?; ');
$db_statement->bind_param( 'ii', $sender_user_id, $recipient_user_id );
$db_statement->execute();
$db_statement->close();
$db_connection->close();

?> 
  <section id="contacts">
<?php foreach ( $db_contacts as $db_contact ) { ?>
<?php if ( $db_contact[ 'id' ] == $recipient_user_id ) { ?>
    <article>
      <strong><?= $db_contact[ 'username' ] ?></strong>
    </article>
<?php } else { ?>
    <article>
      <a href="?r=<?= $db_contact[ 'id' ] ?>"><?= $db_contact[ 'username' ] ?></a>
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
      <h1><?= $db_message[ 'sender'  ] ?></h1>
      <p><?=  $db_message[ 'content' ] ?></p>
    </article>
<?php } else { ?>
    <article>
      <h1><?= $db_message[ 'sender'  ] ?></h1>
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