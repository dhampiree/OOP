<?php
namespace core\models;


class PricesModel
{
    function  __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function newPrice($exemplarID, $type, $value){

        $query = "INSERT INTO Prices (exemplar_id, type, value) VALUES ($exemplarID,'$type',$value)";
        $this->connection->query($query);
    }


    function selectPriceWithParam($id, $exemplarID, $type, $value, $count = 20, $offsetCount = 0){
        $query = "SELECT * FROM Prices WHERE 1 AND ";

        if($id & is_int($id))
            $query .= "id = $id AND ";

        if($exemplarID & is_int($exemplarID))
            $query .= "exemplar_id = $exemplarID AND ";

        if($value & is_int($value))
            $query .= "	value = $value AND ";

        if($type)
            $query .= "	type = '$type' AND ";

        if(!is_int($count))
            $count = 20;

        if(!is_int($offsetCount))
            $offsetCount = 0;

        $query = substr($query,0,strlen($query)-4);
        $query .= " LIMIT $offsetCount, $count";

        $result = $this->connection->query($query);

        if($result !== false){
            $result->data_seek(0);
            $list = array();

            while($row = $result->fetch_assoc()){
                array_push($list, $row);
            }

            $result->close();
            return $list;
        }

        return array("ERROR");
    }


    function updatePriceByID($id, $exemplarID, $type, $value){

        $query = "UPDATE Prices SET exemplar_id=$exemplarID, type=$type, value=$value WHERE id = $id";
        return $this->connection->query($query);
    }


    function deletePriceByID($id){
        if(!is_int($id))
            return false;

        $query = "DELETE FROM Prices WHERE id = $id";
        return $this->connection->query($query);
    }

}