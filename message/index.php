<?php

    require_once '../config.php';

    $user_id = 2;
    
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
        <?php include 'history.php' ?>
    </section>
    <form id="form" method="post">
        <textarea id="content" name="content"></textarea>
        <button type="submit">Send</button>
    </form>
    <script>

        let form = document.getElementById( 'form' )
        let output = document.getElementById( 'output' )
        let content = document.getElementById( 'content' )

        function update() {
            let xhr = new XMLHttpRequest()
            xhr.onreadystatechange = function() {
                if ( this.readyState == 4 && this.status == 200 ) output.innerHTML = this.responseText
            }
            xhr.open( 'GET', 'history.php', true )
            xhr.send()
        }

        form.onsubmit = function ( e ) {
            e.preventDefault()
            let xhr = new XMLHttpRequest()
            xhr.onreadystatechange = function() {
                if ( this.readyState == 4 && this.status == 200 ) update()
            }
            xhr.open( 'POST', 'index.php', true )
            xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' )
            xhr.send( 'content=' + encodeURI( content.value ) )
        }

        setInterval( update, 5000 )

    </script>
</body>
</html>