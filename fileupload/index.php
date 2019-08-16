<?php

require_once '../config.php';

$target_dir = 'upload/';
$target_file = $target_dir . basename( $_FILES[ 'fileToUpload' ][ 'name' ] );
$temporary_file = $_FILES["fileToUpload"]["tmp_name"];

$ext = pathinfo($target_file, PATHINFO_EXTENSION);
//echo $ext;

$ext_length = strlen($ext);
$target_path_length = strlen($target_file);
$s =  $target_path_length - $ext_length  - 1;
echo substr($target_file, 0, $s . ' sc.' . $ext);

if ( file_exists($target_file) ) {
    for ($i = 0; $i < 100; ++$i) {
        // $alternative_target_file = $target_file . ' (' . $i . ')';
        $alternative_target_file = $target_file . ' (' . $i . ')';
        if ( !file_exists( $alternative_target_file ) ) {
            //echo $alternative_target_file;
            //move_uploaded_file( $temporary_file, $alternative_target_file );
            break;
        }
    }
} 
else {
    //echo $target_file;
    //move_uploaded_file( $temporary_file, $target_file );
}

?>

<!DOCTYPE html>
<html>
<body>

    <form action="./" enctype="multipart/form-data" method="post">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

</body>
</html>