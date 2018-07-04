<?php

/*
; DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
; 
; Contributor(s): Hariharasuthan
;
; Portions Copyrighted 2018 
*/

/*
;Text Processing.
*/

final class TxtProcess{  
    
    public function loadClass($name) {
        $classes = array(
            'Database' => '/Database/Database.php',
        );
        if (!array_key_exists($name, $classes)) {
            die('Class "' . $name . '" not found.');
        }
        require_once __DIR__.$classes[$name];
    }
    
    public function init() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');        
        //set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));        
    }
    
    public function trim_all( $str , $what = NULL , $with = ' ' ) {

        if( $what === NULL ) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }
    
    public function RemoveSpecialChar($str){
                $replacestr = '';
		$str = str_replace( array( '\'', '"', ',' , ';', '<', '>','.' ),$replacestr, $str);
                
    return $str;
    }

    
    public function ReadTxtFile($arg_txt){
        //$arg_txt = "../".$arg_txt ; 
        $file_contents = file_get_contents($arg_txt, FILE_USE_INCLUDE_PATH);
        $delimiter     = " ";
        $arr_unique_words = array_unique(explode($delimiter,$file_contents));

        $db = new Database("localhost","mysql_user1","mysql_user1");// Connect database
        $db->selectDatabase("test"); // Select Database  
        // create table

        $column_data  = array(
                array(
                  'column_name'  => 'id',
                  'column_type'  => 'INT UNSIGNED NOT NULL AUTO_INCREMENT'
                ),
                array(
                  'column_name'  => 'words',
                  'column_type'  => 'VARCHAR(100)',
                ),
                array(
                  'column_name'  => 'parse_file_details',
                  'column_type'  => 'VARCHAR(100)',
                ),
            
        );
    
        $db->CreateTable("final",$column_data,"id");
        
    
    
        foreach ($arr_unique_words as $key => $value) {
          $key++;
          
          $date = new DateTime();
          $current_time =  $date->format('U');//$date->format('U = Y-m-d H:i:s');
          $value = $this->RemoveSpecialChar($value);// remove specail characters from a string $value = preg_replace("/&(amp;)?#?[a-z]+;/i", "", $value); 
          /*Enhancement for json Object need to update database crud class
          $parse_file_details->path = $arg_txt;
          $parse_file_details->type = 'Text';
          $parse_file_details->extension = '.txt';
          */
          
          $parse_file_details = "Source:".$arg_txt.",Type:txt"; //temporary solution 
          
          $data = array(
                "words"=> $value,     // Column value assignment   
              "parse_file_details"=>$parse_file_details,
          );
          
          $db->Insert("final",$data); // Insert data 
        }
    
    }
    
}



/*
$txt_process = new TxtProcess();
$txt_process->init();
$txt_process->ReadTxtFile("big.txt");
*/