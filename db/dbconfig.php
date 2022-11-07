<?php
class LabDB extends PDO{
    //Creates a PDO instance representing a connection to a database
    public $pdo; 
    public function __construct($file = 'setting.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');
        //Create string dns
        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['schema'];
       
        try{
            $pdo = parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
        }catch(PDOException $e){
            die('ERROR:'.$e->getMessage());
        }
        return $pdo;
    }

    //Insert a line
    public static function insert($pdo, $table, $data=[]){
        //Create SQL statement template 
        $sql = "INSERT IGNORE {$table} SET ";
        foreach(array_keys($data) as $field){
            $sql .= $field.'=:'.$field.',';
        }
        //Remove the comma on the right
        $sql = rtrim(trim($sql),',');
        //Prepares a statement for execution 
        $stmt = $pdo->prepare($sql);
        //Binds values to parameters
        foreach($data as $field => $value){
            $stmt->bindValue(":{$field}",$value);
        }
        //Execution
        if($stmt->execute()){
            if($stmt->rowCount()>0){
                return true;
            }  
            return false;
        }
    }

    //Delete a line
    public static function delete($pdo, $table, $where='') {
        //Create SQL statement 
        $sql = "DELETE FROM {$table} ";
        //Add the match pattern
        if(!empty($where)) {
            $sql .= 'WHERE '. $where;
        }else{
            exit('The WHERE field need to be non-null.');
        }

        //Prepares a statement for execution
        $stmt = $pdo->prepare($sql);

        //Execution
        if($stmt->execute()){
            if($stmt->rowCount()>0){
                return true;
            }
            return false;
        }
    }
    
    //Query one line
    public static function find($pdo, $table, $fields, $where){
        //Create SQL statement 
        $sql = 'SELECT ';
        if(is_array($fields)){
            foreach($fields as $field){
                $sql .= $field.',';
            }
        }else{
            $sql .=$fields;
        }
        //Remove the comma on the right
        $sql = rtrim(trim($sql),',');
        $sql .= ' FROM '.$table;
        //Add query condition
        if(!empty($where)){
            $sql .= " WHERE ".$where;
        }
        $sql .= ' LIMIT 1';
        
        //Prepares a statement for execution
        $stmt = $pdo->prepare($sql);
        //execution
        if ($stmt->execute()){
            if ($stmt->rowCount()>0){
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
        
    }

    //query several lines
    public static function select($pdo, $table, $fields, $where='', $order=''){
        //create a statement
        $sql = 'SELECT ';
        if (is_array($fields)){
            foreach($fields as $field){
                $sql .= $field.', ';
            }

        }else{
            $sql .=$fields;
        }
        //Remove the comma on the right
        $sql = rtrim(trim($sql),',');
        $sql .=' FROM '.$table;
        //add WHERE condition
        if(!empty($where)) {
            $sql .= ' WHERE '. $where;
        }
        //add order condition
        if (!empty($order)){
            $sql .=' ORDER BY '.$order;
        }
      
        //Prepares a statement for execution
        $stmt= $pdo->prepare($sql);
        //excution
        if ($stmt->execute()){
            if($stmt->rowCount()>0){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return false;
        }
        
    }
    //select all blogs of certain user
    public static function select_allblog($pdo, $userID){
        $sql = 'SELECT post_temp.id, post_temp.title, post_temp.time_update, tb_category.cgname from 
                (SELECT * from tb_post where user = '.$userID.') as post_temp
                inner join tb_category on post_temp.category = tb_category.id ORDER BY post_temp.id DESC';

        $stmt = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stmt!=false){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
    //select all blogs of all users
    public static function select_everyblog($pdo){
        $sql = 'SELECT post_temp.id, post_temp.title, post_temp.time_update, tb_category.cgname, tb_user.mailaddr from 
                tb_post as post_temp
                inner join tb_category on post_temp.category = tb_category.id 
                inner join tb_user on post_temp.user = tb_user.id
                ORDER BY post_temp.time_update DESC';

        $stmt = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stmt!=false){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
     //searcher blogs using keywords in title
     public static function select_bytitle($pdo, $key){ 
        $sql = "SELECT * from tb_post WHERE title LIKE  '%".$key."%'";
        $stmt = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stmt!=false){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }


    //Update a line
    public static function update($pdo, $table, $data=[], $where=''){

        $sql = "UPDATE {$table} SET ";
        foreach (array_keys($data) as $field) {
            $sql .= $field.'=:'. $field.', ';

        }
        //Remove the comma on the right
        $sql = rtrim(trim($sql),', ');
        // add WHERE condition
        if (!empty($where)) {
            $sql .= ' WHERE '.$where.';';
        } else {
            exit('condition WHERE is necessary');
        }

        //Prepares a statement for execution
        $stmt = $pdo->prepare($sql);
        //Binds values to parameters
        foreach ($data as $field => $value) {
            $stmt->bindValue(":{$field}", $value);
        }
        //execution
        if ($stmt->execute()){
            if($stmt->rowCount()>0){
                return true;
            }
        return false;
        }   
    } 
}

