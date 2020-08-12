<?php

//ログインチェック
function login_check() {
    if(isset($_SESSION['id'])) {
        return $_SESSION['id'];
    } else {
        header('Location:'.LOGIN_URL);
    }
}

//管理者用ログインチェック
function login_check_admin() {
    if(isset($_SESSION['id']) && $_SESSION['id'] === 9) {
        return $_SESSION['id'];
    } else {
        header('Location:'.LOGIN_URL);
    }
}

//XSS対策
function h ($str){ 
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//30文字のトークンを生成しセッションに保存、取得する。
function get_csrf_token(){
    $token = get_random_string(30);  
    set_session('csrf_token', $token);
    return $token;
}

// トークンのチェックを行う関数
function is_valid_csrf_token($token){
  //$tokenの中身が空ならfalseを返す
  if($token === '') { 
    return false; 
  }
  
  return $token === get_session('csrf_token');
}

//30文字のランダムな文字列を生成
function get_random_string($length = 20){
    return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

//sessionに保存
function set_session($name, $value){
    $_SESSION[$name] = $value;
}

function get_session($name){
    //セッションに値があるとき、そのままその値を返す
    if(isset($_SESSION[$name]) === true){
      return $_SESSION[$name];
    };
    return '';
}
?>