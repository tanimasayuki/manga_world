<?php
require_once MODEL_PATH . 'db.php';
    
//フォームにお問い合わせ内容を登録する関数
function form_register($dbh) {
    try {
        $sql = 
            'INSERT INTO comics_form
                (name, tel, mail, sex, help, create_datetime)
             VALUES
                (?, ?, ?, ?, ?, NOW())';

        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $_POST['name'], PDO::PARAM_STR);
        $stmt->bindValue(2, $_POST['tel'], PDO::PARAM_STR);
        $stmt->bindValue(3, $_POST['mail'], PDO::PARAM_STR);
        $stmt->bindValue(4, $_POST['sex'], PDO::PARAM_STR);
        $stmt->bindValue(5, $_POST['help'], PDO::PARAM_STR);
        $stmt->execute();

    } catch(PDOException $e) {
      $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

?>