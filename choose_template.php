<html>
<head>
<title>Make a Meme</title>
<link rel="import" id="header" href="header.html">
</head>
<body>
<script>
    var getImport = document.getElementById('header');
    var content = document.querySelector('link[id="header"]').import;    
    document.body.appendChild(content.querySelector('.header').cloneNode(true));
</script>
<?php
include 'util.php';
include_once 'ChromePhp.php';
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

/**
 * Creates a meme with the given meme template and the
 * given caption. Caption is always put at the bottom of
 * the image.
 */
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

/**
 * Displays all the available meme thumbnails.
 */
/**
 * Creates thumbnails for all the available meme templates.
 */
function createTemplateThumbnails() {
  $fileList = glob('templates/*.jpg');
  foreach ($fileList as $fileName) {
    if (filesize($fileName) > 250 * 1024) {
      // Ignore any file greater than 250KB.
      error_log('File ' . $fileName . ' is larger than the max acceptible size. Ignored...');
      continue;
    }
    $image = new Imagick($fileName);
    $image->scaleImage(100, 100, true /* best fit */);
    $count = 1;
    $outputFileName = str_replace('templates/', 'thumb/thumb_', $fileName, $count);
    $image->writeImage($outputFileName);
  }
}

$choice = $choice || '__UNDEF__';

if ($choice == '__UNDEF__') {
  createTemplateThumbnails();
  displayThumbnails();
}

function displayThumbnails() {
  $fileList = glob('thumb/thumb_*.jpg');
  $imageTable = '<div class="thumbnails"><table width="100%">';
  $i = 0;
  foreach ($fileList as $fileName) {
    if (filesize($fileName) < 20 * 1024) {
      // Ignore any file greater than 20KB.
      if ($i == 0) {
        $imageTable .= '<tr>';
      }
      $imageTable .= '<td align="center">' . renderImage($fileName) . '</td>';
      if ($i == 3) {
        $imageTable .= '</tr>';
      }
      $i = ($i + 1) % 4;
    }
  }
  if ($i != 3) {
    $imageTable .= '</tr>';
  }
  $imageTable .=  '</table></div>';
  echo $imageTable;
}

?>
</body>
</html>
