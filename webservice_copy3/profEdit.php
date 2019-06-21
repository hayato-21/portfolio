<?php
//共通変数・関数ファイルを読込み
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
//ログイン認証
require('auth.php');
//================================
// 画面処理
//================================
$dbFormData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($dbFormData, true));
//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST, true));
    debug('FILE情報：'.print_r($_FILE, true));
    //変数にユーザー情報を格納。
    $username = $_POST['username'];
    $tel = $_POST['tel'];
    $zip = (!empty($_POST['zip']))? $_POST['zip'] : 0; //後続のバリデーションのため。
    $addr = $_POST['addr'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    //画像をアップロードし、パスを格納する。
    $pic = (!empty($_FILES['pic']['name']))? uploadImg($_FILES['pic'], 'pic'): '';
    $pic = (empty($pic) && !empty($dbFormData['pic'])) ? $dbFormData['pic'] : $pic;
    
    //DBの情報と入力情報が異なる場合にバリデーションを行う。
    if($dbFormData['username'] !== $username){
        validMaxLen($username, 'username');
    }
    if($dbFormData['tel'] !== $tel){
        validTel($tel, 'tel');
    }
    if($dbFormData['addr'] !== $addr){
        validMaxLen($addr, 'addr');
    }
    if((int)$dbFormData['zip'] !== $zip){  //DB上では数値だが、データベースから取り出す際に、文字列のため。なぜならinputがtextだから。
        validZip($zip, 'zip');
    }
    if($dbFormData['age'] !== $age){
        validMaxLen($age, 'age');
        validNumber($age, 'age');
    }
    if($dbFormData['email'] !== $email){
        validMaxLen($email, 'email');
        if(empty($err_msg['email'])){
            validEmailDup($email);
        }
        validEmail($email, 'email');
        validRequired($email, 'email');
    }
    if(empty($err_msg)){
        debug('バリデーションOKです。');
        // 例外処理
        try{
            // DBへ接続
            $dbh = dbConnect();
            $sql = 'UPDATE users SET username = :u_name, age = :age, tel = :tel, zip = :zip, addr = :addr, email = :email, pic = :pic WHERE id = :u_id';
            $data = array(':u_name' => $username,':age' => $age, ':tel' => $tel, ':zip' => $zip, ':addr' => $addr, ':email' => $email, ':pic' => $pic, ':u_id' => $dbFormData['id']);
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                $_SESSION['msg_success'] = SUC02;
                debug('マイページへ遷移します。');
                header("Location:mypage.php"); 
            }
        }catch (Exception $e){
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'プロフィール編集';
require('head.php');
?>

<body class="page-profEdit page-2colum page-logined">

    <!-- メニュー -->
    <?php
    require('header.php');
    ?>
    
    <div id="contents" class="site-width">
      <h1 class="page-title">プロフィール編集</h1>
      <!-- Main -->
      <section id="main">
        <div class="form-container">
        <form action="" method="post" class="form" enctype="multipart/form-data">
          <div class="area-msg">
            <?php
            if(!empty($err_msg['common'])) echo $err_msg['common'];
            ?>
          </div>
          <!-- 名前 -->
          <label class="<?php if(!empty($err_msg['username'])) echo 'err'; ?>">
            名前
            <input type="text" name="username" value="<?php echo getFormData('username'); ?>">  <!-- 保存されているDBのデータを含めた入力保持 -->
          </label>
          <div class="area-msg">
            <?php
              if(!empty($err_msg['username'])) echo $err_msg['username'];
            ?>
          </div>
          <!-- TEL -->
          <label class="<?php if(!empty($err_msg['tel'])) echo 'err'; ?>">
            TEL<span style="font-size:12px;margin-left:5px;">※ハイフン無しでご入力ください</span>
            <input type="text" name="tel" value="<?php echo getFormData('tel'); ?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['tel'])) echo $err_msg['tel'];
            ?>
          </div>
          <!-- 郵便番号 -->
          <label class="<?php if(!empty($err_msg['zip'])) echo 'err'; ?>">
            郵便番号<span style="font-size:12px;margin-left:5px;">※ハイフン無しでご入力ください</span>
            <input type="text" name="zip" value="<?php if(!empty(getFormData('zip'))){echo getFormData('zip'); } ?>">  <!-- バリデーションに引っかからないように0を師弟しているため、emptyとし、こちらの入力ではデフォルト値を表示させないため。-->
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['zip'])) echo $err_msg['zip'];
            ?>
          </div>
          <!-- 住所 -->
          <label class="<?php if(!empty($err_msg['addr'])) echo 'err'; ?>">
            住所
            <input type="text" name="addr" value="<?php echo getFormData('addr'); ?>">
          </label>
          <div class="area-msg">
            <?php
              if(!empty($err_msg['addr'])) echo $err_msg['addr'];
            ?>
          </div>
          <!-- 年齢 -->
          <label style="text-align:left;" class="<?php if(!empty($err_msg['age'])) echo 'err'; ?>">
            年齢
            <input type="number" name="age" value="<?php echo getFormData('age'); ?>">
          </label>
          <div class="area-msg">
            <?php
              if(!empty($err_msg['age'])) echo $err_msg['age'];
            ?>
          </div>
          <!-- Email -->
          <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            Email
            <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
          </label>
          <div class="area-msg">
            <?php
              if(!empty($err_msg['email'])) echo $err_msg['email'];
            ?>
          </div>
          <!-- プロフィール画像 -->
          <label class="area-drop <?php if(!empty($err_msg['pic'])) echo 'err'; ?>" style="height:370px;line-height:370px;">
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input type="file" name="pic" class="input-file" style="height: 370px;">
            <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
              ドラッグ＆ドロップ<!-- 特殊文字にしなければならない。 -->
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['pic'])) echo $err_msg['pic'];
            ?>
          </div>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="変更する">
          </div>
        </form>
        </div>
      </section>
        
      <!-- サイドバー -->
      <?php
      require('sidebar.php');
      ?>
    </div>
    <!-- footer  ファイルの読み込みの位置に注意。divの中にすると -->
    <?php
      require('footer.php');
    ?>
</body>