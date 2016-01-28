<?php
//http://localhost/MobileMink/index.php?url=http://www.google.com/
function getMementoHTML($urlToRead, $n) {
    ini_set('max_execution_time', 100);
    //$url = "http://mementoproxy.cs.odu.edu/aggr/timemap/link/1/" . $urlToRead;
    $url = "http://labs.mementoweb.org/timemap/link/" . $urlToRead;
    //echo $url . ": <br>";
    
    // create curl resource 
    $ch = curl_init(); 

    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    
    
    
    
    
    
    /*----------GET ALL TIMEMAPS----------*/
    curl_setopt($ch, CURLOPT_URL, $url); // set url 

    // JFB: get initial indexed list:
    $data = array();
    $data = curl_exec($ch); 
    $tempArray = explode(',', $data);
    $tmArray = array();			//our array of URI-Ts to hit

    /**
    print("the temp array\n\n");
    print_r($tempArray);
    print("\n\n");
    /**/


    //for every line in our returned representation...
    for($i = 0; $i < count($tempArray); $i++)
    {
	//find all the timemap lines
        if(strpos($tempArray[$i],"rel=\"timemap\"") !== false)
        {
            //then strip out all of the junk to get the URI-T
            $tempArr2 = explode(';', $tempArray[$i]);
            $toPush = $tempArr2[0];
            $toPush = str_replace("<", '', $toPush);
            $toPush = str_replace(">", '', $toPush);

            //and add the URI-T to our array of URI-Ts
            array_push($tmArray, trim($toPush));
        }
    }
    
    //Print all TimeMaps
//    print("Justin has the following URI-Ts for $urlToRead\n<br><br>");
//    print_r($tmArray);
//    exit();


   /*----------Aggregate TimeMaps----------*/
    
    if($n == null) //if a max number of TimeMaps is NOT given start from the begining of the array
        $TMn = 0;
    else { //else start n from the end of the array to the end of the array
        if($n < count($tmArray))
            $TMn = count($tmArray) - $n;
        else
            $TMn = 0;
    }
    
    
    
    $output_tm = null;
    for($i = $TMn; $i < count($tmArray); $i++) //for each TimeMap starting at $TMn (changes depending on # of TimeMaps asked for) to the end
    {
    	curl_setopt($ch, CURLOPT_URL, $tmArray[$i]); // set url 
	   $data = curl_exec($ch); //recieve TimeMap


        if($output_tm == null) //If this is the first TimeMap create a new TimeMap object
                $output_tm = new TimeMap($data, $tmArray[$i]);
            else //Else agregate with the original TimeMap
                $output_tm->combine($data, $tmArray[$i]);

        $output_tm->combine($data, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);

    }



    /** end TM gathering **/
    
    $output_tm->printText(); //Print Final aggregated and paginated TimeMap
    
    curl_close($ch);  
    
    file_put_contents('TMInversionService.log', $output_tm->log(), FILE_APPEND); //Add an entry to the log for this runthrough 
}
?>
