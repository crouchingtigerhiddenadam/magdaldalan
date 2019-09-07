<?php

require_once '../config.php';

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $temporary_file = $_FILES["fileToUpload"]["tmp_name"];
    $filename = basename( $_FILES[ 'fileToUpload' ][ 'name' ] );
    $store_dir = 'upload/';
    $store = $store_dir . microtime();

    move_uploaded_file( $temporary_file, $store );

    $db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
    $db_statement = $db_connection->prepare( "INSERT INTO attachment(filename, store) VALUES(?, ?);");
    $db_statement->bind_param( 'ss', $filename, $store );
    $db_statement->execute();
    $db_statement->close();
    $db_connection->close();

    header( 'Location: index.php' );
    die();
}

?>

<!DOCTYPE html>
<html>
<body>

    <form action="add.php" enctype="multipart/form-data" method="post">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

</body>
</html>