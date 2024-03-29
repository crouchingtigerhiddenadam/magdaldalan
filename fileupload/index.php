<?php

require '../config.php';

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare( "SELECT * FROM attachment;" );
$db_statement->execute();
$db_result = $db_statement->get_result();
$db_rows = $db_result->fetch_all(MYSQLI_ASSOC);
$db_statement->close();
$db_connection->close();

?>
<html>
<head>
    <title>Attachments</title>
    <link href="../site.css" rel="stylesheet">
</head>
<body>
<main>
    <h2>Attachments</h2>
    <table>
    <colgroup>
        <col style="width: 45%;">
        <col style="width: 45%;">
        <col style="width: 10%;">
    </colgroup>
    <thead>
        <tr>
            <th>Filename</td>
            <th>Store</td>
            <th>Delete</td>
        </tr>
    </thead>
    <tbody>
    <?php if ( count($db_rows) == 0 ) { ?>
        <tr>
            <td colspan="3">You have no files yet.</td>
        </tr>
    <?php } else { ?>
        <?php foreach ( $db_rows as $db_row ) { ?>
            <tr>
                <td><?= $db_row[ 'filename' ] ?></td>
                <td><a href="<?= $db_row[ 'store' ] ?>"><?= $db_row[ 'store' ] ?></a></td>
                <td>
                    <a href="delete.php?id=<?= $db_row[ 'id' ] ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
    <?php } ?>
    </tbody>
    </table>
    <a class="button" href="add.php">Add</a>
</main>
</body>
<html>