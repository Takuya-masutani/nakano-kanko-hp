<?php
// tourism 投稿を取得（IDのみ）
$tourism_posts = get_posts(array(
    'post_type'      => 'tourism',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'post_status'    => 'publish',
    'has_password'   => false,
));

$area_terms = [];

if ( $tourism_posts ) {
    foreach ( $tourism_posts as $post_id ) {
        $terms = get_the_terms( $post_id, 'area' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                // term_id をキーにして重複排除
                $area_terms[ $term->term_id ] = $term;
            }
        }
    }
}

$area_terms = array_values( $area_terms );

if ( ! empty( $area_terms ) ) :
?>
<ul class="cultures-category-list flex">
    <?php foreach ( $area_terms as $term ) : ?>
    <li class="<?php echo esc_attr( sanitize_html_class( $term->slug ) ); ?>">
        <a href="<?php echo esc_url(
            add_query_arg( 'post_type', 'tourism', get_term_link( $term ) )
        ); ?>">
            <?php echo esc_html( $term->name ); ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
