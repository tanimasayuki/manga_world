<!DOCTYPE html>
<html lang="ja">
    
<head>
    <meta charset="utf-8">
    <title>商品詳細</title>
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'products_information.css'); ?>">
</head>

<body>
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/header.php'; ?>
    
    <h1>商品詳細</h1>
    
    <!---- 商品一覧から商品名を押した時のみ、商品詳細情報を表示する ---->
    <?php if(isset($_GET['id']) === TRUE) { ?>
        

        <!---- エラーメッセージの出力処理 ----->
        <?php if(count($err_msg) > 0) {  ?>
            <ul>
        <?php foreach($err_msg as $message) { ?>
            <li><?php print $message; ?></li>
        <?php } ?>
            </ul>
        <?php } ?>
        

        <!---- 商品詳細 ---->

        <!-- 商品画像 -->
        <div id="information">
            <div><img src="<?php print IMAGE_PATH . $data[0]['img']; ?>"></div>
            
            <!-- タイトル -->
            <div id="information2">
                <div class="information3"><?php print 'タイトル：' . h($data[0]['name']); ?></div>
                
                <!-- 作者名 -->
                <div class="information3">  
                    <!-- リンクからGETデータを渡す -->
                    <form name="search_author" method="GET" action="<?php print (PRODUCTS_LIST_URL) ?>">
                        <label>
                        作者　　：<a href="javascript:document.search_author.submit()"><?php print h($data[0]['author']); ?></a>
                        </lavel>
                        <input type="hidden" name="search_author" value="<?php print h($data[0]['author']); ?>">
                    </form>
                </div>
                
                <!-- 出版社 -->
                <div class="information3">
                    <!-- リンクからGETデータを渡す -->
                    <form name="search_publisher" method="GET" action="<?php print (PRODUCTS_LIST_URL) ?>">
                        <label>
                        出版社　：<a href="javascript:document.search_publisher.submit()"><?php print h($publishers[$data[0]['publisher']-1]); ?></a>
                        </lavel>
                        <input type="hidden" name="search_publisher" value="<?php print h($data[0]['publisher']); ?>">
                    </form>
                </div>
                
                <!-- ジャンル -->
                <div class="information3">
                    <!-- リンクからGETデータを渡す -->
                    <form name="search_type" method="GET" action="<?php print (PRODUCTS_LIST_URL) ?>">
                        <label>
                        ジャンル：<a href="javascript:document.search_type.submit()"><?php print h($type[$data[0]['type']-1]); ?></a>
                        </lavel>
                        <input type="hidden" name="search_type" value="<?php print h($data[0]['type']); ?>">
                    </form>
                </div>
                
                <!-- 価格 -->
                <div class="information3">
                    <?php print '価格　　：' . h($data[0]['price']) . '円'; ?>
                </div>

                <!-- 平均評価 -->
                <div class="information3">
                    <div>平均評価：
                        <?php if($average[0]['average'] >= 4.5) { ?>
                            <span class="evaluation">★★★★★</span>
                        <?php } else if($average[0]['average'] >= 3.5) { ?>
                            <span class="evaluation">★★★★☆</span>
                        <?php } else if($average[0]['average'] >= 2.5) { ?>
                            <span class="evaluation">★★★☆☆</span>
                        <?php } else if($average[0]['average'] >= 1.5) { ?>
                            <span class="evaluation">★★☆☆☆</span>
                        <?php } else if($average[0]['average'] >= 0.1 && $average[0]['average'] < 1.5) { ?>
                            <span class="evaluation">★☆☆☆☆</span>
                        <?php } else { ?>
                            <span class="evaluation">評価がありません</span>
                        <?php } ?>
                        
                        <!-- 平均評価0.1以上のときのみ平均点数を表示 -->
                        <?php if($average[0]['average'] >= 0.1) { ?>
                        <?php print '('.$average[0]['average'].'点)' ?>
                        <?php } ?>
                    </div>
                </div>

                <!---- カートに入れる ---->

                <?php if($data[0]['stock'] > 0) { ?>
                    <form method="POST">
                    <div class="information3" id="submit">
                        <input type="submit" value="カートに入れる">
                        <input type="hidden" name="product_id" value="<?php print h($data[0]['id']); ?>"> <!-- 商品IDを送信 -->
                        <!-- sessionに保存されているトークンの送信 -->
                        <input type="hidden" name="token" value="<?php print($token); ?>">
                    </div>
                    </form>
                <?php } else { ?>
                    <div class="information3"><span>売り切れ</span></div>
                <?php } ?>
                        
            </div>
        </div>
        

        <!---- レビュー内容の出力 ---->
        <?php if(isset($_POST['review']) === TRUE) { ?>
        <details open>
        <?php } else { ?>
        <details>
        <?php } ?>
            <summary>レビュー一覧</summary>
            <?php foreach($reviews as $rvws) { ?>
                <!-- ユーザ名 / 投稿日 -->
                <p><?php print h($rvws['user_name']).'さん'; ?><?php print '　['.h($rvws['create_datetime']).']'; ?></p>
                
                <!-- 評価 -->
                <?php if($rvws['evaluation'] === 5) { ?>
                    <p>評価：<span class='evaluation'>★★★★★</span></p>
                <?php } else if($rvws['evaluation'] === 4) { ?>
                    <p>評価：<span class='evaluation'>★★★★☆</span></p>
                <?php } else if($rvws['evaluation'] === 3) { ?>
                    <p>評価：<span class='evaluation'>★★★☆☆</span></p>
                <?php } else if($rvws['evaluation'] === 2) { ?>
                    <p>評価：<span class='evaluation'>★★☆☆☆</span></p>
                <?php } else if($rvws['evaluation'] === 1) { ?>
                    <p>評価：<span class='evaluation'>★☆☆☆☆</span></p>
                <?php } ?>
                
                <!-- レビュー内容 -->
                <p><?php print h($rvws['review']); ?></p>
            <?php } ?>
            

            <!---- エラーメッセージの出力設定 ---->
            <?php foreach($review_err_msg as $read) { ?>
                    <p style=color:red;><?php print $read; ?></p>
            <?php } ?>
            

            <!---- レビュー入力部分 ---->
            <form method="POST">
                <label>評　　価
                <select class="evaluation" name="evaluation">
                    <option class="evaluation" value="5">★★★★★</option>
                    <option class="evaluation" value="4">★★★★☆</option>
                    <option class="evaluation" value="3">★★★☆☆</option>
                    <option class="evaluation" value="2">★★☆☆☆</option>
                    <option class="evaluation" value="1">★☆☆☆☆</option>
                </select><br>
                </label>
                <label>レビュー
                <textarea name="review" cols="50" rows="10" placeholder="300文字以内"></textarea><br>
                </label>
                <!-- sessionに保存されているトークンの送信 -->
                <input type="hidden" name="token" value="<?php print($token); ?>">
                <input type="submit" id="review" value="レビューを送信">
            </form>
        </details>
    
    <?php } else { ?>
        <p id="p">不正な操作です</p>
    <?php } ?>
</body>
</html>