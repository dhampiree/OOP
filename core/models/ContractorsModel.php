<?php

namespace core\models;


class ContractorsModel
{
    private $connection;

    function __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function addContractor($director, $contactPerson, $contactPhone, $comment){

        $query = 'INSERT INTO Contractors(director, contact_person, contact_phone, comment)
                  VALUES ("'.$director.'","'.$contactPerson.'","'.$contactPhone.'","'.$comment.'")';

        $this->connection->query($query);

    }


    function selectContractorByID($id){
        if (!is_int($id))
            return array();

        $query = 'SELECT * FROM Contractors WHERE id='.$id.' LIMIT 1';
        $result = $this->connection->query($query);
        $contactor = array();

        if($result !== false){
            $result->data_seek(0);

            while ($row = $result->fetch_assoc()) {
                $contactor = $row;
            }

            $result->close();
            return $contactor;
        }

    }


    function selectContractorWithParams($id, $director, $contactPerson, $contactPhone, $comment, $count = 20, $offsetCount = 0){

        $query = "SELECT * FROM Contractors WHERE 1 AND ";

        if(is_int($id)){
            $query .= "id = $id AND ";
        }

        if($director){
            $query .= "director = '$director' AND ";
        }

        if($contactPerson){
            $query .= "contact_person = '$contactPerson' AND ";
        }

        if($contactPhone){
            $query .= "contact_phone = '$contactPhone' AND ";
        }

        if($comment){
            $query .= "comment = '$comment' AND ";
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


    function contractorList($count = 20, $offsetCount = 0){
        if(!is_int($count))
            $count = 20;
        if(!is_int($offsetCount))
            $count = 0;

        $query = "SELECT * FROM Contractors WHERE 1 LIMIT $offsetCount, $count; ";

        $result = $this->connection->query($query);

        if($result !== false){
            $contactorsList = array();
            $result->data_seek(0);

            while ($row = $result->fetch_assoc()) {
                array_push($contactorsList, $row);
            }

            $result->close();
            return $contactorsList;
        }

        return array();
    }


    function updateContractorByID($id, $director, $contactPerson, $contactPhone, $comment){

        $query = "UPDATE Contractors
                  SET
                  director='$director',
                  contact_person='$contactPerson',
                  contact_phone='$contactPhone',
                  comment='$comment'
                  WHERE id = $id;";

        return $this->connection->query($query);

    }


    function deleteContractorByID($id){
        if(!is_int($id))
            return false;

        $query = "DELETE FROM Contractors WHERE id = $id";
        return $this->connection->query($query);
    }
}