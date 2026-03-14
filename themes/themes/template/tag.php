<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1><?php single_term_title(); ?></h1>
			<div class="breadcrumb">
				<?php
				if ( function_exists( 'bcn_display' ) ) {
					bcn_display();
				}
				?>
			</div>
		</div>
	</section>
	<?php get_template_part( 'entry' ); ?>
</article>

<?php get_template_part( 'entry' ); ?>
<?php endwhile; endif; ?>
<?php get_template_part( 'nav', 'below' ); ?>
<?php get_footer(); ?>