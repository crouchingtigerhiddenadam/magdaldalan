<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <section id="history">
        <?php include '_history.php' ?>
    </section>
    <section id="send">
        <?php include '_send.php' ?>
    </section>
    <script>

        function send( e ) {
            e.preventDefault()
            try {
                let xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function() {
                    if ( this.readyState == 4 && this.status == 200 ) {
                        document.getElementById( 'send' ).innerHTML = this.responseText
                        update()
                    }
                }
                xhr.open( 'POST', '_send.php', true )
                xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' )
                xhr.send( 'content=' + encodeURI( document.getElementById( 'content' ).value ) )
                return false
            }
            catch( exception ) {
                console.log( exception )
            }
        }

        function update() {
            let xhr = new XMLHttpRequest()
            xhr.onreadystatechange = function() {
                if ( this.readyState == 4 && this.status == 200 ) {
                    document.getElementById( 'history' ).innerHTML = this.responseText
                }
            }
            xhr.open( 'GET', '_history.php', true )
            xhr.send()
        }

        setInterval( update, 5000 )

    </script>
</body>
</html>