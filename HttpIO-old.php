<?php
function getMementoHTML($urlToRead) {
    ini_set('max_execution_time', 100);
    $url = "http://mementoproxy.cs.odu.edu/aggr/timemap/link/1/" . $urlToRead; //creats the URL to the TimeMap location
    
    $ch = curl_init(); // create curl resource 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //return the transfer as a string 
    
    $output_tm = null; //string for the final output TimeMap
    $n = 0; //counts the number of TimeMaps that are aggregated
    do {
        curl_setopt($ch, CURLOPT_URL, $url); // set url 
        // $output contains the output string 
        $data = curl_exec($ch); 
        if($output_tm == null)
            $output_tm = new TimeMap($data, $urlToRead); //Initialize a new TimeMap object from TimeMap.php
        else
            $output_tm->combine($data, $url); //aggregates the new TimeMap with the Last one
    curl_setopt($ch, CURLOPT_HEADER, 0); //???
    $n++;
    $url = $output_tm->nextMap();  
        echo $output_tm->nextMap(); //???
    }while($output_tm->nextMap() != false);
    
    $output_tm->printText(); //print the final TimeMap
     
    curl_close($ch);  //close the cURL resource
    
    file_put_contents('TMInversionService.log', $output_tm->log(), FILE_APPEND); //create an entry in the server's log
}
?>