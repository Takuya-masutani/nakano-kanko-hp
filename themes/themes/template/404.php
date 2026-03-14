<?php get_header(); ?>
<article id="post-<?php the_ID(); ?>">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1>NOT FOUND</h1>
			<div class="breadcrumb">
				<?php
				if ( function_exists( 'bcn_display' ) ) {
					bcn_display();
				}
				?>
			</div>
		</div>
	</section>
	<section id="page_not-found" class="entry-content inner">
		<div class="inner">
			<p>ページが見つかりませんでした。</p>
			<a href="<?php echo home_url(); ?>">トップページへ戻る</a>
		</div>
	</section>
</article>
<?php get_footer(); ?>