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
    validRequired($email, 'email');
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
      //パスワードの最小文字列チェック
      validMinLen($pass, 'pass');
      //パスワード（再入力）の最大文字数チェック
      validMaxLen($pass_re, 'pass_re');
      //パスワード（再入力）の最小文字数チェック
      validMinLen($pass_re, 'pass_re');
      if(empty($err_msg)){
        try{
            $dbh = dbConnect();
            $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
            $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':login_time' => date('Y-m-d H:i:s'), ':create_date' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            // クエリ成功の場合、すぶにログインを行う。
            if($stmt){
                $seeLimit = 60*60;
                $_SESSION['login_date'] = time();
                $_SESSION['login_limit'] = $seeLimit;
                $_SESSION['user_id'] = $dbh->lastInsertId();
                debug('セッション変数の中身：'.print_r($_SESSION,true));
                header("Location:mypage.php");
            }
        }catch (Exception $e){
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
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
      <section id="main">
        
        <div class="form-container">
          
          <form action="" method="post" class="form">
            <h2 class="title">ユーザー登録</h2>
            
            <!-- 共通のエラーメッセージ -->
            <div class="area-msg">
              <?php
                if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <!-- Email --> 
            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>"> <!-- エラー発生時に、枠内を赤くするためのクラス-->
              Email
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>"> <!-- 入力保持 -->
            </label>
            <div class="area-msg">  <!-- 各エラーメッセージ表示用 -->
              <?php
                if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </div>
              
            <!-- パスワード -->
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>"> <!-- エラー発生時に、枠内を赤くするためのクラス-->
              パスワード
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>"> <!-- 入力保持 -->
            </label>
            <div class="area-msg">  <!-- 各エラーメッセージ表示用 -->
              <?php
                if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </div>
            
            <!-- パスワード再入力 -->
            <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>"> <!-- エラー発生時に、枠内を赤くするためのクラス-->
              パスワード（再入力）    
              <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>"> <!-- 入力保持 -->
            </label>
            <div class="area-msg">  <!-- 各エラーメッセージ表示用 -->
              <?php
                if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
              ?>
            </div>
            <!-- 送信ボタン -->
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="登録する">
            </div>
          </form>
        </div>
      </section>
        
      <!-- footer -->
    </div>
    <?php
        require('footer.php');
      ?>
  </body>