<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 14.06.16
 * Time: 14:19
 */

namespace core\models;


class StorageModel
{
    function __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function addToStorage($exmlplarID, $amount){
        $query = "INSERT INTO Storage (exemplar_id, amount) VALUES ($exmlplarID, $exmlplarID)";
        return $this->connection->query($query);
    }


    function selectFromStorageWithParams($id, $exemplarID, $amaount, $count = 20, $offsetCount){
        $query = "SELECT * FROM Storage WHERE 1 AND ";

        if(is_int($id)){
            $query .= "id = $id AND ";
        }

        if(is_int($exemplarID)){
            $query .= "exemplar_id = $exemplarID AND ";
        }

        if(is_int($amaount)){
            $query .= "amount = $amaount AND ";
        }


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


    function updateStorage($id, $exemplarID, $amaount){
        $query = "UPDATE Storage SET exemplar_id=$exemplarID, amount=$amaount WHERE id = $id";
        return $this->connection->query($query);
    }


    function deleteFromStorage($id){
        if(!is_int($id))
            return false;

        $query = "DELETE FROM Storage WHERE id = $id";
        return $this->connection->query($query);
    }
}