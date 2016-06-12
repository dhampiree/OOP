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


    function addContactor($director, $contactPerson, $contactPhone, $comment){

        $query = 'INSERT INTO Contractors(director, contact_person, contact_phone, comment)
                  VALUES ("'.$director.'","'.$contactPerson.'","'.$contactPhone.'","'.$comment.'")';

        $this->connection->query($query);

    }


    function selectContactorByID($id){
        if (!is_numeric($id))
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


    /**
     * @param $count - Количество возвращаемых записей. Целое число. Значение по умолчанию 20.
     * @param $offsetCount - Отступ в выборке. Целое число. Значение по умолчанию 0.
     * @return array - Возвращает ассоциативный массив. В случае ошибки массив будет пуст.
     */
    function contactorsList($count = 20, $offsetCount = 0){
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


    function updateContactorByID($id, $director, $contactPerson, $contactPhone, $comment){

        $query = "UPDATE Contractors
                  SET
                  director='$director',
                  contact_person='$contactPerson',
                  contact_phone='$contactPhone',
                  comment='$comment'
                  WHERE id = $id;";

        return $this->connection->query($query);

    }

    function deleteContactorByID($id){
        if(!is_int($id))
            return false;

        $query = "DELETE FROM Contractors WHERE id = $id";
        return $this->connection->query($query);
    }
}