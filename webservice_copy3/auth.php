<?php
//================================
// ログイン認証・自動ログアウト
//================================
// ログインしている場合
if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです。');
    //現在日時が最終ログイン日時+有効期限を越えていた場合
    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
        debug('ログイン有効期限オーバーです。');
        session_destroy();
        header("Location:login.php");
    }else{
        debug('ログイン有効期限以内です。');
        $_SESSION['login_date'] = time();
        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            debug('マイページへ遷移します。');
            header("Location:mypage.php");
        }
    }
}else{
    debug('未ログインユーザーです。');
    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
        header("Location:login.php");
    }
}