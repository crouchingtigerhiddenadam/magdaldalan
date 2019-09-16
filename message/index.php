<?php

if ( !isset( $_SESSION ) ) {
  session_start();
}

if ( !isset( $_SESSION[ 'user_id' ] ) ) {
  header( 'Location: ../login/' );
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
</body>
</html>