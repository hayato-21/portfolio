<?php
//================================
// ログイン認証・自動ログアウト
//================================
// ログインしている場合
if( !empty($_SESSION['login_date']) ){
  debug('ログイン済みユーザーです。');
  // 現在日時が最終ログイン日時＋有効期限を超えていた場合
  if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
    debug('ログイン有効期限オーバーです。');
    // セッションを削除（ログアウトする）
    session_destroy();
    // ログインページへ
    header("Location:login.php");
  }else{
    debug('ログイン有効期限以内です。');
    //最終ログイン日時を現在日時に更新
    $_SESSION['login_date'] = time();　//もしここで、現在日時に更新しないと、例えば、１時間だったら、１時間で必ず切れるとなる。
    
    //現在実行中のスクリプトファイル名がlogin.phpの場合
    //$_SERVER['PHP_SELF']はドメインからのパスを返すため、今回だと「/webukatu_practice03/login.php」が返ってくるので、
    //さらにbasename関数を使うことでファイル名だけを取り出せる
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('マイページへ遷移します。');
      header("Location:mypage.php"); //マイページへ
    }//マイページにもheaderがあるため、無限ループが繰り返される。
     //それを防ぐのが、上
    
  }
}else{
  debug('未ログインユーザーです。');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
     header("Location:login.php"); //ログインページへ
  }
}