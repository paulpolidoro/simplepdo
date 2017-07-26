<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 25/07/2017
 * Time: 17:36
 */

class Database
{
    private $db;

    public function __construct($host, $database, $user, $password, $exeption = true)
    {
        if($exeption){
            $options = array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        else{
            $options = null;
        }

        $this->db = new PDO("mysql:host=$host;dbname=$database", $user, $password, $options);
    }

    /**
     * Simple PDO INSERT method
     *
     * @param string $table
     * @param array $columns
     * @param array $values
     * @return bool|Exception|PDOException
     */
    public function insert($table, $columns, $values)
    {
        $columns = implode(', ', $columns);
        $total_values = count($values);

        $array = [];
        for($i=0; $i<$total_values; $i++){
            array_push($array, "?");
        }
        $value = implode(', ', $array);
        $query = "INSERT INTO $table($columns) VALUES($value)";
        
        try {
            $sql = $this->db->prepare($query);
            for($i=1; $i<=$total_values; $i++){
                $sql->bindParam($i, $values[$i-1]);
            }

            $sql->execute();

            return "success";
        }
        catch (PDOException $exception){
            return $exception;
        }
    }

    /**
     * Simple PDO SELECT method
     * @param string $table
     * @param array $columns
     * @param null|string $options
     * @return array
     */
    public function selectAll($table, $columns = ['*'], $options = null)
    {
        $columns = implode(', ', $columns);

        $query = "SELECT $columns FROM $table $options";
        $sql = $this->db->prepare($query);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Simple PDO DELETE method
     *
     * @param string $table
     * @param string $column
     * @param string $value
     * @param int $limit
     * @return bool
     */
    public function delete($table, $column, $value, $limit = 1)
    {
        $query = "DELETE FROM $table WHERE $column = $value LIMIT $limit";
        try {
            $sql = $this->db->prepare($query);
            $sql->execute();

            return true;
        }
        catch (PDOException $exception){
            return false;
        }
    }

    /**
     * Simple PDO UPDATE method
     *
     * @param string $table
     * @param array $columns
     * @param array $values
     * @param null $options
     * @return Exception|PDOException|string
     */
    public function update($table, $columns, $values, $options = null)
    {
        $array = [];
        $i = 0;
        foreach ($columns as $column){
            array_push($array, "$column = $values[$i]");
            $i++;
        }

        $str = implode(', ', $array);
        $query = "UPDATE $table SET $str $options";

        try{
            $sql = $this->db->prepare($sql);
            $sql->execute();

            return "success";
        }catch (PDOException $exception){
            return $exception;
        }
    }
}

