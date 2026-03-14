<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1><?php the_title(); ?></h1>
			<div class="breadcrumb">
				<?php
				if ( function_exists( 'bcn_display' ) ) {
					bcn_display();
				}
				?>
			</div>
		</div>
	</section>
	<section id="pageid-post<?php the_ID(); ?>" class="entry-content inner">
		<div itemprop="mainEntityOfPage" class="single-mainContent">
			<?php the_content(); ?>
		</div>
	</section>
</article>
<?php if ( post_password_required() ) { comments_template( '', true ); } ?>
<?php endwhile; endif; ?>

<?php get_footer(); ?>