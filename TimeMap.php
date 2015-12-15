<?php

Class TimeMap { 
    public $next = null;
    public $header1 = null;
    public $footer1 = "";
    public $footer2 = "";
    public $mementos = null;
    
    //for logging
    public $mementosCount = 0; 
    public $uri; 
    public $TMURIs = array();
    
    public function __construct($data, $urlToRead) {
        $this->uri = $urlToRead;
        $this->TMURIs[0] = "http://mementoproxy.cs.odu.edu/aggr/timemap/link/1/" . $urlToRead;
        
        $this->next = null;
        $mementos = explode("\n", $data); //split mementos into an array at new lines
        $mementosLength = count($mementos) - 1;
        $this->mementosCount += $mementosLength;
        
        //extract header and footer
        $n = 0;
        $headerlines = 0;
        while(strpos($mementos[$n], '<') == false && $mementosLength > $n) {
            $this->header1[$n] = $mementos[$n] . "\n"; //get header text
            $headerlines++;
            $n++;
        }
        for($i = 0; $i < $headerlines; $i++) 
            array_shift ($mementos); //remove header from memntos array
        $mementos_temp = $mementos[$mementosLength - $n];
        
        $this->footer2 = array_pop($mementos);
        $this->footer1 = array_pop($mementos);
        
        $this->mementos = $mementos;
    }
    
    public function printText() {
        //print header and footer
        //echo "\n\nheaders:\nheader1:" . htmlspecialchars($this->header1) . "\nfooter1:" . htmlspecialchars($this->footer1) . "\nfooter2:" . htmlspecialchars($this->footer2) . "\n\n";
        
        $data = "";
        
        //cycle through the aray backwards printing the array in reverse order
        $h = 0;
        foreach($this->header1 as $header1)
            $data .= $header1; //print header
        for($i = count($this->mementos) - 1; $i >= 0; --$i) {
            if($h == 10000) {
                $h = 0;
            }
            $data .= $this->mementos[$i] . "\n";
            $h++;
        }
        
        //print footers
        $data .= $this->footer1;
        $data .= "\n";
        $data .= $this->footer2;
        $data .= "\n";
        
        echo $data;
    }
    
    public function nextMap() {
        $temp_footer = $this->footer2;
        $link = false;
        if(strpos($temp_footer,'rel="timemap"') !== false) //check if the footer containst a link to the next timemap
           $link = explode(';', explode('>', explode('<', $temp_footer)[2])[0])[0]; //split link out of the rest of the footer
        return $link;
            
    }
    public $combines = 0;
    public function combine($temp_data, $url) {
            $this->TMURIs[] = $url;
            
        //same as the constructor to split up parts
            $temp_next = null;
            $temp_mementos = explode("\n", $temp_data); //split mementos into an array at new lines
            $temp_mementosLength = count($temp_mementos) - 1;
            $this->mementosCount += $temp_mementosLength;

            //extract header and footer
            $temp_header1 = $temp_mementos[0]; //get header text
            array_shift ($temp_mementos); //remove header from memntos array
            $temp_mementos_temp = $temp_mementos[$temp_mementosLength - 1];

            $temp_footer2 = array_pop($temp_mementos);
            $temp_footer1 = array_pop($temp_mementos);
        /*$mementoheader = null;
        $mementoheader[0] = "--------TM " . $this->combines . ", " . $url . "--------"; //DEBUG
        $mementoheader[1] = "";
        $this->combines++;
        $mementosTemp = array_merge($this->mementos, $mementoheader); //DEBUG*/
        $this->mementos = array_merge($this->mementos, $temp_mementos); //combine array of mementos
        
        //get from data from old footer
            $footer_middle = explode(">", $this->footer2)[1]; //get the middle section of footer2 which contains the date
            $from = explode('"', $footer_middle)[5]; //seperate the date itself from other properties
                
        //combine from date with new footer
            $temp_footer_sections = explode(">", $temp_footer2); //split into section of footer2 to get the middle section which contains the date and the first section which contains the self url
        
        //self URL
            $temp_self_url = explode("<", $temp_footer_sections[0]);
            $temp_self_url = explode("<", $temp_footer_sections[0]);
            $temp_self_url[1] = "InversalWebServiceURL";
            $temp_sections[0] = implode("<", $temp_self_url);
        
        //Date
            $temp_footer_middle = explode('"', $temp_footer_sections[1]); //seperate the middle section which containst the date
        
            $temp_footer_middle[5] = $from; //seperate the date itself from other properties and replace it with the new date
        
            $temp_footer_sections[1] = implode('"', $temp_footer_middle); //recombine the middle section of the footer and put it into the sections array
            $this->footer2 = implode(">", $temp_footer_sections); //recombine the sections of the footer and put it into the main footer
    }
    
    public function log() {
        $output = "";
        
        $output .= "[" . date("Y-m-d H:i:s e") . "] \n";
        $output .= "URI: " . $this->uri . " \n";
        $output .= "Mementos: " . $this->mementosCount . " \n";
        $output .= "Time Map URIs: " . " \n";
        foreach ($this->TMURIs as $value)
            $output .= "   " . $value . "\n";
        $output .= "\n";
        return $output;
        
    }
}
?>