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
        $query = 'INSERT INTO  Goods (title) VALUES ("'.$goodsTitle.'") ';
        $this->connection->query($query);
    }


    function deleteGoods($goodsID){
        $query = 'DELETE FROM Goods WHERE id='.$goodsID;
        $this->connection->query($query);
    }


    function updateGoods($goodsID,$newTitle){
        $query = 'UPDATE Goods SET title="'.$newTitle.'" WHERE id='.$goodsID;
        $this->connection->query($query);
    }

    function echoGoodsOptions() {

        $result = $this->connection->query('SELECT * FROM Goods');
        while ($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
        }
    }
}