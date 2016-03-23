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
<script>

function submitForm(choice) {
  var form = document.getElementById('thumbChoiceForm');
  if (form == null) {
    form = document.createElement('form');
    form.id = 'thumbChoiceForm';
  }
  document.body.appendChild(form);
  form.method = 'POST';
  form.action = 'create.php';
  var inputChoice = document.getElementById('thumbChoiceInput');
  if (inputChoice == null) {
    inputChoice = document.createElement('input');
    inputChoice.id = 'thumbChoiceInput';
    inputChoice.name = 'thumbChoice';
    form.appendChild(inputChoice);
  }
  inputChoice.type = 'hidden';
  inputChoice.value = choice;
  form.submit();
}

</script>
<?php
include_once 'util.php';
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
    $outputFileName = getThumbnailNameFromFileName($fileName);
    $image->writeImage($outputFileName);
  }
}

/**
 * Displays all the available meme thumbnails.
 */
function displayThumbnails() {
  $fileList = glob('thumb/thumb_*.jpg');
  $imageTable = '<div class="thumbnails"><table width="100%">';
  $i = 0;
  foreach ($fileList as $thumbnailName) {
    if (filesize($thumbnailName) < 20 * 1024) {
      // Ignore any file greater than 20KB.
      if ($i == 0) {
        $imageTable .= '<tr>';
      }
      $imageTable .= '<td align="center">' . 
        '<img src="' . $thumbnailName . '" onClick="submitForm(\'' . $thumbnailName  . '\')"></td> ';// . $thumbnailName ')" > </td>';
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

/**
 * Displays an image and presents a form to enter meme test.
 * @param renderFile File to render. When showing a preview, this
 * will be the preview file. For a new meme, this will be the template file.
 * @param memeTemplate Name of the meme template.
 */
function displayMemeTextForm($renderFile, $memeTemplate) {
  echo '<form action="create.php" id="thumbChoiceForm" method="post">'.
    '<table width=320>' . ' <tr><td>' .
    '<p align="center">Enter the text for your meme.</p>' .
    '</td></tr>' .
    '<tr><td>' .
    '<center>' . renderImage($renderFile) . '</center>' .
    '</td></tr> <tr/><tr/>'.
    '<tr>' .
    '<td><input type="text" size=64 maxlength=128 id="memeTextInput">' .
    '</td> </tr>' .
    '<tr><td><input type="submit" name="intent" id="preview" value="Preview">' .
    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
    '<input type="submit" name="intent" id="submit" value="Submit"></td></tr>' .
    '</table>'.
    '<input type="hidden" name="thumbChoice" id="thumbChoiceInput" value="' .
    $memeTemplate . '">' .
    '</form>';
}

/**
 * Generates a meme preview and renders it. But does not commit
 * it in database, allowing user to change it.
 */
function previewMeme($memeTemplate, $memeText) {
  CreatePhp::log("Template ", $memeTemplate);
  $preview = createMeme($memeTemplate, $memeText);
  $previewFile = getPreviewFileNameFromTemplate($memeTemplate0);
  $preview->writeImage($previewFile);
  displayMemeTextForm($previewFile, $memeTemplate);
}

/**
 * Achievement unlocked! 
 * Creates a meme and commits it in the database.
 */
function submitMeme($memeTemplate, $memeText) {
  $meme = createMeme($memeTemplate, $memeText);
  $previewFile = getMemeFileNameFromTemplate($memeTemplate0);
  $meme->writeImage($previewFile);
}

if (isset($_POST['thumbChoice'])) {
  $thumbnailName = $_POST['thumbChoice'];
  $memeTemplate = getFileNameFromThumbnailName($thumbnailName);
  if (isset($_POST['intent'])) {
    if ($_POST['intent'] == 'Submit') {
      echo '<h1>SUbmit</h1>';
      submitMeme($memeTemplate, $memeText);
    } else if ($_POST['intent'] == 'Preview') {
      echo '<h1>Preview</h1>';
      previewMeme($memeTemplate, $memeText);
    }
  } else {
    displayMemeTextForm($memeTemplate);
  }
} else {
  echo '<h1>NOT SET</h1>';
  createTemplateThumbnails();
  displayThumbnails();
}
?>

<!-- HTML form responsible for setting parameters -->

</body>
</html>
