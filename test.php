<?php
header("Transfer-encoding: chunked");
header("Trailer: Server-Timing");
flush();

echo dechex(strlen($myChunk)) . "\r\n";
echo $myChunk;
echo "\r\n";
flush();

echo "0\r\n";
flush();

echo "Server-Timing: missedCache\r\n";
flush();
?>
