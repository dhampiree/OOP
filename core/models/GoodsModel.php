<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 07.06.16
 * Time: 10:58
 */

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

}