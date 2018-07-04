#!/usr/bin/php
<?php
/*
; DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
; 
; Contributor(s): Hariharasuthan
;
; Portions Copyrighted 2018 
*/

/*
;Application Config
*/

/**
 * Main application class.
*/
final class Index {
    
    const DEFAULT_PAGE = 'home';
    const PAGE_DIR = '../page/';
    const LAYOUT_DIR = '../layout/';

    /**
     * System config.
    */
    public function init() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');        
        //set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));        
    }
    
    /**
     * Class loader.
    */
    
    public function loadClass($name) {
        $classes = array(               
            'TxtProcess' => '/Txt/Process.php', 
            'Database' => '/Database/Database.php', 
            
            
            
        );
        if (!array_key_exists($name, $classes)) {
            die('Class "' . $name . '" not found.');
        }
        require_once __DIR__.$classes[$name];
    }
    
    /**
     * Run the application!
    */
    public function run($argv) {
        
        
        $executionStartTime = microtime(true);
        
        $txt_process = new TxtProcess();
        $txt_process->init();
        
        echo 'Parsing Txt File'.PHP_EOL;
        
        $arg_txt_file[1] = $argv ;       
        
        if(count($argv)=="2"){
            $arguments = array_shift($argv);//print_r($argv);
            $txt_arg1 = $argv[0];
            $txt_arg1 = __DIR__ ."/".$txt_arg1;//var_dump($txt_arg1);            
            $txt_process->ReadTxtFile($txt_arg1);
            $executionEndTime = microtime(true);
            print "Executed in ".number_format(($executionEndTime - $executionStartTime),2)." Seconds".PHP_EOL;
            print "Please Check the table for parsed words (Use select query)".PHP_EOL;            
        }
        else{
            die(PHP_EOL."This script will not work more than one aruguments".PHP_EOL);           
        }       
        
        
    }
    
    
   

}

$index = new Index();
$index->init();
// run application!
$index->run($argv);

?>
