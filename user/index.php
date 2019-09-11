<?php

require '../config.php';

$db_connection = new mysqli( $db_server, $db_username, $db_password, $db_name );
$db_statement = $db_connection->prepare( "SELECT * FROM user;" );
$db_statement->execute();
$db_result = $db_statement->get_result();
$db_rows = $db_result->fetch_all(MYSQLI_ASSOC);
$db_statement->close();
$db_connection->close();

?>
<html>
<head>
    <title>Users</title>
    
    <link href="../site.css" rel="stylesheet">
</head>
<body>
<main>
    <h2>Users</h2>
    <table>
    <colgroup>
        <col style="width: 40%;">
        <col style="width: 40%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
    </colgroup>
    <thead>
        <tr>
            <th>Email Address</td>
            <th>Password</td>
            <th>Edit</td>
            <th>Delete</td>
        </tr>
    </thead>
    <tbody>
    <?php if ( count($db_rows) == 0 ) { ?>
        <tr>
            <td colspan="4">You have no users yet.</td>
        </tr>
    <?php } else { ?>
        <?php foreach ( $db_rows as $db_row ) { ?>
            <tr>
                <td><?= $db_row[ 'email_address' ] ?></td>
                <td><?= $db_row[ 'password_hash' ] ?></td>
                <td>
                    <a href="edit.php?id=<?= $db_row[ 'id' ] ?>">Edit</a>
                </td>
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