<?php
require_once MODEL_PATH . 'db.php';

//ログイン中のユーザーIDに応じ、購入履歴を取得する関数
function get_orders($dbh) {
    try{
        $sql=
            'SELECT
                order_id,
                order_datetime,
                (SELECT 
                        SUM(price*amount)
                    FROM 
                        comics_statements 
                    WHERE 
                        order_id = comics_orders.order_id) AS total
            FROM
                comics_orders
            WHERE
                user_id = ?
            ORDER BY
                order_datetime DESC';

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetchAll();

    } catch (PDOException $e) {
        exit('接続できませんでした。理由：'.$e->getMessage() );
    }
    return $order;
}

?>