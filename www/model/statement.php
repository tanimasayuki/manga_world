<?php
require_once MODEL_PATH . 'db.php';

function get_statements($dbh) {
    try{
        $sql=
            'SELECT
                item_name,
                price,
                amount
            FROM
                comics_statements
            WHERE
                order_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $_POST['order_id'], PDO::PARAM_INT);
        $stmt->execute();
        $statement = $stmt->fetchAll();
        
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $statement;
}

?>