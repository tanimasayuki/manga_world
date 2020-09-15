<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>作品一覧</title>
    <link rel="stylesheet" href="html5reset-1.6.1.css">
    <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'products_list.css'); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
        $(function() {
            // 一旦hide()で隠してからフェードイン
            $('#list').hide().fadeIn('fast');
            // class'showUp'を#listに追加
            $('#list').addClass('showUp');
        });
    </script>
</head>

<body>
    <!-- ヘッダー情報 -->
    <?php include VIEW_PATH . 'templates/list_header.php'; ?>
    
    <article>
        
        <section>
            <h1 id="h1">作品一覧</h1>
            <?php print 'ようこそ' . h($user[0]['user_name']) . 'さん' ?>
            <?php if ($_SESSION['id'] === 9) {?>
                 <a href="<?php print(TOOL_URL); ?>">⇒ 商品管理画面へ</a> 
            <?php } ?>
        </section>
    
        <!---- ランキング ---->
        <section>
            <details>
                <summary id="ranking-h1">人気ランキング</summary>
                <div id="ranking">
                    <div class="ranking-item">
                        <div class="name_length"><?php print '１位：'.h($rankings[0]['name']); ?></div>
                        <p><img class="ranking-img" src="<?php print IMAGE_PATH . $rankings[0]['img']; ?>"></p>
                        <p><?php print '売上冊数：'.h($rankings[0]['total'].'冊'); ?></p>
                    </div>
                    <div class="ranking-item">
                        <div class="name_length"><?php print '２位：'.h($rankings[1]['name']); ?></div>
                        <p><img class="ranking-img" src="<?php print IMAGE_PATH . $rankings[1]['img']; ?>"></p>
                        <p><?php print '売上冊数：'.h($rankings[1]['total'].'冊'); ?></p>
                    </div>
                    <div class="ranking-item">
                        <div class="name_length"><?php print '３位：'.h($rankings[2]['name']); ?></div>
                        <p><img class="ranking-img" src="<?php print IMAGE_PATH . $rankings[2]['img']; ?>"></p>
                        <p><?php print '売上冊数：'.h($rankings[2]['total'].'冊'); ?></p>
                    </div>
                </div>
            </details>
        </section>
        
        <section>
            <details>
                <summary>検索・並べ替え</summary>
                <!---- 商品名検索 ---->
                <h2 style="font-size: 20px;">商品名検索</h2>
                <form method="GET">
                    <label><input type="search" name="name"></label>
                    <label><input type="submit" name="name_search" value="検索"></label>
                </form>
                
                <!---- 絞り込み検索 ---->
                <h2 style="font-size: 20px;">絞り込み検索</h2>
                <form method="GET">
                    <label>作者名<input type="search" name="search_author"></label>
                    <label>出版社
                    <select name="search_publisher">
                        <option value="0">     </option>
                        <option value="1">S英社</option>
                        <option value="2">K談社</option>
                        <option value="3">S学館</option>
                        <option value="4">A田書店</option>
                    </select>
                    </label>
                    <label>ジャンル
                    <select name="search_type">
                        <option value="0">     </option>
                        <option value="1">バトル</option>
                        <option value="2">ギャグ</option>
                        <option value="3">スポーツ</option>
                        <option value="4">ラブコメ</option>
                    </select>
                    </label>
                    <label><input type="submit" name="search" value="絞り込み検索"></label>
                    <input type="hidden" name="action" value="search">
                </form>
                
                <!---- 並べ替え ---->
                <div id="sort">
                    <h2 style="font-size: 20px;">商品の並べ替え</h2>
                    <form method="GET">
                        <label>
                        <select name="sort_products">
                            <option value="0">    </option>
                            <option value="1">新着順</option>
                            <option value="2">価格の安い順</option>
                            <option value="3">価格の高い順</option>
                        </select>
                        </label>
                        <label><input type="submit" name="sort" value="並べ替える"></label>
                    </form>
                </div>
            </details>
        </section>
        
        <!--- エラーメッセージの出力処理 ---->
        <?php if(count($err_msg) > 0) {  ?>
            <ul>
        <?php foreach($err_msg as $message) { ?>
            <li><?php print $message; ?></li>
        <?php } ?>
            </ul>
        <?php } ?>
        
        <!---- DBに登録された内容を出力する ---->
        <section>
            <div id="list">
            <?php foreach($data as $value) { ?>
                <div id="flex">
                    <div class="book">
                        <!-- 画像 -->
                        <div><span><img src="<?php print IMAGE_PATH . $value['img']; ?>"></span></div>
                        <!-- 商品名 -->
                        <div class="name_length">
                            <form method="GET" action="<?php print (INFORMATION_URL); ?>">
                                <input type="hidden" name="id" value="<?php print $value['id']; ?>">
                                <input type="submit" id="products_name" name="name" value="<?php print h($value['name']); ?>">
                            </form>
                        </div>
                        <!-- 作者名 -->
                        <div class="name_length"><?php print h($value['author']); ?></div>
                        <!-- 価格 -->
                        <div class="name_length"><?php print h($value['price'].'円'); ?></div>
                        <!-- カートに入れる -->
                        <form method="POST">
                        <?php if($value['stock'] > 0) { ?>
                            <input type="hidden" name="product_id" value="<?php print $value['id']; ?>">
                            <!-- sessionに保存されているトークンの送信 -->
                            <input type="hidden" name="token" value="<?php print($token); ?>">
                            <input type="submit" name="cart" value="カートに入れる">
                        <?php } else { ?>
                            <div id="font"><span>売り切れ</span></div>
                        <?php } ?>
                        </form>
                    </div>
                </div>
            <?php } ?>
            </div>
        </section>
        
    </article>

    <footer>
        <div id=footer>
            <a href=form.php>お問い合わせフォーム</a>
        </div>
    </footer>
</body>
</html>