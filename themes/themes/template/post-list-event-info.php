<?php
/**
 * Event info slider output
 * Shortcode 用パーツ
 */
$args = array(
	'post_type'      => 'event-info', // 投稿タイプ
	'posts_per_page' => 10, // 最大10件
	'post_status'    => 'publish',
	'has_password'   => false,
);

$event_query = new WP_Query($args);

if ( ! $event_query->have_posts() ) {
	return;
}
?>

<div class="swiper event-info-swiper">
	<h2>イベント詳細</h2>
	<ul class="swiper-wrapper">

		<?php while ( $event_query->have_posts() ) : $event_query->the_post(); ?>
		<li class="swiper-slide">
			<article>
				<div>
					<figure class="only_pc">
						<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'full' ); ?>
						<?php else : ?>
						<?php
						$default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg';
						?>
						<img src="<?php echo esc_url( $default_img ); ?>" alt="デフォルト画像">
						<?php endif; ?>
					</figure>

					<div class="post-info">
						<h3 class="post-title"><?php the_title(); ?></h3>
						<figure class="only_sp">
							<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'full' ); ?>
							<?php else : ?>
							<?php
							$default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg';
							?>
							<img src="<?php echo esc_url( $default_img ); ?>" alt="デフォルト画像">
							<?php endif; ?>
						</figure>

						<!-- イベント名 -->
						<p class="event-name">
							<?php echo wp_kses_post( get_field('event-name') ); ?>
						</p>

						<!-- イベント開催日 -->
						<?php
						$event_day   = get_field('event-day');
						$event_hours = get_field('event-hours');

						if ( $event_day || $event_hours ) :
						?>
						<dl>

							<?php
							if ( $event_day ) {
								$start = $event_day['event-day-start'] ?? '';
								$end   = $event_day['event-day-end'] ?? '';

								if ( $start || $end ) :
								$start_disp = $start ? date_i18n('n月j日', strtotime($start)) : '';
								$end_disp   = $end   ? date_i18n('n月j日', strtotime($end))   : '';
							?>
							<dt>開催日</dt>
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
							</dd>
							<?php endif; } ?>

						</dl>
						<?php endif; ?>


						<!-- 会場 -->
						<?php if ( $event_place = get_field( 'event-place' ) ) : ?>
						<dl class="flex">
							<dt class="place">会場</dt>
							<dd class="place"><?php echo esc_html( $event_place ); ?></dd>
						</dl>
						<?php endif; ?>

						<!-- イベント概要 -->
						<?php
						$overview = get_field('event-overview');

						if ( $overview ) :

						// HTMLタグを除去して文字数判定用に
						$plain = trim( wp_strip_all_tags( $overview ) );

						// 中身が本当に空なら何も出さない
						if ( $plain === '' ) {
							return;
						}

						$limit = 50;

						if ( mb_strlen( $plain ) > $limit ) {
							$plain = mb_substr( $plain, 0, $limit )
								. '…<a href="' . esc_url( get_permalink() ) . '" class="more">イベントの詳細へ</a>';
						}
						?>
						<p class="event-overview">
							<?php echo wp_kses_post( $plain ); ?>
						</p>
						<?php endif; ?>

					</div>
				</div>
			</article>
		</li>
		<?php endwhile; ?>

	</ul>

	<!-- ナビゲーション -->
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>

<?php wp_reset_postdata(); ?>
