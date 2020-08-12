<!DOCTYPE html>
<html lang="ja">
<head>
    <title>購入明細</title>
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'statement.css'); ?>"> 
</head>
<body>
    
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/header.php'; ?>
    
    <h1>購入明細</h1>
    
    <!---- 購入明細ボタンが押されたとき および エラーメッセージが０のときのみ表示 ---->
    <?php if($_SERVER['REQUEST_METHOD'] === 'POST' && count($err_msg) === 0) { ?>
        
        <div id="order">
            <p>
            注文番号：<?php print h($_POST['order_id']); ?>　
            購入日時：<?php print h($_POST['order_datetime']); ?>　
            合計金額：<?php print h($_POST['total']).'円'; ?>　
            </p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>価　格</th>
                    <th>購入数</th>
                    <th>小　計</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($statements as $statement){ ?>
                <tr>
                    <td><?php print h($statement['item_name']); ?></td>
                    <td><?php print h($statement['price']); ?></td>
                    <td><?php print h($statement['amount']); ?></td>
                    <td><?php print h(number_format($statement['price'] * $statement['amount'])); ?>円</td>
                </tr>
                <?php } ?>
            </tbody>
        </table> 

    <?php } else { ?>
        <!--- エラーメッセージの出力処理 ---->
        <?php if(count($err_msg) > 0) {  ?>
            <ul>
        <?php foreach($err_msg as $message) { ?>
            <li><?php print $message; ?></li>
        <?php } ?>
            </ul>
        <?php } ?>
    <?php } ?>
</body>