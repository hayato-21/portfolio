<footer id="footer">
  Copyright <a href="http://webukatu.com/">ウェブカツ!!WEBサービス部</a>.All Rights Reserved.
</footer>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
      <link rel="stylesheet" type="text/css" href="style.css">
      <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<script>
  $(function(){
      //フッターを最下部に固定。もし、画面の内容が少なければ、
      var $ftr = $('#footer');
      if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight()){
          $ftr.attr({'style':'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'});
      }
      // メッセージ表示
      var $jsShowMsg = $('#js-show-msg');
      var msg = $jsShowMsg.text();
      if(msg.replace(/^[\s　]+[\s　]+$/g,"").length){
          $jsShowMsg.slideToggle('slow');
          setTimeout(function(){$jsShowMsg.slideToggle('slow');}, 5000);
      }
      
      // 画像ライブプレビュー
      var $dropArea = $('.area-drop');
      var $fileInput = $('.input-file');
      $dropArea.on('dragover', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', '3px #ccc dashed');
      });
      $dropArea.on('dragleave', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', 'none');
      });
      $fileInput.on('change', function(e){
          $dropArea.css('border', 'none');
          var file = this.files[0], //2.files配列にファイルが入っています。
              
          $img = $(this).siblings('.prev-img'), //3. jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();  // 4. ファイルを読み込むFileReaderオブジェクト
          
          //5 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
          fileReader.onload = function(event){
            // 読み込んだデータをimgに設定
            $img.attr('src', event.target.result).show();
          };
          // 6. 画像の読み込み
          fileReader.readAsDataURL(file);  //画像をURLとして読み込む
      });
      
      // テキストエリアカウント
      var $countUp = $('#js-count'),
          $countView = $('#js-count-view');
      $countUp.on('keyup', function(e){
          $countView.html($(this).val().length);
      });
      
      // 画像切り替え
      var $switchImgSubs = $('.js-switch-img-sub'),
          $switchImgMain = $('#js-switch-img-main');
      $switchImgSubs.on('click', function(e){
          $switchImgMain.attr('src', $(this).attr('src'));
      });
      
      // お気に入り登録・削除
      var $like,likeProductId;
      $like = $('.js-click-like') || null;
      likeProductId = $like.data('productid') || null;
  if(likeProductId !== undefined && likeProductId !== null){
      $like.on('click', function(){
        var $this = $(this);
        $.ajax({
            type: "POST", //ここで、POST通信を設定してあげているのか。アイコン押すだけじゃ通信出来ないよね
            url: "ajaxLike.php",
            data: {productId : likeProductId} //自分で指定したデータ型
        }),done(function(data){
            console.log('Ajax Success');
            // クラス属性をtoggleで付け外しする
            $this.toggleClass('active');
        }).fail(function(msg){
            console.log('Ajax Error');
        });
      });
  }
      
  });
</script>
</body>
</html>