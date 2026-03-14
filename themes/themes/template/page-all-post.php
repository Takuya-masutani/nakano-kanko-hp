<?php
/*
Template Name: All Posts List
*/
get_header();
?>

<article id="post-<?php the_ID(); ?>">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1>全ての投稿一覧</h1>
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
		<div class="post-list flex">
			<?php
			// ページ番号の取得（固定ページ用対応）
			if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}

			$args = array(
				'post_type'      => array('post', 'column'),
				'posts_per_page' => 24,
				'paged'          => $paged,
				'orderby'        => 'date',
				'order'          => 'DESC'
				'post_status'    => 'publish',
				'has_password'   => false,
			);
			$query = new WP_Query($args);

			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();

					// 投稿タイプ別ラベル
					$type_map = array(
						'post'   => 'おしらせ',
						'column' => 'コラム',
					);
					$post_type = get_post_type();
					$post_type_label = isset($type_map[$post_type]) ? $type_map[$post_type] : '';
					?>
					<article class="post-box">
						<figure>
							<a href="<?php the_permalink(); ?>">
								<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('full'); ?>
								<?php else : ?>
								<?php
								$default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg';
								?>
								<img src="<?php echo esc_url($default_img); ?>" alt="デフォルト画像">
								<?php endif; ?>
							</a>
						</figure>
						<div class="post-info">
							<span class="post-type post-type-<?php echo esc_attr($post_type); ?>">
								<?php echo esc_html($post_type_label); ?>
							</span>
							<span class="post-date"><?php the_time('Y/m/d'); ?></span>
							<h2 class="post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
						</div>
					</article>
				<?php
				endwhile;
				?>
		</div>
	</section>

	<div class="pagination">
		<?php
		echo paginate_links(array(
			'total'     => $query->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 1,
			'prev_text' => '&laquo; 前へ',
			'next_text' => '次へ &raquo;',
			'type'      => 'list',
		));
		?>
	</div>

	<?php
		wp_reset_postdata();
		else :
			echo '<p>記事はありません。</p>';
		endif;
	?>
</article>

<?php get_footer(); ?>