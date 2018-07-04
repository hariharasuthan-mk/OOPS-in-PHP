<?php

/*
; DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
; 
; Contributor(s): Hariharasuthan
;
; Portions Copyrighted 2018 
*/

/*
;Database config.
*/

final class Database{
    
    private $conn;
    
    public function __construct($host,$user,$pass){
        
        $dsn="mysql:host=$host;";
        try {
            $this->conn = new PDO($dsn,$user,$pass);
            //echo "Connection Success";
        }catch(PDOException $e) {
            die("Error!: ". $e->getMessage());
        }
        
    }

    public function CreateDatabase($dbName,$collation="utf8_general_ci"){
        
        $sql=<<<"db"
            CREATE DATABASE $dbName
            DEFAULT CHARACTER SET utf8
            DEFAULT COLLATE $collation;
db;
         $stmt=$this->conn->prepare($sql);
         $stmt->execute();
        if($stmt->errorCode()=="00000"){
             echo "Database Create Success";
        }
        else{
         die($stmt->errorInfo()[2]);
        }
    }


    public function SelectDatabase($dbName){
        
        $sql="use $dbName";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute();
        if($stmt->errorCode()!="00000"){
             die($stmt->errorInfo()[2]);
        }
        return "Database Selected";
        
    }

    public function CreateTable(string $table, array $fields,$primary_key=""){
        
        $sql = "CREATE TABLE `$table`(";
        foreach ($fields as $definition) {
          $sql.= $definition['column_name'].' '.$definition['column_type'].', ';
        }
        $sql.= 'date_added'." DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,";
        $sql.= "PRIMARY KEY ($primary_key));";
        $stmt=$this->conn->prepare($sql);
             $stmt->execute();
            if($stmt->errorCode()!="00000"){
                 return ($stmt->errorInfo()[2]); //die ($stmt->errorInfo()[2]);
            }
            return "table $table Created ...";
            
    }

    public function Insert($table,array $data){
        
        $sql="INSERT INTO $table ( ";
        foreach($data as $col=>$val){
            $sql.=" $col,";
            }
        $sql= substr($sql,0,-1);
        $sql.=") VALUES ( ";
        foreach($data as $col=>$val){
            $sql.=" :$col,";
        }
        $sql= substr($sql,0,-1);
        $sql.=" )";

            $stmt = $this->conn->prepare($sql);
            foreach($data as $column=>&$value){
                $stmt->bindParam($column, $value);
            }
            $stmt->execute();
        if($stmt->rowCount()>0){
            //echo "Data Insert Success";
            return true;
        }
        else{
            die("Data Insert Fail!");
        }

    }
    
    
    //$config = Config::getConfig("db");
        
    

}


/*
$db = new Database("localhost","mysql_user1","mysql_user1");
 * Database
echo $db->selectDatabase("test");


$column_data  = array(
  array(
    'column_name'  => 'id',
    'column_type'  => 'INT UNSIGNED NOT NULL AUTO_INCREMENT'
  ),
  array(
    'column_name'  => 'words',
    'column_type'  => 'VARCHAR(20)'
  ),

);

//echo $db->createTable("oops3",$column_data,"id");

$data=array(
    "words"=>"Banana",
);
$db->Insert("oops3",$data);
*/


?>
