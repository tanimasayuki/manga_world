<!DOCTYPE html>
<html lang="ja">
    
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'user_tool.css'); ?>">
    <title>ユーザ管理ページ</title>
</head>

<body>
    
    <h1>漫画ワールド ユーザ管理ページ</h1>
    
    <div><a href="tool.php">商品管理ページへ</a></div>
    <a href="logout.php">ログアウト</a>
    
    <!--エラーメッセージの出力処理-->
    <?php if(count($err_msg) > 0) {  ?>
        <ul>
            <?php foreach($err_msg as $message) { ?>
                <li><?php print $message; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
  
    <table>
          <tr>
              <th>ユーザ名</th>
              <th>登録日時</th>
          </tr>
          <?php foreach($data as $read){ ?> 
              <tr> 
                  <td><?php print h($read['user_name']); ?></td>
                  <td><?php print $read['create_date']; ?></td>
              </tr>
          <?php } ?>
    </table>
  
</body>
</html>