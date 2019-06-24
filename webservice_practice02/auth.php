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
    session_destroy();  //全ての格納されているセッションを破壊する。
    // ログインページへ
    header("Location:login.php");
  }else{
    debug('ログイン有効期限以内です。');
    //最終ログイン日時を現在日時に更新
    $_SESSION['login_date'] = time();
    debug('マイページへ遷移します。');
    header("Location:mypage.html"); //マイページへ
  }

}else{
  debug('未ログインユーザーです。');
}