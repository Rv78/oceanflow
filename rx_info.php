<?php
$headers = getallheaders();
$status = $headers['Status'];
$hex = $headers['Hex'];
$workername = $headers['Workername'];
$privatekey = $headers['Privatekey'];
$targetpuzzle = $headers['Targetpuzzle'];

if($status == "workerStarted"){
	shareTelegram($workername." started job!");
}
else if($status == "workerExited"){
	shareTelegram($workername." goes offline!");
}
else if($status == "rangeScanned"){
	shareTelegram($hex." scanned by ".$workername);
}
else if($status == "reachedOfKeySpace"){
	shareTelegram($workername." reached of keyspace!");
}
else if($status == "keyFound"){
	shareTelegram("Congrats dude! ".$workername." found the key! Key is: ".$privatekey);
}
function shareTelegram($message){
	$apiToken = "{telegram_api_token}";
	$chatId= "{telegram_chat_id}";
	$data = [ 
	 "chat_id" => $chatId, 
	 "text" => $message
	]; 
	$response = file_get_contents("http://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) ); 
}

echo 'true';

?>
