<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');
// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
?>
<?php
$siteTitle = 'マイページ';
require('head.php'); 
?>

<body class="page-mypage page-2colum page-logined">
    <style>
      #main{
        border: none !important;
      }
    </style>
    
    <!-- メニュー -->
    <?php
      require('header.php');
    ?>
    <!-- サイドバー -->
    <?php
      require('sidebar.php');
    ?>
    <!-- footer -->
    <?php
      require('footer.php'); 
    ?>