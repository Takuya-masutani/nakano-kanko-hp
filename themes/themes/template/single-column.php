<?php
if ( post_password_required() ) {
    echo get_the_password_form();
    return; // パスワード入力前はACFの内容を出さない
}
?>

<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="subpage-header">
	<div class="subpage-head-content inner">
		<h1><?php the_title(); ?></h1>
		<div class="breadcrumb">
			<?php if ( function_exists( 'bcn_display' ) ) bcn_display(); ?>
		</div>
	</div>
</section>

<article id="pageid-post<?php the_ID(); ?>" class="entry-content inner">



	<!-- ▼ コラム基本情報 -->
	<section class="column-info flex">
		<figure>
			<?php if ( has_post_thumbnail() ) : ?>
			<img src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"
				 alt="<?php echo esc_attr( get_field('column-main-title') ); ?>">
			<?php endif; ?>
		</figure>
		<div class="column-info-detail flex">
			<?php
			$allowed_tags = [
				'br'   => [],
				'span' => [
					'class' => true, // class 属性を自由に使えるようにする
				],
			];
			?>
			<h2><?php echo wp_kses( get_field('column-main-title'), $allowed_tags ); ?></h2>
			<div class="column-info-author flex">
				<p class="name">文：<?php echo esc_html( get_field('column-author') ); ?></p>
				<p class="date"><?php echo esc_html( get_the_date('Y.m.d') ); ?></p>
			</div>
		</div>
	</section>

	<!-- ▼ コラム本文（セクション01〜10） -->
	<section class="column-main-content flex">
		<?php
		for ( $i = 1; $i <= 10; $i++ ) :

		$group_name = 'fukaboru-column-sec' . sprintf('%02d', $i);

		// グループ全体を取得（配列として返る）
		$group = get_field( $group_name );

		if ( empty( $group ) ) continue; // グループ自体が空ならスキップ

		$ttl = $group[ $group_name . '-ttl' ] ?? '';
		$txt = $group[ $group_name . '-txt' ] ?? '';

		// タイトルも本文も両方空 → ブロックごと出さない
		if ( empty($ttl) && empty($txt) ) continue;
		?>
		<div class="column-section column-section-<?php echo $i; ?>">
			<?php if ( $ttl ) : ?>
			<h3><?php echo esc_html( $ttl ); ?></h3>
			<?php endif; ?>

			<?php if ( $txt ) : ?>
			<div class="column-section-body flex">
				<?php echo apply_filters( 'the_content', $txt ); ?>
			</div>
			<?php endif; ?>
		</div>

		<?php endfor; ?>
	</section>

	<section class="column-notes">
		<h2>このコラムについて</h2>

		<!-- ▼ 執筆者・インタビュアー情報（01 / 02） -->
		<div class="citing-block">
			<?php
			// 著者01 / 著者02
			for ( $i = 1; $i <= 2; $i++ ) :

			$author = get_field("column-author-info0{$i}");

			if ( empty($author) ) continue;

			$name  = $author["column-author-name0{$i}"] ?? '';
			$intro = $author["column-author-introduction0{$i}"] ?? '';

			// ▼ ラジオボタン（値＋ラベル両方）
			$related = $author["column-author-related-person0{$i}"] ?? '';
			// ACF返り値形式例： ["value" => "writer", "label" => "ライター"] の想定

			// ▼ リンクセット
			$links = [];
			for ( $j = 1; $j <= 3; $j++ ) {
				$txt  = $author["column-author-linktxt0{$i}-{$j}"] ?? '';
				$url  = $author["column-author-link0{$i}-{$j}"] ?? '';

				if ( $txt && $url ) {
					$links[] = [
						"txt" => $txt,
						"url" => $url
					];
				}
			}

			// どれも空なら丸ごと非表示
			if ( empty($name) && empty($intro) && empty($links) && empty($related) ) continue;
			?>
			<?php if ( $name || $intro || !empty($related) ) : ?>
			<dl class="author-set author-<?php echo $i; ?>">
				<?php if ( $name ) : ?>
				<dt class="flex">
					<?php if ( !empty($related) ) : ?>
					<span class="<?php echo esc_attr($related['value']); ?>">
						<?php echo esc_html($related['label']); ?>
					</span>
					<?php endif; ?>
					<span class="author-name"><?php echo esc_html($name); ?></span>
				</dt>
				<?php endif; ?>

				<?php if ( $intro || !empty($links) ) : ?>
				<dd>

					<?php if ( $intro ) : ?>
						<p><?php echo wp_kses_post( nl2br( $intro ) ); ?></p>
					<?php endif; ?>

					<?php if ( !empty($links) ) : ?>
						<span class="author-links">関連リンク</span>
						<ul class="flex">
							<?php foreach ( $links as $link ) : ?>
							<li>
								<a class="externalLink flex"
								   href="<?php echo esc_url($link['url']); ?>"
								   target="_blank"
								   rel="noopener noreferrer">
									<?php echo esc_html($link['txt']); ?>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

				</dd>
				<?php endif; ?>
			</dl>
			<?php endif; ?>

			<?php endfor; ?>
		</div>


		<!-- ▼ 参考・引用（UL全体を最初に作って、空なら非表示） -->
		<?php
		// ▼ フリーテキスト
		$notes_free = get_field('column-notes-free');

		// ▼ 引用リスト
		$citation_items = [];
		for ( $n = 1; $n <= 6; $n++ ) :
			$group = get_field("column-notes0{$n}");
			if ( empty($group) ) continue;

			$citation = $group["column-notes-citation0{$n}"] ?? '';
			$link     = $group["column-notes-link0{$n}"] ?? '';

			if ( empty($citation) ) continue;

			$citation_items[] = [
				'text' => $citation,
				'link' => $link
			];
		endfor;
		?>

		<?php if ( $notes_free || !empty($citation_items) ) : ?>
		<div class="citing-block">
			<h3>引用元・参考文献・参考ページ</h3>
			<?php if ( $notes_free ) : ?>
			<p>
				<?php echo wp_kses_post( nl2br( $notes_free ) ); ?>
			</p>
			<?php endif; ?>

			<?php if ( !empty($citation_items) ) : ?>
			<ul class="citing-list flex">
				<?php foreach ( $citation_items as $index => $item ) : ?>
				<li class="note-item-<?php echo $index + 1; ?>">
					<?php if ( $item['link'] ) : ?>
					<a class="externalLink white flex"
					   href="<?php echo esc_url($item['link']); ?>"
					   target="_blank"
					   rel="noopener noreferrer">
						<?php echo esc_html($item['text']); ?>
					</a>
					<?php else : ?>
						<?php echo esc_html($item['text']); ?>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

		</div>
		<?php endif; ?>

	</section>
	<?php
	// ▼ 現在のコラムに紐づく執筆者タームを取得
	$authors = get_the_terms( get_the_ID(), 'column_author' );

	if ( !empty($authors) && !is_wp_error($authors) ) :

		$author_ids = wp_list_pluck( $authors, 'term_id' );

		$args = [
			'post_type'      => 'column',
			'posts_per_page' => 4,
			'post__not_in'   => [ get_the_ID() ],
			'post_status'    => 'publish',
			'has_password'   => false,
			'tax_query'      => [
				[
					'taxonomy' => 'column_author',
					'field'    => 'term_id',
					'terms'    => $author_ids,
				],
			],
		];

		$related_query = new WP_Query( $args );

		if ( $related_query->have_posts() ) :
		?>
		<section class="column-notes related-columns">
			<h2>関連コラム</h2>
			<ul class="citing-block related-columns-list flex">
				<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail('full'); ?>
						<?php endif; ?>
						<span><?php the_title(); ?></span>
					</a>
				</li>
				<?php endwhile; ?>
			</ul>
		</section>
		<?php
		endif;

		wp_reset_postdata();

	endif;
	?>

</article>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
