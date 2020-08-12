<?php
require_once MODEL_PATH . 'db.php';

//商品情報を取得する関数
function get_comics_information($dbh) {
    try {
        $sql = 
           'SELECT           
                comics_information. id,
                comics_information. name,
                comics_information. author,
                comics_information. publisher,
                comics_information. type,
                comics_information. price,
                comics_information. img,
                comics_stock. stock
            FROM 
                comics_information
                INNER JOIN comics_stock
                ON comics_information. id = comics_stock. id
            WHERE 
                comics_information. id = ?'; 
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1,$_GET['id'],PDO::PARAM_INT);
        $stmt->execute();
        $datas = $stmt->fetchAll();
    } catch (PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $datas;
}

//レビュー内容を登録する
function review_register($dbh, $product_id, $name, $review, $evaluation, $now_date) {
    try {
        $sql = 
           'INSERT INTO comics_review
                (product_id, user_name, review, evaluation, create_datetime)
            VALUES
                (?, ?, ?, ?, ?)';
        $stmt=$dbh->prepare($sql); 
        $stmt->bindValue(1, $product_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $name, PDO::PARAM_STR);
        $stmt->bindValue(3, $review, PDO::PARAM_STR);
        $stmt->bindValue(4, $evaluation, PDO::PARAM_INT);
        $stmt->bindValue(5, $now_date, PDO::PARAM_STR);
        $stmt->execute();
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
}

//レビュー内容を取得する
function get_review($dbh, $product_id) {
    try {
        $sql =
           'SELECT
                user_name,
                review,
                evaluation,
                create_datetime
            FROM
                comics_review
            WHERE
                product_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $reviews_data = $stmt->fetchAll();
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $reviews_data;
}

//平均評価を算出する
function get_average($dbh, $product_id){
    try {
        $sql =
           'SELECT
                round(SUM(evaluation)/count(evaluation),1) AS average
            FROM
                comics_review
            WHERE
                product_id = ?';
        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(1, $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $averages = $stmt->fetchAll();
    } catch(PDOException $e) {
        $err_msg[] = 'DBエラー:'.$e->getMessage();
    }
    return $averages;
}
?>