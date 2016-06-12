<?php

namespace core\models;


class AttributesModel
{
    private $connection;

    function __construct(){


        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function addAttribute($attribute_name, $cat_id) {

        if (!is_integer($cat_id)) {
            error_log('ERROR! Wrong parent parameter.');
            return false;
        }

        $query = 'INSERT INTO Attribute (title, categ_id) VALUES (\''.$attribute_name.'\','.$cat_id.')';

        return $this->connection->query($query);
    }


    function deleteAttribute($attribute_id) {
        if (!is_integer($attribute_id)) {
            error_log('ERROR! Wrong attribute parameter.');
            return false;
        }

        $query = 'DELETE FROM Attribute	WHERE id = '.$attribute_id;

        $this->connection->query($query);
    }


    function deleteAttributeByCategoryID($cat_id){
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
			UPDATE Attribute
			SET '.$namestring.$separator.$parentstring.'
			WHERE id = '.$id;

        error_log($query);

        $this->connection->query($query);
    }


    function selectAattributeIDByTitle($name){
        $query = 'SELECT * FROM Attribute';

        $result = $this->connection->query($query);
        if($result !== false){
            $result->data_seek(0);

            while ($row = $result->fetch_assoc()) {
                if(strcasecmp ( $row['title'], $name) == 0 ){
                    return intval($row['id']);
                }
            }

            $result->close();
        }

    }


    function  selectAttributesWithParams($id, $categoryID, $title, $count = 20, $offsetCount = 0){

        $query = "SELECT * FROM Attribute WHERE 1 AND ";

        if(is_int($id)){
            $query .= "id = $id AND ";
        }

        if($categoryID){
            $query .= "categ_id = $categoryID AND ";
        }

        if($title){
            $query .= "title = '$title' AND ";
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