<?php
/*
Template Name: 街歩き・ツアー
*/
get_header();
?>

<div class="archive-citywalk">
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
			get_query_var( 'paged' ),
			get_query_var( 'page' )
		);

		// ▼ tourism を取得
		$args = array(
			'post_type'      => 'tourism',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'paged'          => $paged,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$tourism_query = new WP_Query( $args );
		?>

		<?php if ( $tourism_query->have_posts() ) : ?>
		<ul class="archive tourism post-list flex">

			<?php while ( $tourism_query->have_posts() ) : $tourism_query->the_post(); ?>
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
							<?php if ($tourism_type = get_field('tourism-type')) :
								$type_labels = array(
									'history'             => '歴史・旧跡',
									'scenery'             => '自然・景観',
									'cityscape'           => 'みち・街並み',
									'commercial-facility' => '商業施設 (工場跡等)',
									'art'                 => '芸術・文化施設',
									'sports'              => '運動施設',
									'local-speciality'    => '食・特産品',
									'festival'            => 'まつり・イベント'
								);
								// 小文字統一して安全なclass名に変換
								$class_name = sanitize_html_class( strtolower( $tourism_type ) );
							?>
							<figcaption class="category-type <?php echo esc_attr($class_name); ?>">
								<?php echo esc_html($type_labels[strtolower($tourism_type)] ?? $tourism_type); ?>
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

					<!-- 住所 -->
					<?php if ($tourism_location = get_field('tourism-location')) : ?>
					<dl class="flex">
						<dt class="location">住所</dt>
						<dd class="location"><?php echo esc_html($tourism_location); ?></dd>
					</dl>
					<?php endif; ?>

					<!-- コメント -->
					<?php if ($tourism_comment = get_field('tourism-comment')) : ?>
						<?php
							$max_length = 45; // 最大文字数
							$comment_text = wp_strip_all_tags($tourism_comment);
							if (mb_strlen($comment_text, 'UTF-8') > $max_length) {
								$comment_text = mb_substr($comment_text, 0, $max_length, 'UTF-8') . '...';
							}
						?>
						<p class="comment"><?php echo esc_html($comment_text); ?></p>
					<?php endif; ?>


				</div>
					</article>
				</a>
			</li>
			<?php endwhile; ?>
		</ul>

		<!-- ページネーション -->
		<div class="pagination inner">
			<?php
			echo paginate_links( array(
				'total'   => $tourism_query->max_num_pages,
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
