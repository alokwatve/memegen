<?php
function renderImage($filename) {
  $contents = file_get_contents($filename);
  $base64   = base64_encode($contents); 
  return ('<img src="data:image/jpeg;base64,' . $base64 . '" alt=image.jpg/>');
}
?>
