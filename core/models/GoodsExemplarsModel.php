<?php

namespace core\models;


class GoodsExemplarsModel
{
    function __construct(){
        ini_set('display_errors',1);
        error_reporting(E_ALL);

        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }

        $this->connection->set_charset('utf8');
    }


    function newExemplar($g_id, $attr_string) {

        $query = '
		INSERT INTO Goods_exemplars (goods_id, attribute_value_list)
		VALUES ("'.intval($g_id).'", "'.$attr_string.'")
		';

        $this->connection->query($query);
    }


    function removeExemplar($ex_id) {
        $query = '
		DELETE FROM Goods_exemplars
		WHERE id = '.intval($ex_id);

        $this->connection->query($query);
    }


    function updateExemplar($exemplar_ID, $goods_ID = false, $attributesList = false ){

        $goodsID = intval($goods_ID) && $goods_ID!==false ? 'goods_id = '.$goods_ID : '';
        $attrList = $attributesList !== false ? 'attribute_value_list = "'.$attributesList.'"': '';
        $separator = ($goodsID == '' || $attrList == '' )? '' : ', ';

        $query = 'UPDATE Goods_exemplars
         SET '.$goodsID.$separator.$attrList.'
         WHERE id = '.$exemplar_ID;

        $this->connection->query($query);
    }



    function echoExemplarOptions() {

        $result = $this->connection->query('SELECT * FROM Goods_exemplars');
        if ($result !== false) {
            while ($row = $result->fetch_assoc())
                echo '<option value="'.$row['id'].'">Exemplar #'.$row['id'].'</option>';
        }
    }


}