<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section class="subpage-header">
	<div class="subpage-head-content inner">
		<h1><?php the_title(); ?></h1>
		<div class="breadcrumb">
			<?php if ( function_exists( 'bcn_display' ) ) bcn_display(); ?>
		</div>
	</div>
</section>

<article id="pageid-post<?php the_ID(); ?>" class="entry-content inner">
	<h2>イベント情報</h2>
	<p class="attention">当日の天候や状況に応じてイベント（スケジュール）が変更・中止となっている場合もあります。<br>詳しくはイベントサイトやSNSなどの公式情報をご確認ください。</p>

	<h3 class="event-name">
		<?php echo wp_kses_post( get_field('event-name') ); ?>
	</h3>

	<div class="event-content flex">

		<div class="event-info-cont">

			<!-- イベント概要 -->
			<?php if ( $overview = get_field('event-overview') ) : ?>
				<p class="event-overview">
					<?php echo nl2br( wp_kses_post( $overview ) ); ?>
				</p>
			<?php endif; ?>

			<div class="event-details">
				<!-- イベント開催日 -->
				<?php
				$event_day   = get_field('event-day');
				$event_hours = get_field('event-hours');

				if ( $event_day || $event_hours ) :
				?>
				<dl class="event-dates">

					<?php
					if ( $event_day ) {
						$start = $event_day['event-day-start'] ?? '';
						$end   = $event_day['event-day-end'] ?? '';

						if ( $start || $end ) :
						$start_disp = $start ? date_i18n('Y年n月j日', strtotime($start)) : '';
						$end_disp   = $end   ? date_i18n('Y年n月j日', strtotime($end))   : '';
					?>
					<dt>イベント期間</dt>
					<dd class="event-duration">
						<?php if ( $start && $end && $start === $end ) : ?>
						<span class="event-start"><?php echo esc_html($start_disp); ?></span>
						<?php else : ?>
						<?php if ( $start_disp ) : ?>
						<span class="event-start"><?php echo esc_html($start_disp); ?></span>
						<?php endif; ?>
						<?php if ( $start_disp && $end_disp ) : ?> ～ <?php endif; ?>
						<?php if ( $end_disp ) : ?>
						<span class="event-end"><?php echo esc_html($end_disp); ?></span>
						<?php endif; ?>
						<?php endif; ?>
						
						<?php if ( get_field('event-day-attention') ) : ?>
						<span class="event-attention">※開催日については詳細を確認してください</span>
						<?php endif; ?>

					</dd>
					<?php endif; } ?>

					<?php if ( $event_hours ) : ?>
					<dt>イベント時間</dt>
					<dd class="hours"><?php echo nl2br( wp_kses_post( $event_hours ) ); ?></dd>
					<?php endif; ?>

				</dl>
				<?php endif; ?>

				<!-- ウェブサイト -->
				<?php if ( $event_website = get_field('event-website') ) : ?>
				<dl class="event-website">
					<dt>ウェブサイト</dt>
					<dd class="site-url">
						<a class="externalLink" href="<?php echo esc_url($event_website); ?>" target="_blank" rel="noopener">イベントの公式サイトはこちらから</a>
					</dd>
				</dl>
				<?php endif; ?>

				<!-- SNS -->
				<?php
				$sns_fields = array(
					'instagram' => 'Instagram',
					'facebook'  => 'Facebook',
					'x_twitter' => 'X',
					'line'      => 'LINE'
				);

				$has_sns = false;
				foreach ( $sns_fields as $field => $label ) {
					if ( get_field($field) ) {
						$has_sns = true;
						break;
					}
				}

				if ( $has_sns ) :
				?>
				<dl class="event-sns">
					<dt>SNS</dt>
					<dd class="sns">
						<ul class="flex">
							<?php foreach ( $sns_fields as $field => $label ) :
							$url = get_field($field);
							if ( ! $url ) continue;
							?>
							<li class="<?php echo esc_attr($field); ?>">
								<a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
									<img src="<?php echo esc_url( wp_get_upload_dir()['baseurl'] . '/icon-' . $field . '-logo.svg' ); ?>"
										 alt="<?php echo esc_attr($label); ?>のロゴ">
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</dd>
				</dl>
				<?php endif; ?>

				<!-- 会場＋マップ -->
				<?php
				$event_location = get_field('venue');
				$event_map      = get_field('venuemap');

				if ( $event_location || $event_map ) :
				?>
				<dl class="event-venue">
					<?php if ( $event_location ) : ?>
					<dt>会場</dt>
					<dd class="location">

						<!-- エリア -->
						<?php
						$areas = get_the_terms( get_the_ID(), 'area' );

						$area_names = array(
							'north-area'         => '中野駅北口',
							'south-area'         => '中野駅南口',
							'yayoicho-minamidai' => '弥生町/南台',
							'shin-shinbashi'     => '新中野/中野新橋',
							'araiyakushi-ekoda'  => '新井薬師/江古田',
							'higashi-sakaue'     => '東中野/中野坂上',
							'nogata-saginomiya'  => '野方/鷺ノ宮'
						);

						if ( $areas && ! is_wp_error( $areas ) ) :
						?>
						<ul class="location-area flex">
							<?php foreach ( $areas as $area ) : ?>
							<li class="<?php echo esc_attr( $area->slug ); ?>">
								<?php
								echo esc_html(
									$area_names[ $area->slug ] ?? $area->name
								);
								?>
								&nbsp;エリア
							</li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>

						<?php echo esc_html($event_location); ?>

						<?php if ( $event_map ) : ?>
						<div class="googlemap">
							<?php echo $event_map; ?>
						</div>
						<?php endif; ?>
					</dd>
					<?php endif; ?>
				</dl>
				<?php endif; ?>
			</div>
		</div>

		<!-- メインスライダー -->
		<?php
		$images = [];
		for ($i = 1; $i <= 5; $i++) {
			$field = get_field("event-images0{$i}");
			if ($field) $images[] = $field;
		}
		?>
		<?php if (!empty($images)): ?>
		<div class="event-slider">
			<div class="swiper event-main">
				<div class="swiper-wrapper">
					<?php foreach ($images as $img): ?>
					<div class="swiper-slide">
						<img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- スライダーサムネイル -->
			<div class="swiper event-thumbs">
				<div class="swiper-wrapper">
					<?php foreach ($images as $img): ?>
					<div class="swiper-slide">
						<img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

	</div>

	<?php
	$more_info = get_field('event-more-information');

	if ( $more_info ) :

		// ① HTML許可（iframe対応版）
		$allowed_html = wp_kses_allowed_html( 'post' );

		$allowed_html['iframe'] = [
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'allow'           => true,
			'allowfullscreen' => true,
			'loading'         => true,
			'referrerpolicy'  => true,
		];

		$more_info = wp_kses( $more_info, $allowed_html );

		// ② img / picture を figure で囲う
		$more_info = preg_replace(
			'/(<(?:img|picture)[^>]*>.*?<\/picture>|<img[^>]+>)/is',
			'<figure>$1</figure>',
			$more_info
		);

		// ③ wpautop（brは有効、pは制御）
		$more_info = wpautop( $more_info, false );

		// ④ 完全に空の p を削除
		$more_info = preg_replace(
			'#<p>(?:\s|&nbsp;|<br\s*/?>)*</p>#i',
			'',
			$more_info
		);

		// ⑤ figure の直前・直後にある空pを削除
		$more_info = preg_replace(
			'#</figure>\s*<p>(?:\s|&nbsp;|<br\s*/?>)*</p>#i',
			'</figure>',
			$more_info
		);

		$more_info = preg_replace(
			'#<p>(?:\s|&nbsp;|<br\s*/?>)*</p>\s*<figure>#i',
			'<figure>',
			$more_info
		);

		// ⑥ 前後トリム
		$more_info = trim( $more_info );
		?>

		<div class="more-information flex">
			<h4>その他 詳細情報</h4>
			<?php echo $more_info; ?>
		</div>

	<?php endif; ?>




</article>

<?php endwhile; endif; ?>
<?php get_footer(); ?>