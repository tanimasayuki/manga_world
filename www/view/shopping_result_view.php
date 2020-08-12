<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>購入完了ページ</title>
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'shopping_result.css'); ?>">
</head>

<body>
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/header.php'; ?>

    <!---- 購入確定時だけ以下の内容を表示 ---->
    <?php if(isset($_POST['result']) === TRUE && count($err_msg) === 0) { ?>

        <h1>お買い上げありがとうございました！</h1>

        <!---- 購入した商品の情報 ---->
        <table>
            <tr>
                <th></th>
                <th>購入商品</th>
                <th>単価</th>
                <th>購入冊数</th>
            </tr>
            
            <?php foreach ($data as $value) { ?>
            <tr>
                <!-- 商品画像 -->
                <td>
                    <img src="<?php print IMAGE_PATH . $value['img']; ?>">
                </td>
                <!-- 商品情報 (タイトル,作者) -->
                <td>
                    <div><?php print h($value['name']); ?></div>
                    <div><?php print h($value['author']); ?></div>
                </td>
                <!-- 価格 -->
                <td style="color: red;">
                    <?php print h($value['price']) . '円'; ?>
                </td>
                <!-- 購入冊数 -->
                <td>
                    <?php print h($value['amount']) . '冊'; ?>
                </td>
            <tr>
            <?php } ?>
        </table>
    
        <!---- 購入合計金額の表示 ---->
        <p id="price">
            合計金額：<?php print h($price[0]['SUM(comics_information. price * comics_carts. amount)']); ?>円
        </p>
        
    <?php } else { ?>

        <h1>購入結果</h1>
        <p id="p1">購入失敗</p>
        <ul>
            <?php foreach($err_msg as $message) { ?>
                <li><?php print $message; ?></li>
            <?php } ?>
        </ul>

    <?php } ?>

</body>
</html>