<?php
// simple-webhook.php

// Get the raw data sent by the API
$data = file_get_contents('php://input');

// Save it with a timestamp
file_put_contents('webhook_' . date('Y-m-d_H-i-s') . '.txt', $data);

// Send back "OK" so the API knows it worked
echo "OK";
?>
