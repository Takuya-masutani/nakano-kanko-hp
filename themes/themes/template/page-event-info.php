<?php
/*
Template Name: イベント
*/
get_header();
?>

<div class="archive-event-info">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1><?php the_title(); ?></h1>
			<div class="breadcrumb">
				<?php if ( function_exists( 'bcn_display' ) ) bcn_display(); ?>
			</div>
		</div>
	</section>

	<section class="entry-content inner">

		<?php
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;

		// ▼ 1ページ目のみ 固定ページ本文を表示
		if ( $paged === 1 && have_posts() ) :
			while ( have_posts() ) : the_post();
				if ( trim( get_the_content() ) !== '' ) :
					echo '<div class="page-content">';
					the_content();
					echo '</div>';
				endif;
			endwhile;
		endif;
		?>

		<?php
		// ▼ 固定ページ用ページネーション対応
		$paged = max(
			1,
			get_query_var('paged'),
			get_query_var('page')
		);

		// ▼ event-info 取得
		$args = array(
			'post_type'      => 'event-info',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'paged'          => $paged,
			'orderby'        => 'menu_order',
		);

		$event_query = new WP_Query( $args );
		?>

		<?php if ( $event_query->have_posts() ) : ?>
		<ul class="event-info post-list flex">

			<?php while ( $event_query->have_posts() ) : $event_query->the_post(); ?>
			<li class="post-box">
				<a href="<?php the_permalink(); ?>">
					<article>
						<h3 class="post-title">
							<span class="title-text"><?php the_title(); ?></span>
						</h3>
						<div>
							<figure>
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
									$plain = mb_substr( $plain, 0, $limit ) . '…<span class="more">イベントの詳細へ</span>';
								}
								?>
								<p class="event-overview">
									<?php echo wp_kses_post( $plain ); ?>
								</p>
								<?php endif; ?>
							</div>
						</div>
					</article>
				</a>
			</li>
			<?php endwhile; ?>

		</ul>

		<!-- ページネーション -->
		<div class="pagination">
			<?php
			echo paginate_links( array(
				'total'   => $event_query->max_num_pages,
				'current' => $paged,
				'mid_size'=> 1,
				'type'    => 'list',
			) );
			?>
		</div>

		<?php else : ?>
			<p>投稿がありません。</p>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</section>
</div>

<?php get_footer(); ?>
