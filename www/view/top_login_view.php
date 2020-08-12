<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログインページ</title>
    <style>
        input {
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>ログイン</h1>
    
    <!----ユーザ名とパスワードをPOSTで送信 ---->
    <form action="./login.php" method="POST">
        <label>ユーザ名</label>
        <input type="text" name="user_name" value="<?php print h($user_name); ?>" placeholder="半角英数字6文字以上">
        <label>パスワード</label>
        <input type="password" name="password" placeholder="半角英数字6文字以上">
        <!-- sessionに保存されているトークンの送信 -->
        <input type="hidden" name="token" value="<?php print($token); ?>">
        <input type="submit" name="login" value="ログイン">
    </form>
    <p>ユーザ登録がお済み出ない方はこちら　⇒　
    <a href="<?php print (USER_REGISTER_URL); ?>">新規ユーザ登録</a>
    </p>
</body>
</html>