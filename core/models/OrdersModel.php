<?php

namespace core\models;


class OrdersModel
{

    private $connection;
    function __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }

    function addOrder($type, $contractorID, $date, $confirmed, $comment){
        // должна быть тут безопасности проверка
        $query = "INSERT INTO Orders (type, contractor_id, date, confirmed, comment) VALUES ($type, $contractorID, $date, $confirmed, $comment)"; 

        $this->connection->query($query);
    }

    function orderListWith($count=20, $offsetCount=0){
        if(!is_int($count))
            $count = 20;
        if(!is_int($offsetCount))
            $offsetCount = 0;

        $query = "SELECT * FROM Orders WHERE 1 LIMIT $offsetCount, $count";
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


    function orderListWithParams($id, $dateBegin, $dateEnd, $contractor_id, $confirmed, $count=20, $offsetCount=0){
        $query = "SELECT * FROM Orders WHERE 1 AND ";

        if(is_int($id)){
            $query .= "id = $id AND ";
        }



        if($dateBegin && $dateEnd){
            $dateBegin = strtotime($dateBegin);
            $dateEnd   = strtotime($dateEnd);

            $equalDate = ($dateBegin==$dateEnd);

            if($equalDate){
                $query .= "date = '".date("Y-m-d", $dateBegin)."' AND ";
            }

        } elseif (!$dateBegin && $dateEnd)
        {
            $dateEnd   = strtotime($dateEnd);
            $query .= "date <= '".date("Y-m-d", $dateEnd)."' AND ";
        } elseif ($dateBegin && !$dateEnd)
        {
            $dateBegin   = strtotime($dateBegin);
            $query .= "date => '".date("Y-m-d", $dateBegin)."' AND ";
        }


        if($contractor_id){
            $query .= "contractor_id = $contractor_id AND ";
        }

        if($confirmed){
            $query .= "confirmed = $confirmed AND ";
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


    function updateOrderByID($id, $type, $contractorID, $date, $confirmed, $comment){
        $query = "UPDATE Orders SET type=$type, contractor_id=$contractorID, date=$date, confirmed=$confirmed, comment=$comment WHERE id = $id";
        return $this->connection->query($query);
    }


    function deleteOrderByID($id){

        if(!is_int($id))
            return false;

        $query = "DELETE FROM Orders WHERE id = $id";
        return $this->connection->query($query);
    }
}