<?php

namespace core\models;


class AttributesModel
{
    private $connection;

    function __construct(){


        ini_set('display_errors',1);
        error_reporting(E_ALL);

        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function test(){
        echo "test";
    }
//*
    function newAttribute($attribute_name, $cat_id) {

        if (!is_integer($cat_id)) {
            error_log('ERROR! Wrong parent parameter.');
            return false;
        }

        $query = 'INSERT INTO Attribute (title, categ_id) VALUES ('.$attribute_name.','.$cat_id.')';

        $this->connection->query($query);
    }


    function removeAttribute($attribute_id) {
        if (!is_integer($attribute_id)) {
            error_log('ERROR! Wrong attribute parameter.');
            return false;
        }

        $query = 'DELETE FROM Attribute	WHERE id = '.$attribute_id;

        $this->connection->query($query);
    }


    function removeAttributeByCategoryID($cat_id){
        if (!is_integer($cat_id)) {
            error_log('ERROR! Wrong category parameter.');
            return false;
        }

        $query = 'DELETE FROM Attribute WHERE categ_id = '.$cat_id;

        $this->connection->query($query);
    }

    function updateAttribute($id, $name=false, $category_id=false) {

        $namestring   = ($name   === false) ? '' : 'title = "'.$name.'"';
        $parentstring = (!is_numeric($category_id) & $category_id === false) ? '' : 'categ_id = "'.$category_id.'"';
        $separator    = ($namestring == '' or $parentstring == '') ? '' : ', ';

        $query = '
			UPDATE Categories
			SET '.$namestring.$separator.$parentstring.'
			WHERE id = '.$id;

        error_log($query);

        $this->connection->query($query);
    }

    function attributeIDByTitle($name){
        $query = 'SELECT title FROM Attribute';

        if($result = $this->connection->query($query)){
            $result->data_seek(0);

            while ($row = $result->fetch_assoc()) {
                if(strcasecmp ( $row['title'], $name) == 0 ){
                    return intval($row['id']);
                }
            }

            $result->close();
        }

    }

    function categoriesList() {
        $list = array();

        $result = $this->connection->query('SELECT * FROM Categories');
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                array_push($list, $row);
               // echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
            }
        }
        return $list;
    }

    function attributesList() {

        $list = array();
        $result = $this->connection->query('SELECT * FROM Attribute');
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                array_push($list, $row);
                //echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
            }
        }
        return $list;
    }

    function echoAttributesList() {

        $query = '
			SELECT
				Attribute.id AS id,
				Attribute.title AS attr,
				Categories.title AS cat
			FROM Attribute
			LEFT JOIN Categories
			ON Attribute.categ_id = Categories.id';

        $result = $this->connection->query($query);
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="node">#'.$row['id'].' '.$row['attr'].' ('.$row['cat'].')</div>';
            }
        }
    }


    function echoOptionsWithList($listOfOptions){
        foreach($listOfOptions as $option){
            echo '<option value="'.$option['id'].'">('.$option['id'].') '.$option['title'].'</option>';
        }
    }

//*/
}