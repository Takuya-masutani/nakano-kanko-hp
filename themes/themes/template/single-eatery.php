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
	<h2>お店の情報</h2>
	<div class="eatery-content flex">
		<!-- メインスライダー -->
		<?php
		$images = [];
		for ($i = 1; $i <= 5; $i++) {
			$field = get_field("eatery-images0{$i}");
			if ($field) $images[] = $field;
		}
		?>
		<?php if (!empty($images)): ?>
		<div class="eatery-slider">
			<div class="swiper eatery-main">
				<div class="swiper-wrapper">
					<?php foreach ($images as $img): ?>
					<div class="swiper-slide">
						<img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- スライダーサムネイル -->
			<div class="swiper eatery-thumbs">
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

		<div class="eatery-info">

			<!-- エリア -->
			<?php
			$terms = get_the_terms(get_the_ID(), 'area');

			if ($terms && !is_wp_error($terms)) :
			?>
			<ul class="location-area flex">
				<?php foreach ($terms as $term) : ?>
				<li class="<?php echo esc_attr($term->slug); ?>">
					<?php echo esc_html($term->name); ?> エリア
				</li>
				<?php endforeach; ?>
			</ul>

			<?php endif; ?>

			<h3><?php the_title(); ?></h3>
			<dl>
				<!-- 営業時間 -->
				<?php if ( $eatery_hours = get_field('eatery-hours') ) : ?>
				<dt class="hours">営業時間</dt>
				<dd class="hours"><?php echo esc_html($eatery_hours); ?></dd>
				<?php endif; ?>

				<!-- 定休日 -->
				<?php if ( $holidays = get_field('eatery-holidays') ) :
				$day_names = array(
					'mon' => '月曜日', 'tue' => '火曜日', 'wed' => '水曜日',
					'thu' => '木曜日', 'fri' => '金曜日', 'sat' => '土曜日',
					'sun' => '日曜日', 'holiday' => '祝日', 'random' => '不定休',
					'norest' => 'なし'
				);
				?>
				<dt class="holidays">定休日</dt>
				<dd class="holidays">
					<ul class="flex">
						<?php foreach ( $holidays as $day ): ?>
						<li class="<?php echo esc_attr($day); ?>">
							<?php echo esc_html($day_names[$day] ?? $day); ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</dd>
				<?php endif; ?>

				<!-- 住所 -->
				<?php if ( $eatery_location = get_field('eatery-location') ) : ?>
				<dt class="location">住所</dt>
				<dd class="location"><?php echo esc_html($eatery_location); ?></dd>
				<?php endif; ?>

				<!-- Google Map -->
				<?php if ( $eatery_map = get_field('eatery-googlemap') ) : ?>
				<div class="googlemap">
					<?php echo $eatery_map; // そのまま出力 ?>
				</div>
				<?php endif; ?>

			</dl>
		</div>
	</div>
	<!-- コメント -->
	<?php if ( $eatery_comment = get_field('eatery-comment') ) : ?>
	<div class="comment">
		<p><?php echo wp_kses_post( nl2br( esc_html( $eatery_comment ) ) ); ?></p>
	</div>
	<?php endif; ?>

	<!-- ウェブサイト -->
	<?php if ( $eatery_website = get_field('eatery-website') ) : ?>
	<dl class="eatery-info w100">
		<dt class="website">ウェブサイト</dt>
		<dd class="website">
			<a class="externalLink" href="<?php echo esc_url($eatery_website); ?>" target="_blank" rel="noopener">
				<?php echo esc_html( get_the_title() ); ?>&nbsp;のウェブサイトへ行く
			</a>
		</dd>
	</dl>
	<?php endif; ?>

	<!-- SNS -->
	<?php
	$sns_fields = array(
		'instagram' => 'Instagram',
		'facebook' => 'Facebook',
		'x_twitter' => 'X',
		'line' => 'LINE'
	);
	$has_sns = false;
	foreach ( $sns_fields as $field_name => $label ) {
		if ( get_field($field_name) ) {
			$has_sns = true;
			break;
		}
	}
	if ( $has_sns ):
	?>
	<dl class="eatery-info w100">
		<dt class="sns">SNS</dt>
		<dd class="sns">
			<ul class="flex">
				<?php foreach ( $sns_fields as $field_name => $label ):
				$sns_url = get_field($field_name);
				if ( $sns_url ):
				?>
				<li class="<?php echo esc_attr($field_name); ?>">
					<a href="<?php echo esc_url($sns_url); ?>" target="_blank" rel="noopener">
						<figure>
							<img src="<?php echo esc_url(wp_get_upload_dir()['baseurl'] . '/icon-' . $field_name . '-logo.svg'); ?>" 
								 alt="<?php echo esc_attr($label); ?>のロゴ">
						</figure>
					</a>
				</li>
				<?php endif; endforeach; ?>
			</ul>
		</dd>
	</dl>
	<?php endif; ?>

	<div class="other-info">
		<?php the_content(); ?>
	</div>
</article>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
