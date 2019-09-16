<?php

if ( !isset( $_SESSION[ 'user_id' ] ) ) {
  header( 'Location: ../index.php' );
  die();
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Magdaldalan</title>
  <link href="index.css" rel="stylesheet">
</head>
<body>
<?php include '_received.php' ?>
  <section id="send">
<?php include '_send.php' ?>
  </section>
  <script src="index.js"></script>
<?php if ( !empty( $recipient_user_id ) ) { ?>
<?php } ?>
</body>
</html>