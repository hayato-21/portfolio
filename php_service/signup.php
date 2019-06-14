<?php
//共通変数・関数ファイルを読込み
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ユーザー登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
//post送信されていた場合
if(!empty($_POST)){
  //変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];
  //未入力チェック
  validRequired($email, 'email'); //$emailは$_POST['email']で送られてきた値。例（info@webukatu.com）'email'は、ウェブサーバーにあるキーそのもで。POST送信されていた$_POST['email']
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');
  if(empty($err_msg)){
    //emailの形式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($email, 'email');
    //email重複チェック
    validEmailDup($email);
    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');
    //パスワード（再入力）の最大文字数チェック
    validMaxLen($pass_re, 'pass_re');
    //パスワード（再入力）の最小文字数チェック
    validMinLen($pass_re, 'pass_re');
    if(empty($err_msg)){
      //パスワードとパスワード再入力が合っているかチェック
      validMatch($pass, $pass_re, 'pass_re');
      if(empty($err_msg)){
        //例外処理
        try {
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
          $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s'));
          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          
          // クエリ成功の場合 //ユーザー登録したら、セッションに詰める。じゃないと、ユーザー登録した後にまた、ログインすることになる。
          if($stmt){
            //ログイン有効期限（デフォルトを１時間とする）
            $sesLimit = 60*60;
            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            // ユーザーIDを格納
            $_SESSION['user_id'] = $dbh->lastInsertId(); //直前でインサートしたレコードを使うことが出来る。つまり、ユーザー
            debug('セッション変数の中身：'.print_r($_SESSION,true));
            header("Location:mypage.php"); //マイページへ
          }
        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
  }
}
?>
<?php
  $siteTitle = 'ユーザー登録';
  require('head.php'); 
?>

  <body class="page-signup page-1colum">

    <!-- ヘッダー -->
    <?php
      require('header.php'); 
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- Main -->
      <section id="main" >

        <div class="form-container">

          <form action="" method="post" class="form">
            <h2 class="title">ユーザー登録</h2>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>"> <!-- キーを指定することで、値を入れれる。 -->
              Email
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>"> <!-- これはPOST送信したそのもの -->
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
              パスワード <span style="font-size:12px">※英数字６文字以上</span>
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
              パスワード（再入力）
              <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
              ?>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="登録する">
            </div>
          </form>
        </div>

      </section>

    </div>

    <!-- footer -->
    <?php
      require('footer.php'); 
    ?>
      
<!-- signupの作り方の思考手順 -->
<!-- 1 まず、全体のhtml＆cssを書く。inputはlabelで囲う。label下のdivはメッセージ表示用。
　　　　abelの一番上は、divの共通のエラー用 -->
<!-- 2 div,label,input,div、１番上のdivは共通のDBエラーを表示用のphp,divで値を保持、ボックスを
　　　　赤くするためのphp、値を保持するためのphp、エラーメッセージ格納の変数のphp、 -->
<!-- 3 POST通信があるかどうか確認して、あれば、それぞれの値を変数に代入する。 -->
<!-- 4 バリデーションチェックをする。3つの未入力→email(形式、最大文字数、重複)→
　　　　password(半角英数字、最大文字数、最小文字数)→password_re(最大文字数、
　　　　最小文字数)→password_match -->
<!-- 5 DB接続 try-cathc文で、DB接続関数、SQL文(INSERT)、データ配列→クエリ実行→クエリ成功の場合、
　　　　ログインと同じ処理を作る。headerでマイページへ→catchの共通用のを書く。-->
      
<!-- ※ バリデーションチェック・DB接続、クエリ実行関数（1回のみ）はその都度書く。-->
<!-- ※ function.phpの、ログ、デバッグ、セッション準備・セッション有効期限を延ばす、画面表示処理開始ログ吐き出し関数、定数、を順番に書く。 -->
