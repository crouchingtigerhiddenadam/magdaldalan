<?php

    require_once '../config.php';

    $_SESSION[ 'user_id' ] = 2

    $user_id = $_SESSION[ 'user_id' ];
    
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        $content = htmlentities( $_POST[ 'content' ] );

        $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
        $db_statement = $db_connection->prepare( 'INSERT INTO message(user_id, content, creation_datetime_utc) VALUES(?, ?, UTC_DATE());' );
        $db_statement->bind_param( 'is', $user_id, $content );
        $db_statement->execute();
        $db_statement->close();
        $db_connection->close();
    }

?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <section id="output">
    </section>
    <form method="post">
        <textarea name="content"></textarea>
        <button type="submit">Send</button>
    </form>
    <script>

        let output = document.getElementById( 'output' );

        setInterval( function() {
            let xhr = new XMLHttpRequest()
            xhr.onreadystatechange = function() {
                if ( this.readyState == 4 && this.status == 200 ) output.innerHTML = this.responseText
            }
            xhr.open( 'get', 'history.php', true)
            xhr.send()
        }, 5000 )

    </script>
</body>
</html>