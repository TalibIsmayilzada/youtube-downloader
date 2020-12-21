<?php

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (!$url) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();

$s = $youtube->parseTitle($url);
$links = $youtube->getDownloadLinks($url);
$thumbnail = $youtube->getImage($url);


$error = $youtube->getLastError();

header('Content-Type: application/json');
echo json_encode([
    'title' => $s,
    'links' => $links,
    'thumbnails' => $thumbnail,
    'error' => $error
], JSON_PRETTY_PRINT);
