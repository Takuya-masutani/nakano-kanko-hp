<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div id="post-<?php the_ID(); ?>">
	<?php if ( is_front_page() ) : ?>
	<div class="page-top page-header">
		<div class="main-visual fadeIn_MvBg">
			<div class="mv-inner flex">
				<!--<div class="mv_txt">
					<p>キャッチフレーズ</p>
				</div>-->
				<picture class="mv_img fadeIn_Mv">
					<source srcset="<?php echo home_url(); ?>/wp-content/uploads/topimage_06.png" media="(min-width: 769px)">
					<img src="<?php echo home_url(); ?>/wp-content/uploads/topimage_06.png" alt="メインビジュアル" width="1920" height="950">
				</picture>
				<div class="MV_slide only_pc">
					<?php get_template_part('post-slide-event-info'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php else: ?>
	<section class="subpage-header" style="background: center center/cover no-repeat url(<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>)">
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
	<?php endif; ?>
	<section id="pageid-post<?php the_ID(); ?>" class="entry-content" itemprop="mainContentOfPage">
		<?php the_content(); ?>
	</section>
</div>
<?php if ( comments_open() && !post_password_required() ) { comments_template( '', true ); } ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>