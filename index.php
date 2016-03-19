<html>
<head>
<title>Memegen!</title>
<script src="jquery.js"></script> 

<script>
  function handleLoad(e) {
    console.log('Loaded import: ' + e.target.href);
  }
  function handleError(e) {
    console.log('Error loading import: ' + e.target.href);
  }
</script>

<link rel="import" href="header.html"
      onload="handleLoad(event)" onerror="handleError(event)">
</head>
<body>
<center><h1> Memegen </h1><br></center>

<script>
    var link = document.querySelector('link[rel="import"]');
    var content = link.import;

    // Grab DOM from warning.html's document.
    var el = content.querySelector('.warning');

    document.body.appendChild(el.cloneNode(true));
  </script>


<?php
include 'util.php';
//echo renderImage('templates/clinton_zero.jpg');
?>
</body>
</html>
