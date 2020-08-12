<?php
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );
define('IMAGE_PATH', '/assets/images/');
define('STYLESHEET_PATH', '/assets/css/');

//DBの設定情報
define('DB_NAME', 'sample'); //MySQLのDB名
define('DB_HOST', 'mysql'); //localhost
define('DB_CHARSET', 'utf8'); //DBの文字コード
define('DB_USER', 'testuser'); //MySQLのユーザー名
define('DB_PASS', 'password'); //MySQLのパスワード

//各ページのURl
define('LOGIN_URL', '/top_login.php'); //ログインページ
define('LOGOUT_URL', '/logout.php'); //ログアウト
define('PRODUCTS_LIST_URL', '/products_list.php'); //商品一覧
define('FORM_RRL', '/form.php'); //お問い合わせ
define('TOOL_URL', '/tool.php'); //商品管理
define('RESULT_URL', '/shopping_result.php'); //購入結果
define('ORDER_URL', '/order.php'); //購入履歴
define('STATEMENT_URL', '/statement.php'); //明細
define('INFORMATION_URL', '/products_information.php'); //商品詳細
define('CART_URL', '/shopping_cart.php'); //ショッピングカート
define('USER_REGISTER_URL', '/user_register.php'); //ユーザ登録
define('USER_REGISTER_RESULT_URL', '/user_register_result.php'); //ユーザ登録完了
define('USER_TOOL_URL', 'user_tool.php'); //ユーザ管理
