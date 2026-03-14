<?php
/*
Template Name: 食べる
*/
get_header();
?>

<div class="archive-eatery">
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
	$paged = max(
		1,
		get_query_var('paged'),
		get_query_var('page')
	);

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
	$args = array(
		'post_type'      => 'eatery',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'paged'          => $paged,

		// ★ 日替わりランダム用
		'orderby'        => 'none',
		'daily_rand'     => true,
	);

	$event_query = new WP_Query( $args );
	?>

	<?php if ( $event_query->have_posts() ) : ?>
	<ul class="archive eatery post-list flex">

	<?php while ( $event_query->have_posts() ) : $event_query->the_post(); ?>
	<li class="post-box">
	<a href="<?php the_permalink(); ?>">
	<article>

	<figure>
	<?php if ( has_post_thumbnail() ) : ?>
	<?php the_post_thumbnail( 'full' ); ?>
	<?php else : ?>
	<?php
	$default_img = get_stylesheet_directory_uri() . '/images/post-thumbnail-default.jpg';
	?>
	<img src="<?php echo esc_url( $default_img ); ?>" alt="デフォルト画像">
	<?php endif; ?>
	</figure>

	<div class="post-info">

	<h3 class="post-title"><?php the_title(); ?></h3>

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

	<?php if ($eatery_hours = get_field('eatery-hours')) : ?>
	<dl class="flex">
	<dt class="hours">営業時間</dt>
	<dd class="hours"><?php echo esc_html($eatery_hours); ?></dd>
	</dl>
	<?php endif; ?>

	<?php if ($holidays = get_field('eatery-holidays')) :
	$day_names = array(
	'mon' => '月曜日','tue' => '火曜日','wed' => '水曜日',
	'thu' => '木曜日','fri' => '金曜日','sat' => '土曜日',
	'sun' => '日曜日','holiday' => '祝日','random' => '不定休',
	'norest' => 'なし'
	);
	?>
	<dl class="flex">
	<dt class="holidays">定休日</dt>
	<dd class="holidays">
	<ul class="flex">
	<?php foreach ($holidays as $day) : ?>
	<li class="<?php echo esc_attr($day); ?>">
	<?php echo esc_html($day_names[$day] ?? $day); ?>
	</li>
	<?php endforeach; ?>
	</ul>
	</dd>
	</dl>
	<?php endif; ?>

	<?php if ($eatery_location = get_field('eatery-location')) : ?>
	<dl class="flex">
	<dt class="location">住所</dt>
	<dd class="location"><?php echo esc_html($eatery_location); ?></dd>
	</dl>
	<?php endif; ?>

	</div>
	</article>
	</a>
	</li>
	<?php endwhile; ?>

	</ul>

	<div class="pagination">
	<?php
	echo paginate_links( array(
		'total'   => $event_query->max_num_pages,
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