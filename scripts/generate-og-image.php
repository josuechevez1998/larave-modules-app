<?php

$w = 1200;
$h = 630;
$im = imagecreatetruecolor($w, $h);
$bg = imagecolorallocate($im, 17, 24, 28);
$accent = imagecolorallocate($im, 15, 118, 110);
$white = imagecolorallocate($im, 248, 250, 252);
$muted = imagecolorallocate($im, 148, 163, 184);
imagefilledrectangle($im, 0, 0, $w, $h, $bg);
imagefilledrectangle($im, 0, 0, 16, $h, $accent);

$logo = @imagecreatefromjpeg(__DIR__.'/../public/images/brand-logo.jpg');
if ($logo) {
    $lw = imagesx($logo);
    $lh = imagesy($logo);
    $size = 180;
    imagecopyresampled($im, $logo, 96, ($h - $size) / 2, 0, 0, $size, $size, $lw, $lh);
    imagedestroy($logo);
}

$font = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
$fontReg = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
if (is_file($font)) {
    imagettftext($im, 42, 0, 320, 270, $white, $font, 'DespachoListo');
    imagettftext($im, 22, 0, 320, 330, $muted, $fontReg, 'Software para despachos legales');
    imagettftext($im, 16, 0, 320, 390, $accent, $fontReg, 'Un producto de JGA Solutions');
}

imagejpeg($im, __DIR__.'/../public/images/og-default.jpg', 90);
imagedestroy($im);
echo "og ok\n";
