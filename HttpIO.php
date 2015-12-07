<?php
function getMementoHTML($urlToRead) {
    ini_set('max_execution_time', 100);
    $url = "http://mementoproxy.cs.odu.edu/aggr/timemap/link/1/" . $urlToRead;
    //echo $url . ": <br>";
    
    // create curl resource 
    $ch = curl_init(); 

    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
 
    
    $output_tm = null;
    $n = 0;
    do {
        curl_setopt($ch, CURLOPT_URL, $url); // set url 
        // $output contains the output string 
        $data = curl_exec($ch); 
        if($output_tm == null)
            $output_tm = new TimeMap($data, $urlToRead);
        else
            $output_tm->combine($data, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $n++;
    $url = $output_tm->nextMap();  
        echo $output_tm->nextMap();
    }while($output_tm->nextMap() != false);
    
    $output_tm->printText();
    
    curl_close($ch);  
    
    file_put_contents('TMInversionService.log', $output_tm->log(), FILE_APPEND);
}
?>