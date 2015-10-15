<!DOCTYPE html>
<html>
<body>
<?php

Class TimeMap { 
    public $next = null;
    public $header1 = "";
    public $footer1 = "";
    public $footer2 = "";
    public $mementos = null;
    
    public function __construct($data) {
        $this->next = null;
        $mementos = explode("\n", $data); //split mementos into an array at new lines
        $mementosLength = count($mementos) - 1;
        
        //extract header and footer
        $this->header1 = $mementos[0]; //get header text
        array_shift ($mementos); //remove header from memntos array
        $mementos_temp = $mementos[$mementosLength - 1];
        
        $this->footer2 = array_pop($mementos);
        $this->footer1 = array_pop($mementos);
        
        $this->mementos = $mementos;
    }
    
    public function printText() {
        //print header and footer
        //echo "<br><br>headers:<br>header1:" . htmlspecialchars($this->header1) . "<br>footer1:" . htmlspecialchars($this->footer1) . "<br>footer2:" . htmlspecialchars($this->footer2) . "<br><br>";
        
        //cycle through the aray backwards printing the array in reverse order
        $h = 0;
        echo htmlspecialchars($this->header1); //print header
        echo("<br>");
        for($i = count($this->mementos) - 1; $i >= 0; --$i) {
            if($h == 10000) {
                $h = 0;
            }
            echo htmlspecialchars($this->mementos[$i]);
            echo("<br>");
            $h++;
        }
        
        //print footers
        echo htmlspecialchars($this->footer1);
        echo("<br>");
        echo htmlspecialchars($this->footer2);
        echo("<br>");
    }
    
    public function nextMap() {
        $temp_footer = $this->footer2;
        $link = false;
        if(strpos($temp_footer,'rel="timemap"') !== false) //check if the footer containst a link to the next timemap
           $link = explode(';', explode('>', explode('<', $temp_footer)[2])[0])[0]; //split link out of the rest of the footer
        return $link;
            
    }
    
    public function combine($temp_data) {
        //same as the constructor to split up parts
            $temp_next = null;
            $temp_mementos = explode("\n", $temp_data); //split mementos into an array at new lines
            $temp_mementosLength = count($temp_mementos) - 1;

            //extract header and footer
            $temp_header1 = $temp_mementos[0]; //get header text
            array_shift ($temp_mementos); //remove header from memntos array
            $temp_mementos_temp = $temp_mementos[$temp_mementosLength - 1];

            $temp_footer2 = array_pop($temp_mementos);
            $temp_footer1 = array_pop($temp_mementos);
        
        $this->mementos = array_merge($this->mementos, $temp_mementos); //combine array of mementos
        
        //get from data from old footer
            $footer_middle = explode(">", $this->footer2)[1]; //get the middle section of footer2 which contains the date
            $from = explode('"', $footer_middle)[5]; //seperate the date itself from other properties
                
        //combine from date with new footer
            $temp_footer_sections = explode(">", $temp_footer2); //split into section of footer2 to get the middle section which contains the date
            $temp_footer_middle = explode('"', $temp_footer_sections[1]); //seperate the middle section which containst the date
        
            $temp_footer_middle[5] = $from; //seperate the date itself from other properties and replace it with the new date
        
            $temp_footer_sections[1] = implode('"', $temp_footer_middle); //recombine the middle section of the footer and put it into the sections array
            $this->footer2 = implode(">", $temp_footer_sections); //recombine the sections of the footer and put it into the main footer
    }
}