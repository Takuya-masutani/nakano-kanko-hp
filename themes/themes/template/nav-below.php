<?php get_header(); ?>
<article id="post-<?php the_ID(); ?>">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1>お知らせ一覧</h1>
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
		<div class="post-list">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="post-box">
				<a href="<?php the_permalink(); ?>">
					<figure><?php the_post_thumbnail(); ?></figure>
				</a>
				<div class="post-info">
					<span class="post-date"><?php the_time('Y/m/d'); ?></span>
					<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
			</article>
			<?php endwhile; ?>
		</div>
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
	</section>
	<?php endif; ?>
</article>
<?php get_footer(); ?>