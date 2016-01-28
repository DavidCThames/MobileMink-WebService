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
    
    
    
    
    
    
    /*****JFB mods to use the indexed timemaps here****/
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
    
    /**jfb debug**/
    print("Justin has the following URI-Ts for $urlToRead\n<br><br>");
    print_r($tmArray);
    //exit();
    /**end jfb debug**/


    /*****end JFB mods****/

    
    
    
    
    
    /** JFB commenting this stuff out for a quick hack of the new stuff **/
//    $output_tm = null;
//    $n = 0;
//    do {
//        curl_setopt($ch, CURLOPT_URL, $url); // set url 
//        // $output contains the output string 
//        $data = curl_exec($ch); 
//        if($output_tm == null)
//            $output_tm = new TimeMap($data, $urlToRead);
//        else
//            $output_tm->combine($data, $url);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    $n++;
//    $url = $output_tm->nextMap();  
//        echo $output_tm->nextMap();
//    }while($output_tm->nextMap() != false);

    /** End JFB commenting  **/

    if($n == null)
        $TMn = 0;
    else {
        if($n < count($tmArray))
            $TMn = count($tmArray) - $n;
        else
            $TMn = 0;
    }
    
    /** new TM gathering from JFB **/
    $output_tm = null;
    for($i = $TMn; $i < count($tmArray); $i++)
    {
        echo "\n" . $tmArray[$i] . "\n";
    	curl_setopt($ch, CURLOPT_URL, $tmArray[$i]); // set url 
	$data = curl_exec($ch); 


	if($output_tm == null)
            $output_tm = new TimeMap($data, $tmArray[$i]);
        else
            $output_tm->combine($data, $tmArray[$i]);

	$output_tm->combine($data, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);

    }



    /** end TM gathering **/
    
    //$output_tm->printText();
    
    curl_close($ch);  
    
    file_put_contents('TMInversionService.log', $output_tm->log(), FILE_APPEND);
}
?>
