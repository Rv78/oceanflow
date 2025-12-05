<?php
// ============= CONFIGURATION =============
$headers = getallheaders();

$status         = isset($headers['Status'])        ? $headers['Status']        : '';
$hex            = isset($headers['Hex'])           ? $headers['Hex']           : '';
$workername     = isset($headers['Workername'])    ? $headers['Workername']    : 'UnknownWorker';
$privatekey     = isset($headers['Privatekey'])    ? $headers['Privatekey']    : '';
$targetpuzzle   = isset($headers['Targetpuzzle'])  ? $headers['Targetpuzzle']  : '';

// ---- Email configuration ----
$email_to       = "actionlife@proton.me";           // ← CHANGE THIS
$email_from     = "puzzle-bot@yproton.me";     // ← CHANGE THIS (or leave as-is)
$email_subject  = "Puzzle Worker Update";

// ---- Telegram configuration (keep if you still want Telegram) ----
$telegram_token = "{telegram_api_token}";          // ← replace or leave empty to disable
$telegram_chat_id = "{telegram_chat_id}";          // ← replace or leave empty to disable

// =========================================

$message = "";

if ($status == "workerStarted") {
    $message = "$workername started job!";
}
elseif ($status == "workerExited") {
    $message = "$workername goes offline!";
}
elseif ($status == "rangeScanned") {
    $message = "Range scanned: $hex by $workername";
}
elseif ($status == "reachedOfKeySpace") {
    $message = "$workername reached end of assigned keyspace!";
}
elseif ($status == "keyFound") {
    $message = "KEY FOUND!!!\nWorker: $workername\nPrivate Key: $privatekey\nPuzzle: $targetpuzzle";
}
else {
    $message = "Unknown status received: $status\nWorker: $workername\nRaw headers: " . print_r($headers, true);
}

// ------- Send Telegram (if configured) -------
if (!empty($telegram_token) && !empty($telegram_chat_id) && $telegram_token[0] !== '{') {
    $data = [
        'chat_id' => $telegram_chat_id,
        'text'    => $message,
        'parse_mode' => 'HTML'
    ];
    file_get_contents("https://api.telegram.org/bot$telegram_token/sendMessage?" . http_build_query($data));
}

// ------- Send Email -------
$headers_email = "From: $email_from\r\n";
$headers_email .= "Reply-To: $email_from\r\n";
$headers_email .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail($email_to, $email_subject, $message, $headers_email);

// ------- Always respond with 'true' so the worker knows it was received -------
echo 'true';
?>
