<?php get_header(); ?>

<div id="column-archives">

	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1>ふかぼるなかの</h1>

			<div class="breadcrumb">
				<?php if ( function_exists( 'bcn_display' ) ) { bcn_display(); } ?>
			</div>
		</div>
	</section>


	<section class="entry-content inner">
		<h2>ちょっと変わった角度から中野を見てみる、中野のふかぼりコラム</h2>
		<?php if ( have_posts() ) : ?>

		<ul class="output-column-list flex">

			<?php while ( have_posts() ) : the_post(); ?>

			<li>
				<article class="column-box">

					<a class="flex" href="<?php the_permalink(); ?>">

						<div class="column-meta">

							<h3><?php the_title(); ?></h3>

							<?php
							$group = get_field('fukaboru-column-sec01');
							$text  = $group['fukaboru-column-sec01-txt'] ?? '';

								if ( $text ) :

									$plain = wp_strip_all_tags( $text );
									$limit = 35;

									if ( mb_strlen( $plain ) > $limit ) {
										$plain = mb_substr( $plain, 0, $limit ) . '…';
									}
							?>

								<p><?php echo esc_html( $plain ); ?></p>
								<span class="more">続きを読む</span>

							<?php endif; ?>

						</div>


						<figure>

							<?php if ( has_post_thumbnail() ) : ?>

								<?php
								$img_id  = get_post_thumbnail_id();
								$img_alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
								?>

								<?php the_post_thumbnail(
									'full',
									array(
										'alt' => esc_attr( $img_alt ?: get_the_title() ),
										'loading' => 'lazy'
									)
								); ?>

							<?php else : ?>

								<?php
								$default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg';
								?>

								<img src="<?php echo esc_url($default_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">

							<?php endif; ?>

						</figure>

					</a>

				</article>
			</li>

			<?php endwhile; ?>

		</ul>


		<div class="pagination">
			<?php
			the_posts_pagination( array(
				'mid_size'  => 1,
				'prev_next' => false,
				'type'      => 'list',
			) );
			?>
		</div>

		<?php else : ?>

			<p>コラムがまだ投稿されていません。</p>

		<?php endif; ?>

	</section>

</div>

<?php get_footer(); ?>