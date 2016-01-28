<?php
header('Content-Type: application/link-format');
date_default_timezone_set('America/New_York');

if (extension_loaded("curl")) //check if the cURL extension for php is avaliable
{
    include 'HttpIO.php'; //contains the code that accsesses TimeMaps and creasts TimeMap objects
    include 'TimeMap.php'; //containst the TimeMap object
    
    //test($_GET['url']);
    if($_GET['url'] == null) //check if the user entered a URL to recieve TimeMaps for
        echo "No URL given. in the url for this page use /?url=http://...";
    else {
        if($_GET['n'] == null)
            getMementoHTML($_GET['url'], null); //Create the TimeMap for the given URL (function in HttpIO.php);
        else
            getMementoHTML($_GET['url'], $_GET['n']); //Create the TimeMap for the given URL (function in HttpIO.php);
    }   
}
else
{
    echo "cURL extension is not available<br>";
}
?>