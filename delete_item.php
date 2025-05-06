<?php
if (isset($_GET['id'])) {
    $conn = new mysqli("localhost", "root", "", "dbpsa");
    $id = $_GET['id'];
    $conn->query("UPDATE tbl_items SET deleted = 1 WHERE id = $id");
}
?>
