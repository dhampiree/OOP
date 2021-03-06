<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 05.06.16
 * Time: 20:09
 */


namespace core\models;


class CategoriesModel
{

    private $connection;

    function __construct(){
        $this->connection = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('Connect Error ('.$this->connection->connect_errno.') '.$this->connection->connect_error);
        }
        $this->connection->set_charset('utf8');
    }


    function disconetcFromDB(){
        $this->connection->close();
    }

    function newCategory($category_name, $parent_cat_id=0) {

        if (!is_integer($parent_cat_id)) {
            error_log('ERROR! Wrong parent parameter.');
            return false;
        }

        $query = 'INSERT INTO Categories (title, parent_cat_id)
                  VALUES ("' . $category_name . '", ' . $parent_cat_id . ')';
        $this->connection->query($query);
    }

    function removeCategory($category_id) {
        if (!is_integer($category_id)) {
            error_log('ERROR! Wrong category parameter.');
            return false;
        }


        $query = '
		DELETE FROM Categories
		WHERE id = '.$category_id;

        $this->connection->query($query);
    }

    function updateCategory($id, $name=false, $parent=false) {


        $namestring = ($name === false) ? '' : 'title = "'.$name.'"';
        $parentstring = ($parent === false) ? '' : 'parent_cat_id = "'.$parent.'"';
        $separator = ($namestring == '' or $parentstring == '') ? '' : ', ';

        $query = '
			UPDATE Categories
			SET '.$namestring.$separator.$parentstring.'
			WHERE id = '.$id.'
		';
        error_log($query);
        $this->connection->query($query);
    }

    function echoCategoriesOptions() {

        $result = $this->connection->query('SELECT * FROM Categories');
        while ($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
        }
    }


}