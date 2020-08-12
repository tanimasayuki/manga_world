<?php
require_once MODEL_PATH . 'db.php';

//ユーザ名とパスワードに応じたユーザIDを取得する関数
function get_user_id($sql, $dbh, $user_name, $password) {

    try {
        $sql = 
        'SELECT
            id
        FROM
            comics_users
        WHERE
            user_name = ? 
            AND password = ?';

        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $data;

    } catch(PDOException $e) {
    $err_msg[] = 'DBエラー:'.$e->getMessage();
    }

}

?>