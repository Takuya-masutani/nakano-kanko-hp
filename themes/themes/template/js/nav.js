$(function () {
  const ham = $('#js-hamburger');
  const nav = $('#menu');
  const hasChild = $('.has-child, .has-child_2');
  const subMenuLink = $('.sub-menu a'); // サブメニューのリンク

  // ハンバーガーメニューのクリックイベント
  ham.on('click', function (event) {
    event.preventDefault(); // デフォルトのクリックイベントを無効化
    toggleMenu();
    $('body').toggleClass('no-scroll'); // bodyタグにno-scrollクラスを付け外し
  });

  // .has-child, .has-child_2 要素のクリックイベント
  hasChild.on('click', function(event) {
    event.stopPropagation(); // イベントのバブリングを停止
    $(this).toggleClass('active'); // クリックされた要素に active クラスを付け外し
    $(this).find('.sub-menu').toggleClass('active'); // クリックされた要素内の .sub-menu に active クラスを付け外し
  });

  // サブメニューのリンクがクリックされたらサブメニューのみを閉じる
  subMenuLink.on('click', function (event) {
    event.stopPropagation(); // イベントのバブリングを停止
    $(this).closest('.sub-menu').removeClass('active');
  });

  // メニューのリンクがクリックされたらハンバーガーメニューを閉じる
  $('#menu a').on('click', function (event) {
    if (!$(this).attr('href').startsWith('#')) { // ページ内リンク以外の場合
      if (ham.hasClass('active')) {
        toggleMenu();
        $('body').removeClass('no-scroll');
      }
    }
  });

  // ハンバーガーメニューを切り替える関数
  function toggleMenu() {
    ham.toggleClass('active');
    nav.toggleClass('active');

    // ハンバーガーメニューを閉じた時に .sub-menu から active クラスを削除
    if (!ham.hasClass('active')) {
      $('.sub-menu').removeClass('active');
      hasChild.removeClass('active'); // .has-child と .has-child_2 から active クラスを削除
    }
  }
});
