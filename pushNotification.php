<?php

// Provide the Host Information.
//'gateway.sandbox.push.apple.com' for development
// 'gateway.push.apple.com' for product		
$tHost = 'gateway.sandbox.push.apple.com';
$tPort = 2195;


// Provide the Certificate and Key Data.  
// Follow to generate key: https://stackoverflow.com/questions/21250510/generate-pem-file-used-to-setup-apple-push-notification
	
$tCert = 'pushcert.pem';

// if you set a password on your key otherwise set it nil
$tPassphrase = 'ShawnSunDev';


// Replace this token with the token of the iOS device that is to receive the notification
$tToken = '36FEF2585ADD6E749148081DCBA7E040819C010C149C9970F87341E1A8E048B2';


// The message that is to appear on the dialog.
$tAlert = 'Hello World!';

// The Badge Number for the Application Icon (integer >=0). 
$tBadge = 1;

// Audible Notification Option.
$tSound = 'default';


// You also could set it as a array that includes all info you want to push to app 
$tPayload = 'Finally I reached here';

// Create the message content that is to be sent to the device.
$tBody['aps'] = array (
'alert' => $tAlert,
'badge' => $tBadge,
'sound' => $tSound,
);
$tBody ['payload'] = $tPayload;
// Encode the body to JSON.
$tBody = json_encode ($tBody);

// Create the Socket Stream.
$tContext = stream_context_create ();
stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);
// Remove this line if you would like to enter the Private Key Passphrase manually.
stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);
// Open the Connection to the APNS Server.
$tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
// Check if we were able to open a socket.
if (!$tSocket)
exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
// Build the Binary Notification.
$tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) . pack ('n', strlen ($tBody)) . $tBody;
// Send the Notification to the Server.
$tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
if ($tResult)
echo 'Delivered Message to APNS' . PHP_EOL;
else
echo 'Could not Deliver Message to APNS' . PHP_EOL;
// Close the Connection to the Server.
fclose ($tSocket);
?>
