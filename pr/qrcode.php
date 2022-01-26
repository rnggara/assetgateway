<?php


include '../dist/phpqrcode/qrlib.php';

if (isset($_GET['text'])) {
    QRcode::png($_GET['text']);
}

?>
