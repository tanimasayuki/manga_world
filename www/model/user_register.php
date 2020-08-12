<?php
require_once MODEL_PATH . 'db.php';

//ユーザ登録
function user_register($dbh, $user_name, $user_password, $mail, $sex, $birthday, $now_date) {
    try {
        $sql = 
        'INSERT INTO 
                comics_users
                (user_name, password, mail, sex, birthday, create_date, update_date)
            VALUES
                (?,?,?,?,?,?,?)';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_password, PDO::PARAM_STR);
        $stmt->bindValue(3, $mail, PDO::PARAM_STR);
        $stmt->bindValue(4, $sex, PDO::PARAM_INT);
        $stmt->bindValue(5, $birthday, PDO::PARAM_STR);
        $stmt->bindValue(6, $now_date, PDO::PARAM_STR);
        $stmt->bindValue(7, $now_date, PDO::PARAM_STR);
        $stmt->execute();
        //ユーザ登録完了ページへ
        header('Location: ' . USER_REGISTER_RESULT_URL);
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}
?>