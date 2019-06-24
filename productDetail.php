<?php
$siteTitle = '商品詳細';
require('head.php'); 
?>

  <body class="page-productDetail page-1colum">
    <style>
      .badge{
        padding: 5px 10px;
        color: white;
        background: #7acee6;
        margin-right: 10px;
        font-size: 16px;
        vertical-align: middle;
        position: relative;
        top: -4px;
      }
      #main .title{
        font-size: 28px;
        padding: 10px 0;
      }
      .product-img-container{
        overflow: hidden;
      }
      .product-img-container img{
        width: 100%;
      }
      .product-img-container .img-main{
        width: 750px;
        float: left;
      }
      .product-img-container .img-sub{
        width: 230px;
        float: left;
        background: #f6f5f4;
        padding: 15px;
        box-sizing: border-box;
      }
      .product-img-container .img-sub img{
        margin-bottom: 15px;
      }
      .product-img-container .img-sub img:last-child{
        margin-bottom: 0;
      }
      .product-detail{
        background: #f6f5f4;
        padding: 15px;
        margin-top: 15px;
        min-height: 150px;
      }
      .product-buy{
        overflow: hidden;
        margin-top: 15px;
        margin-bottom: 50px;
        height: 50px;
        line-height: 50px;
      }
      .product-buy .item-left{
        float: left;
      }
      .product-buy .item-right{
        float: right;
      }
      .product-buy .price{
        font-size: 32px;
        margin-right: 30px;
      }
      .product-buy .btn{
        border: none;
        font-size: 18px;
        padding: 10px 30px;
      }
      .product-buy .btn:hover{
        cursor: pointer;
      }
    </style>

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

        <div class="title">
          <span class="badge">スマホ</span>
          iPhone6s
        </div>
        <div class="product-img-container">
          <div class="img-main">
            <img src="/img/sample01.jpg" alt="">
          </div>
          <div class="img-sub">
            <img src="/img/sample02.jpg" alt="">
            <img src="/img/sample03.gif" alt="">
            <img src="/img/sample04.jpg" alt="">
          </div>
        </div>
        <div class="product-detail">
          <p>
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
          </p>
        </div>
        <div class="product-buy">
          <div class="item-left">
            <a href="index.html">&lt; 商品一覧に戻る</a>
          </div>
          <div class="item-right">
            <button class="btn btn-primary">買う!</button>
          </div>
          <div class="item-right">
            <p class="price">¥85,000-</p>
          </div>
        </div>

      </section>

    </div>

    <!-- footer -->
    <?php
    require('footer.php'); 
    ?>
