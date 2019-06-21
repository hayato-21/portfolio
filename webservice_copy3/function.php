<?php
//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');
//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバックログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str); //ユーザー側の混乱を避けるため、開発するときのみだけtrueにする
    }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
//session_save_path("/var/tmp/"); xmampでは、以下のサイトのように設定されている。デフォルトで24日間削除されないところにある。https://ymyk.wordpress.com/
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime', 60*60*24*39);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();
//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){  // 先ほど定義したdebug関数を用いて。
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID：'.session_id());
  debug('セッション変数の中身：'.print_r($_SESSION,true)); 
  debug('現在日時タイムスタンプ：'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
      debug('ログイン期限日時タイムスタンプ：'.($_SESSION['login_date'] + $_SESSION['login_limit'] ));
  }
}
//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG10', '電話番号の形式が違います');
define('MSG11', '郵便番号の形式が違います');
define('MSG12', '古いパスワードが違います');
define('MSG13', '古いパスワードと同じです');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('MSG17', '半角数字のみご利用いただけます');
define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');
//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$err_msg = array();
//================================
// バリデーション関数
//================================
//バリデーション関数（未入力チェック）
function validRequired($str, $key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
//バリデーション関数（Emailの形式チェック）
function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
//バリデーション関数（Email重複チェック）  //todo
function validEmailDup($email){
    global $err_msg;
    try{
      //DBへ接続
      $dbh = dbConnect();
      //SQL文の作成 
      $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
      //データ
      $data = array(':email' => $email);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      //クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      //array_shiftを用いて、クエリ結果（配列）から取り出す。
      if(!empty(array_shift($result))){
          $err_msg['email'] = MSG08;
      }
    }catch(Exception $e){
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
//バリデーション関数（同値チェック）
function validMatch($str1, $str2, $key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
//バリデーション関数（最小文字数チェック）
function validMinLen($str, $key, $min = 6){ //引数が渡されていなくても、設定し使うことができる
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
//バリデーション関数（最大も指数チェック）
function validMaxLen($str, $key, $max = 256){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
//バリデーション関数（半角チェック）
function validHalf($str, $key){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
//電話番号形式チェック
function validTel($str, $key){
    if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}
//郵便番号形式チェック
function validZip($str, $key){
    if(!preg_match("/^\d{7}$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG11;
    }
}
//半角数字チェック
function validNumber($str, $key){
    if(!preg_match("/^[0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG17;
    }
}
//固定長チェック
function validLength($str, $key, $len = 8){
    if(mb_strlen($str) !== $len){
        global $err_msg;
        $err_msg[$key] = $len . MSG14;
    }
}
//パスワードチェック
function validPass($str, $key){
    validHalf($str, $key);
    validMaxLen($str, $key);
    validMinLen($str, $key);
}
//selectboxチェック
function validSelect($str, $key){
    if(!preg_match("/^[0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG15;
    }
}
//エラーメッセージ表示
function getErrMsg($key){
    global $err_msg;
    if(!empty($err_msg[$key])){
        return $err_msg[$key];
    }
}
//================================
// データベース
//================================
//DB接続関数
function dbConnect(){
    $dsn = 'mysql:dbname=freemarket;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'mysql'; //インターノウスでは、
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}
function queryPost($dbh, $sql, $data){
    $stmt = $dbh->prepare($sql);
    if(!$stmt->execute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL：'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
    }
    debug('クエリ成功。');
    return $stmt;
}
function getUser($u_id){  //基本的にセッションのIDが入る。
    debug('ユーザー情報を取得します。');
    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';  //u=idの:がなかった。u_idが、u=idになっていた。
        $data = array(':u_id' => $u_id);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
          return $stmt->fetch(PDO::FETCH_ASSOC); //userの情報を一行取得したいので。全体のreturnの役割もある。
        }else{
          return false;
        }
    }catch (Exception $e){
        error_log('エラー発生：'. $e->getMessage());
    }
    //  return $stmt->fetch(PDO::FETCH_ASSOC);これでも結果は変わらない。
}
//ここから下は、商品登録用
function getProduct($u_id, $p_id){
    debug('商品情報を取得します。');
    debug('ユーザーID：'.$u_id);
    debug('商品ID：'.$p_id);
    //例外処理
    try{
      //DBへ接続
      $dbh = dbConnect();
      //SQL文の作成
      $sql = 'SELECT * FROM product WHERE user_id = :u_id AND id = :p_id AND delete_flg = 0';
      $data = array(':u_id' => $u_id, ':p_id' => $p_id);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        // クエリ結果のデータを1レコード返却
        return $stmt->fetch(PDO::FETCH_ASSOC);
      }else{
        return false;
      }
      }catch (Exception $e){
          error_log('エラー発生：'.$e->getMessage());
    }
}
//getProductList
function getProductList($currentMinNum = 1, $category, $sort, $span = 20){  //何も無ければ、$currentMinNum=1とはどのゆうことか？ここには、0,20,40が入るので。
  debug('商品情報を取得します。');
  //例外処理
  try{
    $dbh = dbConnect();
    // 件数用のSQL文の作成  //件数の表示なので、Idのみ。
    $sql = 'SELECT id FROM product';
    //' WHERE 'の先頭の半角を忘れない。
    if(!empty($category)) $sql .= ' WHERE category_id = '.$category; //カテゴリー検索
    if(!empty($sort)){
      switch($sort){
          case 1:
              $sql .= ' ORDER BY price ASC'; //安い順
              break;
          case 2:
              $sql .= ' ORDER BY price DESC'; //高い順
              break;
      }
    }
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount(); //総レコード数
    $rst['total_page'] = ceil($rst['total']/$span); //総ページ数。ceilで切り上げ。
      
    if(!stmt){
        return false;
    }
      
    // ページング用のSQL文作成
      $sql = 'SELECT * FROM product';
      if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
      if(!empty($sort)){
          switch($sort){
            case 1:
              $sql .= ' ORDER BY price ASC';
              break;
            case 2:
              $sql .= ' ORDER BY price DESC';
              break;
          }
      }
      $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum; //0から、20件、20から40件。
      $data = array();
      debug('SQL:' .$sql);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
          $rst['data'] = $stmt->fetchAll();
          return $rst;
      }else{
          return false;
      }
  } catch (Exception $e){
      error_log('エラー発生:' . $e->getMessage());
  }
}
//getProductOne
function getProductOne($p_id){
    debug('商品情報を取得します。');
    debug('商品ID：'.$p_id);
    //例外処理
    try{
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT p.id, p.name, p.comment, p.price, p.pic1, p.pic2, p.pic3, p.user_id, p.create_date, p.update_date, c.name AS category FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
      $data = array(':p_id' => $p_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
          return $stmt->fetch(PDO::FETCH_ASSOC);
      }else{
          return false;
      }
    }catch (Exception $e){
        error_log('エラー発生：'. $e->getMessage());
    }
}

//getMyProducts
//getMsgAndBord
function getMsgsAndBord($id){
  debug('msg情報を取得します。');
  debug('掲示板ID:'.$id);
  //例外処理
  try{
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT m.id AS m_id, product_id, bord_id, send_date, to_user, from_user, sale_user, buy_user, msg, b.create_date FROM message AS m RIGHT JOIN bord AS b ON b.id = m.bord_id WHERE b.id = :id ORDER BY send_date ASC'; //m.delete_flg を消すと表示された。b,id=:idの1つの条件であれば、表示できるが？さらにdelete_flgの条件を追加すると、とれなくる？
    // RIGHT JOINできれば、bord側の情報を取ることが出来るので、表示されるはず。そもそもマッチするはず、message側にIDが存在しないことが問題。
    $data = array(':id' => $id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      // クエリ結果の全データを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch (Exception $e){
      error_log('エラー発生:' . $e->getMessage());
  }
 
}
//getMyMsgsAndBord
function getCategory(){
    debug('カテゴリー情報を取得します。');
    //例外処理
    try{
      // DB接続
      $dbh = dbConnect();
      // SQL文作成
     $sql = 'SELECT * FROM category';
     $data = array();
     // クエリ実行
     $stmt = queryPost($dbh, $sql, $data);
     if($stmt){
       // クエリ結果の全データを返却
       return $stmt->fetchAll();
     }else{
       return false;
     }
    }catch (Exception $e){
      error_log('エラー発生：' . $e->getMessage());
    }
}
function isLike($u_id, $p_id){
    debug('お気に入り情報があるか確認します。');
    debug('ユーザーID：'.$u_id);
    debug('商品ID:'.$p_id);
    //例外処理
    try{
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
      //likeは予約語は、バックで囲う。
      $data = array(':u_id' => $u_id, ':p_id' => $p_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt->rowCount()){
          debug('お気に入りです');
          return true;
      }else{
          debug('特に気に入ってません');
          return false;
      }
    }catch (Exception $e){
        error_log('エラー発生：'. $e->getMessage());
    }
}
//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment){
    if(!empty($to) && !empty($subject) && !empty($comment)){
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        
        $result = mb_send_mail($to, $subject, $comment, "From: ".$from);
        if($result){
            debug('メールを送信しました。');
        }else{
            debug('【エラー発生】メールの送信に失敗しました。');
        }
    }
}

//================================
// その他
//================================
// サニタイズ
function sanitize($str){
  return htmlspecialchars($str,ENT_QUOTES);
}
// フォーム入力保持
function getFormData($str,$flg = false){
    if($flg){
        $method = $_GET;
    }else{
        $method = $_POST;
    }
    global $dbFormData;  //formがfromになっていた。snanitizeがsainitizeになっていた。そして、データベースが話しかける。tellになっていた。
    if(!empty($dbFormData)){  //DBの情報がある場合。
        if(!empty($err_msg[$str])){
            if(isset($method[$str])){ //o円の場合もあるので、issetを用いる。
                return sanitize($method[$str]);
            }else{
                return sanitize($dbFormData[$str]);  //POST送信がない場合は、この関数の一番初めの制御構文で確認ずみのため、基本的にはありえないが、その場合はDBの情報を返す
            }
        }else{ //POST通信があるが、DBの情報が異なる場合。入力した情報を表示させたいから。
            if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
                return sanitize($method[$str]);
            }else{
                return sanitize($dbFormData[$str]);
            }
        }
    }else{  //ない場合
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}
//sessionを１回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}
// 認証キー生成
function makeRandKey($length = 8){
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    // 静的変数。returnされた後も変わらない。
    $str = '';
    for ($i = 0; $i < $length; ++$i){
        $str .= $chars[mt_rand(0, 61)];
    }
    return $str;
}
// 画像処理
function uploadImg($file, $key){
    debug('画像アップロード処理開始');
    debug('FILE情報：'.print_r($file, true));
    
    if(isset($file['error']) && is_int($file['error'])){ // $_FILEの中には、name,type,tmp_name,error,sizeのkeyが入っており、errorのvalueは、エラーが無ければ0を返し、ファイルが選択されていないと、4を返す。つまり、数値でファイルの状態？区別している。
      try{
          switch($file['error']){
              case UPLOAD_ERR_OK: // OK
                break;
              case UPLOAD_ERR_NO_FILE:  //FILEが未選択の場合。
                throw new RuntimeException('ファイルが選択されていません');
              case UPLOAD_ERR_INI_SIZE:  //php.ini 設定のフォーム定義
              case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('ファイルサイズが大きすぎます');
              default:
                throw new RuntimeException('その他のエラーが発生しました');
                
          }
          
          //$file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを事前でチェックする。
          //@exif_imagetypeでIMAGETYPE_GIFやJPEGなどの値を返す。
          $type = @exif_imagetype($file['tmp_name']);
          if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
              throw new RuntimeException('画像形式が未対応です');
          }
          
          //ハッシュ化して保存しないと、同じファイル名の可能性があり、DBに登録する際に、どちらの写真か判断がつかない可能性がある。
          // image_type_to_extension関数で拡張子を取得する。sha1は１であることに気をつける。
          $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
          if(!move_uploaded_file($file['tmp_name'], $path)){
              throw new RuntimeException('ファイル保存時にエラーが発生しました。');
          }
          
          chmod($path, 0644);
          
          debug('ファイルは正常にアップロードされました');
          debug('ファイルパス：'.$path);
          return $path;
      } catch (RuntimeException $e){
          debug($e->getMessage());
          global $err_msg;
          $err_msg[$key] = $e->getMessage();
          
      }
    }
}
//ページング
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク 
// $pageColNum : ページネーション表示数
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
    // 現在のページが、総ページと同じ　かつ　総ページ数が表示項目以上なら、左にリンクを4個出す
    if($currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum -4;
        $maxPageNum = $currentPageNum;
    // 現在のページが、総ページの1ページ前なら、左にリンク3個、右に1個出す
    }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum -3;
        $maxPageNum = $currentPageNum +1;
    // 現ページが2の場合は、左に1個、右にリンクを3個出す
    }elseif( $currentPageNum == 2 && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum -1;
        $maxPageNum = $currentPageNum +3;
    // 現ページが1の場合は、左に何も出さない。右に5個出す。
    }elseif( $currentPageNum == 1 && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
    // 総ページ数が表示項目より少ない場合は、総ページ数をループのMax、ループのMinを1に設定
    }elseif( $totalPageNum < $pageColNum){
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
    // それ以外は左に2個出す。
    }else{
        $minPageNum = $currentPageNum -2;
        $maxPageNum = $currentPageNum +2;
    }
    //phpのファイルなので、echoを使う。
    echo '<div class="pagination">';
      echo '<ul class="pagination-list">';
        if($currentPageNum != 1){ //もし、$linkを外すと、は検索用か？。
            echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;&lt;</a></li>'; //先頭に戻る
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
            echo '<li class="list-item';
            if($currentPageNum == $i){ echo 'active'; }
            echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>'; //これでGET通信で取れる
        }
        if($currentPageNum != $maxPageNum && $maxPageNum > 1){ //1以上でかつ、一番最後のページではない
            echo '<li class="list-item">< a href="?p='.$maxPageNum.$link.'">&gt;$gt;</a></li>';
        }
    
      echo '</ul>';
    echo '</div>';
}
//画像表示用関数
function showImg($path){
    if(empty($path)){
        return 'img/sample-img.png';
    }else{
        return $path;
    }
}
//GETパラメータ付与。（詳細から、一覧に戻るときは）
// $del_key : 付与から取り除きたいGETパラメータのキー
function appendGetParam($arr_del_key = array()){
    if(!empty($_GET)){
      $str = '?';
      foreach($_GET as $key => $val){
        if(!in_array($key, $arr_del_key, true)){
            $str .= $key.'='.$val.'&';  //?p=3だったら、詳細ページいく前に付与。&も検索用か？
        }
      }
      $str = mb_substr($str, 0, -1, "UTF-8");
      return $str; //最後の&を消す。h
    }
}