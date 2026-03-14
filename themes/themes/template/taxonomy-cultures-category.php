<?php get_header(); ?>
<div class="archive-facility">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1><?php single_term_title(); ?>一覧</h1>
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
		<ul class="facility post-list flex">
			<?php while ( have_posts() ) : the_post(); ?>
			<li class="post-box">
				<a href="<?php the_permalink(); ?>">
					<article>
						<figure>
							<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('full'); ?>
							<?php else : ?>
							<?php $default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg'; ?>
							<img src="<?php echo esc_url($default_img); ?>" alt="デフォルト画像">
							<?php endif; ?>

							<!-- 区分 -->
							<?php
							$culture_terms = wp_get_post_terms(get_the_ID(), 'cultures-category');
							if (!is_wp_error($culture_terms) && !empty($culture_terms)) :
							?>
							<figcaption class="category-type">
								<ul class="flex">
									<?php foreach ($culture_terms as $term) : 
									$class_name = sanitize_html_class(strtolower($term->slug));
									// もしラベル変換が必要ならここでやる（基本は$term->nameでOK）
									$label = $term->name;
									?>
									<li class="<?php echo esc_attr($class_name); ?>">
										<?php echo esc_html($label); ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</figcaption>
							<?php endif; ?>
						</figure>

						<div class="post-info">
							<h3 class="post-title"><?php the_title(); ?></h3>

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

							<!-- 営業時間 -->
							<?php if ( $facility_hours = get_field('facility-hours') ) : ?>
							<dl class="flex">
								<dt class="hours">営業時間</dt>
								<dd class="hours"><?php echo esc_html($facility_hours); ?></dd>
							</dl>
							<?php endif; ?>

							<!-- 定休日 -->
							<?php if ( $holidays = get_field('facility-holidays') ) :
							$day_names = array(
								'mon' => '月曜日', 'tue' => '火曜日', 'wed' => '水曜日',
								'thu' => '木曜日', 'fri' => '金曜日', 'sat' => '土曜日',
								'sun' => '日曜日', 'holiday' => '祝日', 'random' => '不定休',
								'norest' => 'なし'
							);
							?>
							<dl class="flex">
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
							</dl>
							<?php endif; ?>

							<!-- 住所 -->
							<?php if ( $facility_location = get_field('facility-location') ) : ?>
							<dl class="flex">
								<dt class="location">住所</dt>
								<dd class="location"><?php echo esc_html($facility_location); ?></dd>
							</dl>
							<?php endif; ?>
						</div>
					</article>
				</a>
			</li>
			<?php endwhile; ?>
		</ul>
	</section>
	<div class="pagination">
		<?php
		// ページネーション
		the_posts_pagination( array(
			'mid_size' => 1,
			'prev_next' => false,
			'type' => 'list',
		) );
		?>
	</div>
	<?php else : ?>
	<p>投稿がありません。</p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>