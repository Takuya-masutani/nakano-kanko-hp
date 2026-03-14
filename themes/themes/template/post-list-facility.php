<?php
/**
 * facility list output (fixed)
 * Shortcode 用パーツ
 */
$args = array(
	'post_type'      => 'facility', // 投稿タイプ
	'posts_per_page' => 6, // 最大6件
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'has_password'   => false,
);

$facility_posts = new WP_Query($args);

if ($facility_posts->have_posts()) : ?>
<ul class="archive facility post-list flex">
	<?php while ($facility_posts->have_posts()) : $facility_posts->the_post(); ?>
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

					<!-- 営業時間 -->
					<?php if ($facility_hours = get_field('facility-hours')) : ?>
					<dl class="flex">
						<dt class="hours">営業時間</dt>
						<dd class="hours"><?php echo esc_html($facility_hours); ?></dd>
					</dl>
					<?php endif; ?>

					<!-- 定休日 -->
					<?php if ($holidays = get_field('facility-holidays')) :
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
								<?php foreach ($holidays as $day) : ?>
								<li class="<?php echo esc_attr($day); ?>">
									<?php echo esc_html($day_names[$day] ?? $day); ?>
								</li>
								<?php endforeach; ?>
							</ul>
						</dd>
					</dl>
					<?php endif; ?>

					<!-- 住所 -->
					<?php if ($facility_location = get_field('facility-location')) : ?>
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
<?php wp_reset_postdata(); endif; ?>
