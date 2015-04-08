<?php
require_once 'config.php';

$result = mysqli_query($DB, "SELECT * from clients");

$status = [];

while ($row = mysqli_fetch_assoc($result)) {
    $status[] = [
        'ip' => $row['ip'],
        'name' => $row['name'],
        'allowed' => (bool) $row['allow_vote']
    ];
}

echo json_encode($status);
