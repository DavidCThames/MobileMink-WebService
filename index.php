<!DOCTYPE html>
<html>
<body>

<?php
if (extension_loaded("curl"))
{
    echo "cURL extension is loaded<br>";
    include 'HttpIO.php';
    include 'TimeMap.php';
    
    //test($_GET['url']);
    if($_GET['url'] == null){
        echo "No URL given. in the url for this page use /?url=http://...";
    }
    else {
        getMementoHTML($_GET['url']);
    }   
}
else
{
    echo "cURL extension is not available<br>";
}
?>

</body>
</html>