<?php
require_once MODEL_PATH . 'db.php';

//全ユーザ情報の取得
function get_user($dbh) {
    try {
        $sql = 
           'SELECT 
                user_name, create_date
            FROM 
                comics_users';
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(); 
        $datas = $stmt->fetchAll();
    } catch(PDOException $e) {
        throw $e;
    }
    return $datas;
}
?>