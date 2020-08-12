<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'tool.css'); ?>">
    <title>商品管理ページ</title>
</head>

<body>
    <h1>商品管理ページ</h1>
    <div><a href="<?php print (USER_TOOL_URL); ?>">ユーザ管理ページへ</a></div>
    <div><a href="<?php print (PRODUCTS_LIST_URL); ?>">商品一覧へ</a></div>
    <a href="<?php print (LOGOUT_URL); ?>">ログアウト</a>
    
    <!---- エラーメッセージの出力処理 ---->
    <?php if(count($err_msg) > 0) {  ?>                         
        <ul>
            <?php foreach($err_msg as $message) { ?>
                <li><?php print $message; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    
    <h2>新規商品追加</h2> 
    
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="new_products">  <!-- actionの値 = new_products -->
        <label>商品名　 <input type="text" name="name" ><br></label>
        <label>作者名　 <input type="text" name="author"><br></label>
        <label>出版社　
        <select name="publisher">
            <option value="0">　　　</option>
            <option value="1">S英社</option>
            <option value="2">K談社</option>
            <option value="3">S学館</option>
            <option value="4">A田書店</option>
        </select><br>
        </label>
        <label>ジャンル
        <select name="type">
            <option value="0">　　　</option>
            <option value="1">バトル</option>
            <option value="2">ギャグ</option>
            <option value="3">スポーツ</option>
            <option value="4">ラブコメ</option>
        </select><br>
        </label>
        <label>価　格　 <input type="text" name="price"><br></label>
        <label>個　数　 <input type="text" name="stock"></label>
        <div><input type="file" name="new_img"></div>
        <div>
            <select name="status">
                <option value="0">非公開</option> <!-- statusの値 = 0 -->
                <option value="1">公開</option>   <!-- statusの値 = 1 -->
            </select>
        </div>
        <!-- sessionに保存されているトークンの送信 -->
        <input type="hidden" name="token" value="<?php print($token); ?>">  
        <div><input type="submit" value="◆◇◆ 商品追加 ◆◇◆"></div>
    </form>
    
    <h2>商品情報変更</h2>
    <table>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>作者名</th>
            <th>出版社</th>
            <th>ジャンル</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>ステータス</th>
            <th>操作</th>
        </tr>
      
    <?php foreach ($data as $value) { ?>
    
        <tr>
            <td><img src="<?php print IMAGE_PATH . $value['img']; ?>"></td>
            <td><?php print h($value['name']); ?></td>
            <td><?php print h($value['author']); ?></td>
            <td>
                <?php if($value['publisher'] === 1) { ?>
                    <?php print 'S英社'; ?>
                <?php } else if($value['publisher'] === 2) { ?>
                    <?php print 'K談社'; ?>
                <?php } else if($value['publisher'] === 3) { ?>
                    <?php print 'S学館'; ?>
                <?php } else if($value['publisher'] === 4) { ?>
                    <?php print 'A田書店'; ?>
                <?php } ?>
            </td>
            <td>
                <?php if($value['type'] === 1) { ?>
                    <?php print 'バトル'; ?>
                <?php } else if($value['type'] === 2) { ?>
                    <?php print 'ギャグ'; ?>
                <?php } else if($value['type'] === 3) { ?>
                    <?php print 'スポーツ'; ?>
                <?php } else if($value['type'] === 4) { ?>
                    <?php print 'ラブコメ'; ?>
                <?php } ?>
            </td>
            <td><?php print h($value['price']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="action" value="update_stock">  <!--actionの値 = update_stock-->
                    <input type="hidden" name="id" value="<?php print $value['id'] ?>">
                    <!-- sessionに保存されているトークンの送信 -->
                    <input type="hidden" name="token" value="<?php print($token); ?>">   
                    <label><input type="text" name="change_stock" value="<?php print h($value['stock']); ?>">個</label>
                    <label><input type="submit" value="変更"></label>
                </form>
            </td>
            <td>
                <!-- 得たstatusカラムの値(0 or 1)をPOSTで送る -->
                <form method="post">
                    <?php if ($value['status'] === 0) { ?>
                        <p style="color:red;"><?php print '状態：非公開'; ?></p>
                    <?php } else { ?>
                        <p><?php print '状態：公開'; ?></p>
                    <?php } ?>
                    <input type="hidden" name="change_status" value="<?php print $value['status'] ?>">
                    <input type="hidden" name="id" value="<?php print $value['id'] ?>">     
                    <input type="hidden" name="action" value="change_status">  <!--actionの値 = change_status-->
                    <!-- sessionに保存されているトークンの送信 -->
                    <input type="hidden" name="token" value="<?php print ($token); ?>">
                    <input type="submit" value="非公開⇔公開">
                </form>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="action" value="delete_column">
                    <input type="hidden" name="id" value="<?php print $value['id'] ?>">
                    <!-- sessionに保存されているトークンの送信 -->
                    <input type="hidden" name="token" value="<?php print($token); ?>">
                    <input type="submit" value="削除">
                </form>
            </td>
        <tr>
        
    <?php } ?>
    </table>

</body>
</html>