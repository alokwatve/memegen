<html>
<head>
<title>Memegen!</title>
<script src="jquery.js"></script> 

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
echo renderImage('templates/clinton_zero.jpg');
?>
</body>
</html>
