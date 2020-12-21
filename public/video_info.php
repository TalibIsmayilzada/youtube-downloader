<?php

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (!$url) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();
$links = $youtube->getDownloadLinks($url);
$title = $youtube->parseTitle($url);
$thumbnail = $youtube->getImage($url);


$error = $youtube->getLastError();

header('Content-Type: application/json');
echo json_encode([
    'title' => $title,
    'links' => $links,
    'thumbnails' => $thumbnail,
    'error' => $error
], JSON_PRETTY_PRINT);
