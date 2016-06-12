<?php

namespace core\models;


class GoodsModel
{

    private $connection;

    function __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function addGoods($goodsTitle){
        $query = "INSERT INTO  Goods (title) VALUES ('".$goodsTitle."') ";
        $this->connection->query($query);
    }


    function deleteGoods($goodsID){
        $query = "DELETE FROM Goods WHERE id=".$goodsID;
        $this->connection->query($query);
    }


    function updateGoods($goodsID,$newTitle){
        $query = "UPDATE Goods SET title='".$newTitle."' WHERE id=".$goodsID;
        $this->connection->query($query);
    }

    function selectGoodsWithParams($id, $title, $count = 20, $offsetCount = 0){
        $query = "SELECT * FROM Goods WHERE 1 AND ";

        if($id & is_int($id))
            $query .= "id = $id AND ";

        if($title)
            $query .= "title = '$title' AND ";

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


    function echoGoodsOptions() {

        $result = $this->connection->query('SELECT * FROM Goods');
        while ($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
        }
    }
}