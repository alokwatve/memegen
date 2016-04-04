<?php
include_once 'ChromePhp.php';

function renderImage($filename) {
  $contents = file_get_contents($filename);
  $base64   = base64_encode($contents); 
  return ('<img align="middle" src="data:image/jpeg;base64,' . $base64 . '" alt=image.jpg/>');
}

/**
 * Returns thumbnail file name for given template file name.
 */
function getThumbnailNameFromFileName($fileName) {
  $count = 1;
  return str_replace('templates/', 'thumb/thumb_', $fileName, $count);
}

/**
 * Returns thumbnail file name for given template file name.
 */
function getFileNameFromThumbnailName($fileName) {
  $count = 1;
  return str_replace('thumb/thumb_', 'templates/', $fileName, $count);
}

/**
 * Generate a random string of characters.
 */
function generateRandomString($length=32) {
  $chars = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
  $keys = array_rand($chars, $length);
  $result = '';
  foreach ($keys as $key) {
    $result .= $chars[$key];
  }
  return $result;
}

/**
 * Returns file extension based on its type.
 */
function getExtension($fileName) {
  $parts = explode(".", $fileName);
  if (count($parts) > 1) {
    return "." . end($parts);
  } else {
    return "jpg";
  }
}

/**
 * Generate a preview file name.
 */
function getPreviewFileNameFromTemplate($fileName) {
  return 'preview/' . generateRandomString() . getExtension($fileName);
}

/**
 * Generate a random meme file name.
 */
function getMemeFileNameFromTemplate($fileName) {
  return 'memes/' . generateRandomString() . getExtension($fileName);
}

?>
