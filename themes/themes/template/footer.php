</div>
</main>
<?php get_sidebar(); ?>

<footer id="footer">
	<!-- 特定のページに表示させるコンテンツがあれば記述 -->
	<?php if( is_page( array('固定ページのid') ) ) : ?>
	

	<?php else: ?>

	<?php endif; ?>

	<?php if ( is_home() || is_front_page() ) : ?>

	<?php else: ?>
	<?php endif; ?>

	<div id="copyright">
		<div class="inner">
		<p style="font-size:1.4rem;margin: 1rem auto 0;" class="center">この事業は、（公財）東京観光財団「観光まちづくり支援事業助成金」を活用して実施しています。</p>
		<p style="font-size:1.2rem;margin: 0.5rem auto;" class="center">This project is supported by Tokyo Convention & Visitors Bureau.</p>
		</div>
		<ul class="footer_nav flex inner">
			<li><a href="<?php echo site_url(); ?>/about/">中野区観光協会について</a></li>
			<li><a href="<?php echo site_url(); ?>/publications/">発行物一覧</a></li>
			<li><a href="<?php echo site_url(); ?>/privacy-policy/">プライバシーポリシー</a></li>
		</ul>
		<p class="center inner">（一社）中野区観光協会Anchor Tour<br>東京都知事登録 旅行業　第3-8343号<br>国内旅行業務取扱管理者　鈴木秋穂<br>Copyright&copy;<?php echo esc_html( date_i18n( __( 'Y', 'blankslate' ) ) ); ?>&nbsp; NAKANO-KU KANKOU ASSOCIATION.All Rights Reserved.</p>
	</div>
</footer>
</div>
<?php wp_footer(); ?>

<script src="<?php echo get_template_directory_uri() ?>/js/jquery-v3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/nav.js"></script>
<script>
	$(function () {
		$(".js-accordion-title").on("click", function() {
			$(this).next().slideToggle(500);
			$(this).toggleClass("open",200);
		});
	});
</script>

<script>
	$(function(){
		const hash = location.hash;
		if(hash){
			$("html, body").stop().scrollTop(0);
			setTimeout(function(){
				const target = $(hash),
					  position = target.offset().top;
				$("html, body").animate({scrollTop:position}, 500, "swing");
				target.css('margin-top',55);
			});
		}
	});
</script>

<?php if ( is_singular( array('eatery', 'facility', 'tourism', 'event-info') ) ) : ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {

		// 配列で対象のセレクタをまとめる
		var sliders = [
			{ main: ".eatery-main", thumbs: ".eatery-thumbs" }, //飲食店舗用
			{ main: ".facility-main", thumbs: ".facility-thumbs" }, //カルチャー用
			{ main: ".tourism-main", thumbs: ".tourism-thumbs" }, //観光資源用
			{ main: ".event-main",    thumbs: ".event-thumbs" } //イベント用
		];

		sliders.forEach(function(slider) {
			// サムネイル Swiper
			var thumbs = new Swiper(slider.thumbs, {
				spaceBetween: 10,
				slidesPerView: 4,
				freeMode: true,
				watchSlidesProgress: true,
			});

			// メイン Swiper
			var main = new Swiper(slider.main, {
				spaceBetween: 10,
				speed: 800,
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
				},
				navigation: {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
				},
				thumbs: {
					swiper: thumbs,
				},
				loop: true,
			});
		});

	});
</script>
<?php endif; ?>

<?php if ( is_front_page() ) : ?>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		const rssSlider = new Swiper('.rss-slider', {
			loop: true,
			speed: 800,

			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			},

			// ★ 外側に移動したので .rss-wrapper を基準にする
			navigation: {
				nextEl: '.rss-wrapper .swiper-button-next',
				prevEl: '.rss-wrapper .swiper-button-prev',
			},

			pagination: {
				el: '.rss-wrapper .swiper-pagination',
				clickable: true,
			},

			slidesPerGroup: 1,
			centeredSlides: true,

			slidesPerView: 1.05,
			spaceBetween: 20,

			breakpoints: {
				768: {
					centeredSlides: false,
					slidesPerView: 2,
					spaceBetween: 30,
				},
				1024: {
					centeredSlides: false,
					slidesPerView: 3,
					spaceBetween: 20,
				}
			}
		});
	});
</script>
<?php endif; ?>

<?php
global $post;

if (
	is_page() &&
	(
		$post->post_name === 'citywalk' ||
		$post->post_parent && get_post_field('post_name', $post->post_parent) === 'citywalk'
	)
) :
?>


<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

	// スライダー初期化を遅延
	setTimeout(() => {
		const eventSwiper = new Swiper('.event-info-swiper', {
			loop: true,
			speed: 2000,
			slidesPerView: 1,
			spaceBetween: 24,

			pagination: {
				el: '.event-info-swiper .swiper-pagination',
				clickable: true,
			},

			navigation: {
				nextEl: '.event-info-swiper .swiper-button-next',
				prevEl: '.event-info-swiper .swiper-button-prev',
			},

			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
		});
	}, 6000); // ← ここで「最初の静止時間」を作る
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const loading = document.getElementById('loading');
  if (!loading) return;

  const LIMIT_TIME = 15 * 60 * 1000; // 15分（ms）
  const now = Date.now();
  const lastTime = sessionStorage.getItem('top_loading_time');

  // 15分以内に表示済みなら即非表示
  if (lastTime && now - lastTime < LIMIT_TIME) {
    loading.style.display = 'none';
    return;
  }

  // 今回の表示時刻を保存
  sessionStorage.setItem('top_loading_time', now);

  const minTime = 4000; // 最低表示時間
  const fadeTime = 2000;

  setTimeout(() => {
    loading.classList.add('is-hide');
    setTimeout(() => {
      loading.style.display = 'none';
    }, fadeTime);
  }, minTime);
});
</script>
<script>
(function () {
  var isSafari =
    /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

  if (!isSafari) return;

  window.addEventListener('load', function () {
    setTimeout(function () {
      var select = document.querySelector('select.goog-te-combo');
      if (!select || typeof doGTranslate !== 'function') return;

      // Safari：言語選択後に1回だけリロードするためのフラグ
      var reloaded = sessionStorage.getItem('gtranslate_safari_reloaded');

      select.addEventListener('change', function () {
        if (!reloaded) {
          sessionStorage.setItem('gtranslate_safari_reloaded', '1');
          location.reload();
        }
      });

    }, 500);
  });
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const fixedElem = document.querySelector('.gt_switcher.notranslate');
  const threshold = 600; // 境界線位置(px)

  if (!fixedElem) return;

  function checkScroll() {
    if (window.scrollY > threshold) {
      fixedElem.classList.add('hidden');
    } else {
      fixedElem.classList.remove('hidden');
    }
  }

  window.addEventListener('scroll', checkScroll);
  checkScroll();
});
</script>
</body>
</html>
