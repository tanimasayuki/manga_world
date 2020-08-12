<header>
    <div id="nav-drawer">
        <input id="nav-input" type="checkbox" class="nav-unshown">
        <label id="nav-open" for="nav-input"><span></span></label>
        <label class="nav-unshown" id="nav-close" for="nav-input"></label>
        
        <div id="nav-content">
            <div id="nav-publisher">
                <p class="nav-p">出版社別</p>
                <form name="search_publisher" method="GET">
                    <input type="submit" class="submit" name="publisher" value="　➤　S英社　">
                    <input type="hidden" name="search_publisher" value="1">
                </form>
                <form name="search_publisher" method="GET">
                    <input type="submit" class="submit" name="publisher" value="　➤　K談社　">
                    <input type="hidden" name="search_publisher" value="2">
                </form>
                    <form name="search_publisher" method="GET">
                    <input type="submit" class="submit" name="publisher" value="　➤　S学館　">
                    <input type="hidden" name="search_publisher" value="3">
                </form>
                <form name="search_publisher" method="GET">
                    <input type="submit" class="submit" name="publisher" value="　➤　A田書店">
                    <input type="hidden" name="search_publisher" value="4">
                </form>
            </div>
            
            <div id="nav-type">
                <p class="nav-p">ジャンル別</p>
                <form name="search_type" method="GET">
                    <input type="submit" class="submit" name="type" value="　➤　バトル　">
                    <input type="hidden" name="search_type" value="1">
                </form>
                <form name="search_type" method="GET">
                    <input type="submit" class="submit" name="type" value="　➤　ギャグ　">
                    <input type="hidden" name="search_type" value="2">
                </form>
                <form name="search_type" method="GET">
                    <input type="submit" class="submit" name="type" value="　➤　スポーツ">
                    <input type="hidden" name="search_type" value="3">
                </form>
                <form name="search_type" method="GET">
                    <input type="submit" class="submit" name="type" value="　➤　ラブコメ">
                    <input type="hidden" name="search_type" value="4">
                </form>
            </div>
        </div>
    </div>

    <a class="header_menu" href="<?php print (PRODUCTS_LIST_URL); ?>">商品一覧へ</a>
    <a class="header_menu" href="<?php print (CART_URL); ?>">ショッピングカートへ</a>
    <a class="header_menu" href="<?php print (ORDER_URL); ?>">購入履歴へ</a>
    <a class="header_menu" href="<?php print (LOGOUT_URL); ?>">ログアウト</a>
</header>