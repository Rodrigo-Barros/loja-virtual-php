<?php 
require ('autoload.php');

$query = Database::sql("SELECT * FROM Usuarios");
$query->execute();

foreach ($query as $result){
    echo $result["nome"];
    echo "<br>";
}



// $sth->execute();
// $result = $sth->fetchAll();
// echo "<pre>";
// var_dump($result);
// echo "</pre>";

?>