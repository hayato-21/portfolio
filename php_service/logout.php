<?php
//共通変数・関数ファイルを読込み
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログアウトページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
debug('ログアウトします。');
// セッションを削除（ログアウトする）
session_destroy();
debug('ログインページへ遷移します。');
// ログインページへ
header("Location:login.php");
//セッションデストロイは、IDごと消すが、unsetは、セッションのIDを消さない。中身を消すだけ、悪意のあるユーザーがセッションのIDを見て、ログインする恐れがある。

// logout.phpのつくり方
// 1 session_destroy
// 2 header(login.php)