<?php

$source = __DIR__ . '/../public/front/assets/images/justgoom-logo.png';
$sizes = [
    __DIR__ . '/../public/assets/images/favicon.png' => 64,
    __DIR__ . '/../public/front/assets/images/favicon.png' => 64,
    __DIR__ . '/../public/favicon.png' => 64,
];

if (! extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is required.\n");
    exit(1);
}

$image = imagecreatefrompng($source);
if (! $image) {
    fwrite(STDERR, "Unable to read source logo.\n");
    exit(1);
}

imagealphablending($image, false);
imagesavealpha($image, true);

$width = imagesx($image);
$height = imagesy($image);
$size = max($width, $height);
$canvas = imagecreatetruecolor($size, $size);

imagealphablending($canvas, false);
imagesavealpha($canvas, true);
$transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
imagefilledrectangle($canvas, 0, 0, $size, $size, $transparent);

$offsetX = (int) floor(($size - $width) / 2);
$offsetY = (int) floor(($size - $height) / 2);
imagecopy($canvas, $image, $offsetX, $offsetY, 0, 0, $width, $height);
imagedestroy($image);

foreach ($sizes as $path => $targetSize) {
    $favicon = imagecreatetruecolor($targetSize, $targetSize);
    imagealphablending($favicon, false);
    imagesavealpha($favicon, true);
    imagefilledrectangle($favicon, 0, 0, $targetSize, $targetSize, $transparent);
    imagecopyresampled($favicon, $canvas, 0, 0, 0, 0, $targetSize, $targetSize, $size, $size);
    imagepng($favicon, $path);
    imagedestroy($favicon);
    echo "Created {$path}\n";
}

imagedestroy($canvas);
