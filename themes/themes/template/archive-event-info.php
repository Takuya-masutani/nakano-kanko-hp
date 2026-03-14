<?php get_header(); ?>
<div class="archive-event-info">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1>イベント一覧</h1>
			<div class="breadcrumb">
				<?php
				if ( function_exists( 'bcn_display' ) ) {
					bcn_display();
				}
				?>
			</div>
		</div>
	</section>

	<section class="entry-content inner">
		<?php if ( have_posts() ) : ?>
		<ul class="event-info post-list flex">
			<?php while ( have_posts() ) : the_post(); ?>
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
	</section>

	<div class="pagination">
		<?php
		the_posts_pagination( array(
			'mid_size'  => 1,
			'prev_next'=> false,
			'type'     => 'list',
		) );
		?>
	</div>

	<?php else : ?>
	<p>投稿がありません。</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
