<?php
// 'area' の全タームを取得
$terms = get_terms(array(
    'taxonomy'   => 'area',
    'hide_empty' => false,
));

$filtered_terms = array_filter($terms, function($term) {
    $posts = get_posts(array(
        'post_type'      => 'post',
        'tax_query'      => array(
            array(
                'taxonomy' => 'area',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        ),
        'posts_per_page' => 1,
        'has_password'   => false,
        'post_status'    => 'publish',
    ));
    return !empty($posts); // 投稿があれば残す
});


if (!is_wp_error($terms) && !empty($terms)) :
?>
<ul class="cultures-category-list flex">
    <?php foreach ($terms as $term) : ?>
    <li class="<?php echo esc_attr(sanitize_html_class($term->slug)); ?>">
        <a href="<?php echo esc_url(get_term_link($term)); ?>">
            <?php echo esc_html($term->name); ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
