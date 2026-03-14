<?php get_header(); ?>
<article id="post-<?php the_ID(); ?>">
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
		<div class="post-list flex">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="post-box">
				<figure>
					<a href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('full'); ?>
						<?php else : ?>
						<?php
						// デフォルト画像パス
						$default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg';
						?>
						<img src="<?php echo esc_url($default_img); ?>" alt="デフォルト画像">
						<?php endif; ?>
					</a>
				</figure>
				<div class="post-info">
					<span class="post-date"><?php the_time('Y/m/d'); ?></span>
					<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
			</article>
			<?php endwhile; ?>
		</div>
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
	<?php endif; ?>
</article>
<?php get_footer(); ?>