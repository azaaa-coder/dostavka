<?php
//подключили базу данных
require 'connectBD.php';
// функция удаления
function delete($pdo, $id,) {
    $sql = "DELETE FROM `bookings` WHERE `id` = :id";
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute($id);
}
//вызов функции
delete($pdo, $_GET);
header('Location: index.php');
?>
