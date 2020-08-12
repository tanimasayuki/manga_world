<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>ユーザ登録</title>
</head>

<body>
    <h1>ユーザ登録</h1>
    
    <!---- エラーメッセージの出力処理 ---->
    <?php if(count($err_msg) > 0) {  ?>
        <ul>
            <?php foreach($err_msg as $message) { ?>
            <li><?php print $message; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    
    <!---- ユーザ情報入力フォーム ---->
    <form method="POST">                                              
        <table>
            <tr>
                <td>ユーザ名</td>
                <td><input type="text" name="user_name" placeholder="半角英数字6文字以上 15文字以下"></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input type="text" name="user_password" placeholder="半角英数字6文字以上"></td>
            </tr>
            <tr>
                <td>メールアドレス</td>
                <td><input type="text" name="email_address" placeholder="例：Wa-da.taro@gmail.com"></td>
            </tr>
            <tr>
                <td>性別</td>
                <td><input type="radio" name="sex" value="1">男性　<input type="radio" name="sex" value="2">女性</td>
            </tr>
            <tr>
                <td>生年月日</td>
                <td>
                  <input type="text" name="birthday" placeholder="例：1990/1/13 (半角数字)">
                </td>
            </tr>
        </table>
        <!-- sessionに保存されているトークンの送信 -->
        <input type="hidden" name="token" value="<?php print($token); ?>">
        <input type="submit" value="新規登録">
    </form>
  
</body>
</html>