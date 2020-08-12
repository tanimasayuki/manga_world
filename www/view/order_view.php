<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>購入履歴</title>
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>"> 
</head>
<body>
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/header.php'; ?>
    
    <h1>購入履歴</h1>

    <!---- 購入履歴がある場合に表示 ---->
    <?php if(count($orders) > 0) { ?>
    
        <table>
            <thead>
                <tr>
                    <th>注文番号</th>
                    <th>購入日時（新着順）</th>
                    <th>合計金額</th>
                    <th>購入明細</th>
                </tr>
            </thead>
    
            <tbody>
                <?php foreach($orders as $order){ ?>
                <tr>
                    <td><?php print (h($order['order_id'])); ?></td>
                    <td><?php print (h($order['order_datetime'])); ?></td>
                    <td><?php print (h($order['total'])); ?></td>
                    <td>
                        <form method="post" name="order" action="<?php print(STATEMENT_URL); ?>">
                            <input type="hidden" name="order_id" value=<?php print (h($order['order_id'])); ?>>
                            <input type="hidden" name="order_datetime" value=<?php print (h($order['order_datetime'])); ?>>
                            <input type="hidden" name="total" value=<?php print (h($order['total'])); ?>>
                            <!-- sessionに保存されているトークンの送信 -->
                            <input type="hidden" name="token" value="<?php print($token); ?>">   
                            <input type="submit" value="明細">    
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
    
        </table> 
        
    <?php } else { ?>
        <p>購入履歴がありません</p>
    <?php } ?>
    
</body>