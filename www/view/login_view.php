<!-- 以下、エラーメッセージ出力用 -->

<!DOCTYPE html>
<html lang="ja">
    
<head>
    <meta charset="utf-8">
    <title>ログイン</title>
</head>

<body>
    <p>ログインに失敗しました。</p>
  
    <?php if(count($err_msg) > 0) {  ?>
    <ul>
        <?php foreach($err_msg as $message) { ?>
        <li><?php print $message; ?></li>
        <?php } ?>
    </ul>
    <?php } ?>
  
    <a href="top_login.php">
    ログインページへ戻る
    </a>
  
</body>
</html>