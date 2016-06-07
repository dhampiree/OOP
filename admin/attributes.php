<?php
	
	include('../autoloader.php');
	include('../core/config.php');

	use \core\models\AttributesModel;
	use \core\models\CategoriesModel;

	$attributesHandler = new AttributesModel();
	$categoriesHandler = new CategoriesModel();


	if (isset($_POST['delete'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			//removeAttr($id);
			$attributesHandler->removeAttribute($id);
		}
	} else if (isset($_POST['add'])) {
		if (isset($_POST['name'])) {
			$parent = (isset($_POST['parent'])) ? intval($_POST['parent']) : 0;
			$name = $_POST['name'];
			//newAttr($name, $parent);
			$attributesHandler->newAttribute($name,$parent);
		}
	} else if (isset($_POST['edit'])) {
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
			$parent = (isset($_POST['parent']) && $_POST['parent']!='noch') ? intval($_POST['parent']) : false;
			$name = (isset($_POST['name']) && $_POST['name']!='') ? $_POST['name'] : false;
			//updateAttr($id, $name, $parent);
			$attributesHandler->updateAttribute($id, $name, $parent);
		}
	}

	function echoCategoriesOptions() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}
		
		$result = $connection->query('SELECT * FROM Categories');
		if ($result !== false) {
			while ($row = $result->fetch_assoc()) {
				echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
			}
		}
	}

	function echoAttributesList() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}

		$query = '
			SELECT 
				Attribute.id AS id,
				Attribute.title AS attr, 
				Categories.title AS cat
			FROM Attribute
			LEFT JOIN Categories
			ON Attribute.categ_id = Categories.id
		';

		$result = $connection->query($query);
		if ($result !== false) {
			while ($row = $result->fetch_assoc()) {
				echo '<div class="node">#'.$row['id'].' '.$row['attr'].' ('.$row['cat'].')</div>';
			}
		}
	}

	function echoAttributesOptions() {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// стандартна перевірка підключення до бази даних
		if ($connection->connect_error) {
		    die('Connect Error ('.$connection->connect_errno.') '.$connection->connect_error);
		}
		
		$result = $connection->query('SELECT * FROM Attribute');
		if ($result !== false) {
			while ($row = $result->fetch_assoc()) {
				echo '<option value="'.$row['id'].'">('.$row['id'].') '.$row['title'].'</option>';
			}
		}
	}
?>

<html>
<head>
	<meta charset="utf8">
	<title>Редагування cписку атрибутів</title>
	<style>
		.node {
			margin-left: 3em;
			margin-top: 1em;
			padding: 0.25em 1em;
			border: 1px solid black;
		}
		.half {
			float: 		left; 
			padding: 	2%; 
			width: 		46%;
		}
	</style>
</head>
<body>
	<div class="half">
	<?php
		$attributesHandler->echoAttributesList();
	?>
	</div>
	<div class="half" style="position: fixed; right:0">
		<hr>
		<h1>Додавання нового атрибуту</h1>
		<form method="POST">
			<p>Назва: <input name="name" type="text"></p>
			<p>Батьківська категорія: <select name="parent">
				<?php $categoriesHandler->echoCategoriesOptions(); ?>
			</select></p>
			<input name="add" type="submit" value="Додати">
		</form>
		<hr>
		<h1>Видалення атрибуту</h1>
		<form method="POST">
			<p>Категорія для видалення: <select name="id">
				<?php $attributesHandler->echoOptionsWithList($attributesHandler->attributesList()); ?>
			</select></p>
			<input name="delete" type="submit" value="Видалити">
		</form>
		<hr>
		<h1>Редагування атрибутів</h1>
		<form method="POST">
			<p>Атрибут для редагування: <select name="id">
				<?php $attributesHandler->echoOptionsWithList($attributesHandler->attributesList()); ?>
			</select></p>
			<p>Нова назва: <input name="name" type="text"></p>
			<p>Нова батьківська категорія: <select name="parent">
				<option value="noch" selected="">БЕЗ ЗМІН</option>
				<option value="0">КОРЕНЕВА</option>
				<?php $categoriesHandler->echoCategoriesOptions(); ?>
			</select></p>
			<input name="edit" type="submit" value="Редагувати">
		</form>
		<hr>
	</div>
</body>
</html>