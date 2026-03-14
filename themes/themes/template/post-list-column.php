<?php
/**
 * Column list output (fixed)
 * Shortcode 用パーツ
 */
$args = array(
	'post_type'      => 'column',
	'posts_per_page' => 6,
	'post_status'    => 'publish',
	'has_password'   => false,
);

$column_query = new WP_Query($args);

if ( ! $column_query->have_posts() ) {
	return;
}
?>

<ul class="output-column-list flex">
	<?php while ( $column_query->have_posts() ) : $column_query->the_post(); ?>
	<li>
		<article class="column-box">
			<a class="flex" href="<?php the_permalink(); ?>">
				<div class="column-meta">
					<h3><?php the_title(); ?></h3>

					<!--ふかぼる中野のみで表示--->
					<?php if ( is_page('nakano_column') ) : ?>
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
					<p><?php echo wp_kses_post( $plain ); ?></p>
					<span class="more">続きを読む</span>
					<?php endif; ?>

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
	)
); ?>
					<?php endif; ?>
				</figure>
			</a>
		</article>
	</li>
	<?php endwhile; ?>
</ul>

<?php wp_reset_postdata(); ?>
