<?php
header('Content-Type: application/json; charset=utf-8');
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
echo json_encode($spotify->album('1Wbnehbde06xsK8KLsJfOn'));

// other examples:
// echo json_encode($spotify->track('67nepsnrcZkowTxMWigSbb'));
// echo json_encode($spotify->artist('0RN2n6EdV90CQmfhfxqv0f'));
// echo json_encode($spotify->artistَAlbums('0RN2n6EdV90CQmfhfxqv0f'));
