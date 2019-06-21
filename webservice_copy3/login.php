<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

if(!empty($_POST)){
    debug('POST通信があります。');
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true: false;
    
    validEmail($email, 'email');
    validMaxLen($email, 'email');
    validHalf($pass, 'pass');
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    if(empty($err_msg)){
        debug('バリデーションOKです。');
        
        try{
            $dbh = dbConnect();
            $sql = 'SELECT password,id FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            debug('クエリ結果の中身：'.print_r($result, true));
            
            if(!empty($result) && password_verify($pass, array_shift($result))){
                debug('パスワードがマッチしました。');
                $seeLimit = 60 * 60;
                $_SESSION['login_date'] = time();
                if($pass_save){
                    debug('ログイン保持にチェックがあります。');
                    $_SESSION['login_limit'] = $seeLimit * 24 * 30;
                }else{
                    debug('ログイン保持にチェックはありません。');
                    $_SESSION['login_limit'] = $seeLimit;
                }
                $_SESSION['user_id'] = $result['id'];
                debug('セッション変数の中身：'.print_r($_SESSION, true));
                debug('マイページへ遷移します。');
                header("Location:mypage.php");
            }else{
                debug('パスワードがマッチしません。');
                $err_msg['common'] = MSG09;
            }
        }catch (Exception $e){
            error_log('エラー発生：'. $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'ログイン';
require('head.php');
?>

  <body class="page-login page-1colum">
      
    <!-- ヘッダー -->
    <?php
      require('header.php');
    ?>
    <p id="js-show-msg" style="display: none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?> <!-- 関数を定義していないのに、出そうとしているから。下のが表示されない。-->
    </p>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main">
        <div class="form-container">
            
          <form action="" method="post" class="form">
          <h2 class="title">ログイン</h2>
          <!-- 共通のエラーメッセージ -->
            <div class="area-msg">
              <?php
                if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
              
            <!-- Email -->
            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>"> <!-- エラー発生時に、枠内を赤くするためのクラス-->
              メールアドレス
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
            <!-- 次回のログインの省略 -->
            <label>
              <input type="checkbox" name="pass_save">次回のログインを省略する
            </label>
            <!-- 送信ボタン -->
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="登録する">
            </div>
              パスワードを忘れた方は<a href="passRemindSend.php">コチラ</a>
          </form>
        </div>
      </section>
        
    </div>
    
    <?php
    require('footer.php');
    ?>
  </body>