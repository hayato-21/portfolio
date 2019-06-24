<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// ログイン画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');

  //変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false; //ショートハンド（略記法）という書き方

  //emailの形式チェック
  validEmail($email, 'email');
  //emailの最大文字数チェック
  validMaxLen($email, 'email');

  //パスワードの半角英数字チェック
  validHalf($pass, 'pass');
  //パスワードの最大文字数チェック
  validMaxLen($pass, 'pass');
  //パスワードの最小文字数チェック
  validMinLen($pass, 'pass');
  
  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');

  if(empty($err_msg)){
    debug('バリデーションOKです。');
    
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT password,id  FROM users WHERE email = :email';  //どんな情報？password,idを読み取る。どこのテーブル？usersの。
                                                                     // どこのレコードを読み取る？データベースに套路されているemailの文字列とPOST通信で遅れた$emailの文字列が一致するレコード。
      $data = array(':email' => $email);                             // なんで、WHEREの後に、passwordがないわけ？ハッシュ化されているから？取り出す時に、元のパスワードに戻るから？
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      // クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      debug('クエリ結果の中身：'.print_r($result,true));
      
      // パスワード照合
      if(!empty($result) && password_verify($pass, array_shift($result))){ //POST送信の文字列と、データベースから取得した1個目の文字列が一致しているか。
        debug('パスワードがマッチしました。');
        
        //ログイン有効期限（デフォルトを１時間とする）
        $sesLimit = 60*60;
        // 最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time(); //time関数は1970年1月1日 00:00:00 を0として、1秒経過するごとに1ずつ増加させた値が入る
        
        // ログイン保持にチェックがある場合
        if($pass_save){
          debug('ログイン保持にチェックがあります。');
          // ログイン有効期限を30日にしてセット
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        }else{
          debug('ログイン保持にチェックはありません。');
          // 次回からログイン保持しないので、ログイン有効期限を1時間後にセット
          $_SESSION['login_limit'] = $sesLimit;
        }
        // ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];  // 今セッションを保持しているユーザーの情報を、セッションに格納することにより、デバックした時にわかりやすくするため？
                                               // もしくは、後々の処理で、$_SESSION['user_id']の有無で、処理を簡単にするため。これが、診察券のようなもの？
                                               // セッションの値を保持する限り、headerにより、画面遷移を行う。
                                               // もし、passwordを入れてしまうと、パスワードがセッションに入ってることになり、セキュリティ上良くない。
        debug('セッション変数の中身：'.print_r($_SESSION,true));
        debug('マイページへ遷移します。');
        header("Location:mypage.html"); //マイページへ
      }else{
        debug('パスワードがアンマッチです。');
        $err_msg['common'] = MSG09;
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="utf-8">
    <title>ログイン | WEBUKATU MARKET</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  </head>

  <body class="page-login page-1colum">

    <!-- メニュー -->
    <header>
      <div class="site-width">
        <h1><a href="index.html">WEBUKATU MARKET</a></h1>
        <nav id="top-nav">
          <ul>
            <li><a href="signup.html" class="btn btn-primary">ユーザー登録</a></li>
            <li><a href="">ログイン</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- Main -->
      <section id="main" >

       <div class="form-container">
        
         <form action="" method="post" class="form">
           <h2 class="title">ログイン</h2>
           <div class="area-msg">
             <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
             ?>
           </div>
           <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            メールアドレス
             <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
           </label>
           <div class="area-msg">
             <?php 
             if(!empty($err_msg['email'])) echo $err_msg['email'];
             ?>
           </div>
           <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
             パスワード
             <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
           </label>
           <div class="area-msg">
             <?php 
             if(!empty($err_msg['pass'])) echo $err_msg['pass'];
             ?>
           </div>
           <label>
             <input type="checkbox" name="pass_save">次回ログインを省略する
           </label>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="ログイン">
            </div>
            パスワードを忘れた方は<a href="passRemindSend.html">コチラ</a>
         </form>
       </div>

      </section>

    </div>

    <!-- footer -->
    <footer id="footer">
      Copyright <a href="http://webukatu.com/">ウェブカツ!!WEBサービス部</a>. All Rights Reserved.
    </footer>
    
    <script src="js/vendor/jquery-2.2.2.min.js"></script>
    <script>
      $(function(){
        var $ftr = $('#footer');
        if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
          $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
        }
      });
    </script>
  </body>
</html>
<!-- signupの作り方の思考手順 -->
<!-- 1 まず、全体のhtml＆cssを書く。inputはlabelで囲う。label下のdivはメッセージ表示用。abelの一番上は、divの共通のエラー用 -->
<!-- 2 div,label,input,div、１番上のdivは共通のDBエラーを表示用のphp,divで値を保持、ボックスを赤くするためのphp、値を保持するためのphp、エラーメッセージ格納の変数のphp、 -->
<!-- 3 loginは、pass_save用のinputがある。ショートハンド記法で。-->
<!-- 4 POST通信があるかどうか確認して、あれば、それぞれの値を変数に代入する。 -->
<!-- 4 バリデーションチェックをする。3つの未入力→email(形式、最大文字数)→ password(半角英数字、最大文字数、最小文字数) -->
<!-- 5 DB接続 try-cathc文で、DB接続関数、SQL文、データ配列→クエリ実行→
　           クエリ結果の値を取得→パスワード照合→ログイン有効期限（デフォルトを１時間とする）→最終ログイン日時を現在日時に→ログイン保持にチェックがある場合→ログイン有効期限を30日にしてセット→次回からログイン保持しないので、ログイン有効期限を1時間後にセット→ユーザーIDを格納
　           header(Location)→パスワード照合失敗の記述→catchの共通用のを書く。-->
<!-- 6 auth.phpにログイン認証を作る。-->
<!-- ※バリデーションチェック・DB接続、クエリ実行関数（1回のみ）はその都度書く。-->
<!-- ※ function.phpの、ログ、デバッグ、セッション準備・セッション有効期限を延ばす、画面表示処理開始ログ吐き出し関数、定数、を順番に書く。 -->