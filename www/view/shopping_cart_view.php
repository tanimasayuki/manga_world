<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'shopping_cart.css'); ?>">
    <title>ショッピングカート</title>
</head>
    
<body>
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/header.php'; ?>
    
    <h1>ショッピングカート</h1>
    
    <!---- エラーメッセージの出力処理 ----->
    <?php if(count($err_msg) > 0) {  ?>
        <ul>
    <?php foreach($err_msg as $message) { ?>
        <li><?php print $message; ?></li>
    <?php } ?>
        </ul>
    <?php } ?>

    <!---- カートに商品があるときのみ表示 ---->
    <?php if(count($data) > 0) { ?>
        
        <!---- カート内商品情報 ---->
        <table>
        <tr>
            <th>商品画像</th>
            <th>商品</th>
            <th>単価</th>
            <th>購入予定数</th>
            <th>カートから削除</th>
        </tr>
        
        <?php foreach ($data as $value) { ?>
        <tr>
            <!-- 商品画像 -->
            <td><img src="<?php print IMAGE_PATH . $value['img']; ?>"></td>
            <!-- 商品情報 (タイトル,作者) -->
            <td>
                <form method="GET" action="<?php print (INFORMATION_URL); ?>">
                    <input type="hidden" name="id" value="<?php print $value['id']; ?>">
                    <input type="submit" id="products_name" name="name" value="<?php print h($value['name']); ?>">
                </form>
                <div><?php print htmlspecialchars($value['author'],ENT_QUOTES,'UTF-8'); ?></div>
            </td>
            <!-- 価格 -->
            <td>
                <?php print h($value['price']) . '円'; ?>
            </td>
            <!-- 購入予定数 -->
            <td>
                <form method="post">
                    <input type="hidden" name="action" value="update_amount">  <!--actionの値 = update_amount-->
                    <input type="hidden" name="product_id" value="<?php print $value['id'] ?>">
                    <!-- sessionに保存されているトークンの送信 -->
                    <input type="hidden" name="token" value="<?php print($token); ?>">
                    <label><input type="text" name="update_amount" value="<?php print h($value['amount']); ?>">冊</label>
                    <label><input type="submit" value="変更"></label>
                </form>
            </td>
            <!-- 削除ボタン -->
            <script>
                function check(){
                    if(window.confirm('削除しますか？')) {
                        return true;
                    } else {
                        return false;
                    }
                }
            </script>
            
            <td>
                <form method="post" onsubmit="return check()">
                    <input type="submit" value="削除">
                    <!-- sessionに保存されているトークンの送信 -->
                    <input type="hidden" name="token" value="<?php print($token); ?>">
                    <input type="hidden" name="action" value="delete_products">  <!--actionの値 = delete_products-->
                    <input type="hidden" name="product_id" value="<?php print $value['id'] ?>">
                </form>
            </td>
        <tr>
        <?php } ?>
        </table>
        
        <!---- 合計金額の表示 ---->
        <p id="total">合計金額：<?php print h($price[0]['SUM(comics_information. price * comics_carts. amount)']); ?>円</p>
        
        <!---- 購入ボタン ---->
        <?php if(count($data) !== 0) { ?>
            <form method = "post" action="<?php print (RESULT_URL); ?>" id="buy">
                <!-- sessionに保存されているトークンの送信 -->
                <input type="hidden" name="token" value="<?php print($token); ?>">
                <input type="submit" name="result" value="■ □ ■ 購入する ■ □ ■">
            </form>
        <?php } ?>
        
    <?php } else { ?>
        <p id="count_data_0">カートに商品がありません</p>
    <?php } ?>
</body>
</html>