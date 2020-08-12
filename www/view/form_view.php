<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ</title>
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'form.css'); ?>">
</head>

<body>
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/header.php'; ?>
    
    <h1>お問い合わせフォーム</h1>

    <!-- お問い合わせ完了時のメッセージ -->
    <?php if($_SERVER['REQUEST_METHOD'] === 'POST' && count($err_msg) === 0) { ?>
        <?php print 'お問い合わせありがとうございました。'; ?>
    <?php } else { ?>
    
    <!-- エラーメッセージの出力設定 -->
    <?php if(count($err_msg) > 0) {  ?>
        <ul>
        <?php foreach($err_msg as $message) { ?>
            <li><?php print $message; ?></li>
        <?php } ?>
        </ul>
    <?php } ?>

    <!-- フォーム部分 -->
    <form method="post" name="submit" id="submit" onsubmit="return check()">
        
    <table>
        <tr>
            <th>ユーザー名</th>
            <td><input type="text" name="name" placeholder="yamadataro"></td>
        </tr>
        <tr>
            <th>電話番号</th>
            <td><input type="tel" name="tel" placeholder="090-1234-5678"></td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td><input type="email" name="mail" placeholder="xxx@xxx"></td>
        </tr>
        <tr>
            <th>性別</th>
            <td>
                <input type="radio" name="sex" value="man">男　
                <input type="radio" name="sex" value="woman">女
            </td>
        </tr>
        <tr>
            <th>お問い合わせ内容</th>
            <td><textarea name="help" cols="50" rows="10" placeholder="1000文字以内"></textarea></td>
        </tr>
    </table>
    
    <script>
        function check(){
            if(window.confirm('この内容で送信しますか？')) {
                return true;
            } else {
                return false;
            }
        }
    </script>

        <!-- sessionに保存されているトークンの送信 -->
        <input type="hidden" name="token" value="<?php print($token); ?>">
        <input type="submit" id="form_submit" value="送信">
    </form>
    
<?php } ?>
</body>
</html>