<?php

namespace core\models;


class CharacteristicsModel
{
    private $connection;

    function  __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function addCharacteristic($characteristicName){
        $query = "INSERT INTO Characteristics (title) VALUES ('$characteristicName')";
        return $this->connection->query($query);
    }


    function selectCharacteristicByID($id){
        $query = "SELECT * FROM Characteristics WHERE id = $id";
        $result = $this->connection->query($query);

        if($result !== false){
            $result->data_seek(0);
            $row = $result->fetch_assoc();
            return $row['title'];
        }

        return '';
    }


    function selectCharacteristicByIDInArray($idArray){

        $idsList = '(';

        foreach($idArray as $id){
            $idsList .="$id,";
        }
        $idsList = substr($idsList, 0 , strlen($idsList)-1).")";

        $query = "SELECT * FROM Characteristics WHERE id IN $idsList";
        $result = $this->connection->query($query);

        if($result !== false){
            $result->data_seek(0);
            $list = array();
            while ($row = $result->fetch_assoc()){
                array_push($list, $row['title']);
            }

            return $list;
        } else {

            return array("ERROR");
        }
    }


    function selectCharacteristicByTitle($title){
        $query = "SELECT * FROM Characteristics WHERE title = '$title'";
        $result = $this->connection->query($query);

        if($result !== false){
            $result->data_seek(0);
            $row = $result->fetch_assoc();
            return $row['id'];
        }

        return '';
    }


    function updateCharacteristicByID($id, $newTitle){
        $query = "UPDATE Characteristics SET title='$newTitle' WHERE id = $id";
        return $this->connection->query($query);
    }


    function deleteCharacteristic($id){
        if(!is_int($id))
            return false;

        $query = "DELETE FROM Characteristics WHERE id = $id";
        return $this->connection->query($query);
    }
}