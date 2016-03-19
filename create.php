<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
header('Content-type: image/jpeg');

/**
 * Returns exact location for the text. Currently returns (x. y, fontsize). In future
 * it should be extended to include location as well as text wrapping.
 * This function implictily assumes a monospaced font.
 */
function getTextLocation($imageHeight, $imageWidth, $text) {
  $fontsize = $imageHeight / 4;
  // This is just a heuristic.
  $locationY = $imageHeight - $fontsize * 0.5;
  $textLen = strlen($text);
  error_log(sprintf("TEXT %d\n", $textLen));
  $textWidth = $textLen * $fontsize * 0.7;
  $locationX = ($imageWidth - $textWidth) *0.5;
  return [$locationX, $locationY, $fontsize];
}

function createMeme($templateFileName, $text) {
  $image = new Imagick($templateFileName);
  $draw = new ImagickDraw();
  $color = new ImagickPixel('#ffffff');

  $height = $image->getImageHeight();
  $width = $image->getImageWidth();
  list($locationX, $locationY, $fontsize) = getTextLocation($height, $width, $text);

  /* Font properties */
  $draw->setFont('fonts/FreeMonoBold.ttf');
  $draw->setFontSize($fontsize);
  $draw->setFillColor($color);
  $draw->setStrokeAntialias(true);
  $draw->setTextAntialias(true);

  /* Create text */
  $draw->annotation($locationX, $locationY, $text);

  error_log(sprintf(" %d  %d  %d   %d %d\n", $height, $width, $locationX, $locationY, $fontsize));
  /* Create image */
  $image->drawImage($draw);
  return $image;
}

$meme = createMeme("templates/clinton_zero.jpg", "Zero");
echo $meme;


?>
