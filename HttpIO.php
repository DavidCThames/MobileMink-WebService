<?php
function getMementoHTML($urlToRead) {
    ini_set('max_execution_time', 100);
    $url = "http://mementoproxy.cs.odu.edu/aggr/timemap/link/1/" . $urlToRead;
    echo $url . ": <br>";
    
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
            $output_tm = new TimeMap($data);
        else
            $output_tm->combine($data);
    
    $n++;
    $url = $output_tm->nextMap();
    }while($output_tm->nextMap() != false && $n < 20); //stop after no more links to new maps or after 10 maps
    
    $output_tm->printText();
    
    curl_close($ch);      
}
?>