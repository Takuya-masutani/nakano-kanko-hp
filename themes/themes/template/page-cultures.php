<?php
/*
Template Name: カルチャー
*/
get_header();
?>

<div class="archive-cultures">
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
		// ▼ ページネーション対応
		$paged = max(
			1,
			get_query_var('paged'),
			get_query_var('page')
		);

		// ▼ facility 取得
		$args = array(
			'post_type'      => 'facility',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'paged'          => $paged,

			// 🔥 日替わり固定ランダム
			'orderby'        => 'rand',
			'daily_rand'     => true,
		);

		$facility_query = new WP_Query( $args );
		?>

		<?php if ( $facility_query->have_posts() ) : ?>
		<ul class="archive facility post-list flex">

			<?php while ( $facility_query->have_posts() ) : $facility_query->the_post(); ?>
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

							<?php
							$culture_terms = wp_get_post_terms(get_the_ID(), 'cultures-category');
							if (!is_wp_error($culture_terms) && !empty($culture_terms)) :
							?>
							<figcaption class="category-type">
								<ul class="flex">
									<?php foreach ($culture_terms as $term) : 
									$class_name = sanitize_html_class(strtolower($term->slug));
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

							<?php
							$terms = get_the_terms(get_the_ID(), 'area');
							if ($terms && !is_wp_error($terms)) :
							?>
							<dl class="flex">
								<dt class="location-area">エリア</dt>
								<dd class="location-area">
									<ul class="location-area flex">
										<?php foreach ($terms as $term) : ?>
										<li class="<?php echo esc_attr($term->slug); ?>">
											<?php echo esc_html($term->name); ?>
										</li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</dl>
							<?php endif; ?>

							<?php if ( $facility_hours = get_field('facility-hours') ) : ?>
							<dl class="flex">
								<dt class="hours">営業時間</dt>
								<dd class="hours"><?php echo esc_html( $facility_hours ); ?></dd>
							</dl>
							<?php endif; ?>

							<?php if ( $holidays = get_field('facility-holidays') ) :
							$day_names = array(
								'mon' => '月曜日', 'tue' => '火曜日', 'wed' => '水曜日',
								'thu' => '木曜日', 'fri' => '金曜日', 'sat' => '土曜日',
								'sun' => '日曜日', 'holiday' => '祝日',
								'random' => '不定休', 'norest' => 'なし'
							);
							?>
							<dl class="flex">
								<dt class="holidays">定休日</dt>
								<dd class="holidays">
									<ul class="flex">
										<?php foreach ( $holidays as $day ) : ?>
										<li class="<?php echo esc_attr( $day ); ?>">
											<?php echo esc_html( $day_names[ $day ] ?? $day ); ?>
										</li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</dl>
							<?php endif; ?>

							<?php if ( $facility_location = get_field('facility-location') ) : ?>
							<dl class="flex">
								<dt class="location">住所</dt>
								<dd class="location"><?php echo esc_html( $facility_location ); ?></dd>
							</dl>
							<?php endif; ?>
						</div>
					</article>
				</a>
			</li>
			<?php endwhile; ?>

		</ul>

		<div class="pagination">
			<?php
			echo paginate_links( array(
				'total'   => $facility_query->max_num_pages,
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
