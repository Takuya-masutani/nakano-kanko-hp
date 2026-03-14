<?php
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
add_theme_support( 'appearance-tools' );
add_theme_support( 'woocommerce' );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ) ) );
}

add_action( 'wp_enqueue_scripts', 'blankslate_enqueue' );
function blankslate_enqueue() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/customcss.css', array('blankslate-style'), null );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'blankslate_footer' );
function blankslate_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (deviceAgent.match(/(Android)/)) {
$("html").addClass("android");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = esc_html( '|' );
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return esc_html( '...' );
} else {
return wp_kses_post( $title );
}
}
function blankslate_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'blankslate_schema_url', 10 );
function blankslate_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'blankslate_wp_body_open' ) ) {
function blankslate_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'blankslate_skip_link', 5 );
function blankslate_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'blankslate' ) . '</a>';
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
	if ( !is_admin() ) {
		global $post;

		if ( isset( $post ) && isset( $post->ID ) ) {
			return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' .
				sprintf(
				__( '...%s', 'blankslate' ),
				'<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>'
			) .
				'</a>';
		}
	}
	return ''; // $post がない場合は空文字を返す
}

add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

function my_remove_post_support() {
	remove_post_type_support('achievements','editor'); 
}
add_action( 'init' , 'my_remove_post_support' );

// phpファイルをショートコード化し固定ページに出力
function Include_my_php($params = array()) {
	extract(shortcode_atts(array(
		'file' => 'default'
	), $params));
	ob_start();
	include(get_theme_root() . '/' . get_template() . "/$file.php");
	return ob_get_clean();
}
add_shortcode('myphp', 'Include_my_php');

// body_class にページスラッグや関連タームを追加
function pagename_class( $classes = array() ) {
	global $post;

	// 固定ページ
	if ( is_page() && isset( $post->post_name ) ) {
		$classes[] = sanitize_html_class( $post->post_name );

	// 投稿ページ（シングル）
	} elseif ( is_single() && isset( $post->post_type ) ) {
		// 投稿タイプ名
		$classes[] = sanitize_html_class( $post->post_type );

		// カテゴリーがある場合
		$categories = get_the_category( $post->ID );
		if ( !empty( $categories ) && isset( $categories[0]->slug ) ) {
			$classes[] = sanitize_html_class( $categories[0]->slug );
		}

		// タグがある場合
		$tags = get_the_tags( $post->ID );
		if ( !empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$classes[] = sanitize_html_class( $tag->slug );
			}
		}

	// アーカイブ（カテゴリー、タグ、カスタムタクソノミー）
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( isset( $term->slug ) ) {
			$classes[] = sanitize_html_class( $term->slug );
		}

	// 投稿タイプアーカイブ
	} elseif ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( $post_type ) {
			$classes[] = sanitize_html_class( $post_type );
		}
	}

	// モバイル判定
	if ( wp_is_mobile() ) {
		$classes[] = 'mobile';
	}

	return $classes;
}
add_filter( 'body_class', 'pagename_class' );

// 管理画面用CSS追加
function custom_admin_styles() {
    wp_enqueue_style( 'custom-admin-css', get_stylesheet_directory_uri() . '/admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'custom_admin_styles' );

// カスタム投稿URL生成ルール
function set_post_slug_by_post_type_once( $data, $postarr ) {

    // ID がない（完全な新規作成前）場合は何もしない
    if ( empty( $postarr['ID'] ) ) {
        return $data;
    }

    // すでにスラッグが設定されている場合は何もしない
    // （＝手動変更 or 再保存を尊重）
    if ( ! empty( $postarr['post_name'] ) ) {
        return $data;
    }

    $post_type = $data['post_type'];
    $post_id   = $postarr['ID'];

    switch ( $post_type ) {

        case 'eatery':
            $data['post_name'] = 'e' . $post_id;
            break;

        case 'facility':
            $data['post_name'] = 'f' . $post_id;
            break;

        case 'column':
            $data['post_name'] = 'column-' . $post_id;
            break;

        case 'event-info':
            $data['post_name'] = 'event-' . $post_id;
            break;

        case 'post': // WordPress デフォルト投稿
            $data['post_name'] = 'post-' . $post_id;
            break;

        default:
            // それ以外の投稿タイプは何もしない
            break;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'set_post_slug_by_post_type_once', 10, 2 );

// メディアから挿入する画像はフルサイズにする
function force_full_size_only( $sizes ) {
    return array( 'full' => __( 'Full Size' ) );
}
add_filter( 'image_size_names_choose', 'force_full_size_only' );

// WYSIWYG のコンテンツ内の画像を <figure> + <figcaption> に整形する
add_filter('the_content', 'wp_caption_to_figure_regex', 20);
function wp_caption_to_figure_regex($content) {

    // wp-caption ブロックを丸ごと検索
    $pattern = '/<div[^>]*class="[^"]*wp-caption[^"]*"[^>]*>(.*?)<\/div>/is';

    return preg_replace_callback($pattern, function($matches) {

        $block = $matches[1];

        // picture または img を抽出
        if (preg_match('/<(picture|img)[\s\S]*?<\/picture>|<img[^>]+>/is', $block, $imgMatch)) {
            $image = $imgMatch[0];
        } else {
            return $matches[0]; // 画像なし → 元のまま返す
        }

        // キャプションを抽出
        $caption = '';
        if (preg_match('/<p[^>]*class="[^"]*wp-caption-text[^"]*"[^>]*>(.*?)<\/p>/is', $block, $capMatch)) {
            $caption = trim($capMatch[1]);
        }

        // figure 組み立て
        $html  = '<figure class="acf-img-wrap">';
        $html .= $image;

        if ($caption !== '') {
            $html .= '<figcaption>' . wp_kses_post($caption) . '</figcaption>';
        }

        $html .= '</figure>';

        return $html;

    }, $content);
}

//<p><img></p> を figure に変換（キャプションなし用）
add_filter('the_content', 'wrap_p_img_with_figure', 21);
function wrap_p_img_with_figure($content) {

    $pattern = '/<p>\s*(<(img|picture)[^>]+>)\s*<\/p>/i';

    return preg_replace_callback($pattern, function ($matches) {

        return '<figure class="acf-img-wrap">' . $matches[1] . '</figure>';

    }, $content);
}

// 日本の祝日判定
if ( ! function_exists('is_japanese_holiday') ) {
	function is_japanese_holiday($ymd) {
		$time = strtotime($ymd);
		if ($time === false) return false;

		$y = (int)date('Y', $time);
		$m = (int)date('n', $time);
		$d = (int)date('j', $time);
		$w = (int)date('w', $time);

		$fixed = [
			'1-1','2-11','4-29','5-3','5-4','5-5','8-11','11-3','11-23'
		];
		if (in_array("$m-$d", $fixed, true)) return true;

		if (
			($m === 1  && $w === 1 && $d >= 8  && $d <= 14) ||
			($m === 7  && $w === 1 && $d >= 15 && $d <= 21) ||
			($m === 9  && $w === 1 && $d >= 15 && $d <= 21) ||
			($m === 10 && $w === 1 && $d >= 8  && $d <= 14)
		) return true;

		$spring = floor(20.8431 + 0.242194 * ($y - 1980) - floor(($y - 1980) / 4));
		$autumn = floor(23.2488 + 0.242194 * ($y - 1980) - floor(($y - 1980) / 4));

		return ($m === 3 && $d === $spring) || ($m === 9 && $d === $autumn);
	}
}

//トップページのイベントスライド
// Event Info Slider Shortcode（ACF対応版）
function eventinfo_slider_shortcode() {

  static $slider_seq = 0;
  $slider_seq++;
  $slider_id = 'event-slider-' . $slider_seq;

  ob_start();
  ?>
  <div id="<?php echo $slider_id; ?>" class="event-slider-container">
    <?php
    $event_posts = new WP_Query(array(
      'post_type'      => 'event-info',
      'posts_per_page' => 5,
      'orderby'        => 'date',
      'order'          => 'DESC'
    ));

    if ($event_posts->have_posts()):
      $i = 0;
      while ($event_posts->have_posts()):
        $event_posts->the_post();

        $event_day   = get_field('event-day');
        $event_hours = get_field('event-hours');
        $venue       = get_field('venue');
    ?>
      <div class="event-slide <?php echo $i === 0 ? 'active' : ''; ?>">
        
        <!-- 左（画像） -->
        <div class="event-slide-img">
          <?php if (has_post_thumbnail()) {
            the_post_thumbnail('large');
          } ?>
        </div>

        <!-- 右（詳細） -->
        <div class="event-slide-info">
          <h3 class="event-title"><?php the_title(); ?></h3>

<?php 
$event_day = get_field('event-day');

if ($event_day) {
    echo '<p class="event-meta"><strong>開催日：</strong>';

    if (is_array($event_day)) {
        echo esc_html(implode(' ～ ', array_filter($event_day)));
    } else {
        echo esc_html($event_day);
    }

    echo '</p>';
}
?>

<?php if ($event_hours): ?>
  <p class="event-meta"><strong>開催時間：</strong>
    <?php
      if (is_array($event_hours)) {
        echo wp_kses_post(implode('<br>', $event_hours));
      } else {
        echo esc_html($event_hours);
      }
    ?>
  </p>
<?php endif; ?>

          <?php if ($venue): ?>
            <p class="event-meta"><strong>会場：</strong><?php echo esc_html($venue); ?></p>
          <?php endif; ?>

          <a class="event-link" href="<?php the_permalink(); ?>">詳細を見る</a>
        </div>

      </div>
    <?php
        $i++;
      endwhile;
    endif;
    wp_reset_postdata();
    ?>
  </div>

<script>
window.addEventListener("load", () => {
  const slides = document.querySelectorAll('.event-slide');
  if (slides.length < 2) return;

  let index = 0;
  const interval = 5000; // 表示時間
  const speed = 800; // スライド速度(一致必須)

  // 初期状態
  slides[0].classList.add('is-active');

  setInterval(() => {
    const current = slides[index];
    const nextIndex = (index + 1) % slides.length;
    const next = slides[nextIndex];

    current.classList.remove('is-active');
    current.classList.add('is-leaving');

    next.classList.remove('is-leaving');
    next.classList.add('is-active');

    setTimeout(() => {
      current.classList.remove('is-leaving');
    }, speed);

    index = nextIndex;
  }, interval);
});
</script>

  <?php
  return ob_get_clean();
}
add_shortcode('eventinfo_slider', 'eventinfo_slider_shortcode');

/* 管理画面：複数カスタム投稿タイプの一覧にサムネイルを追加 */
add_filter('manage_posts_columns', function ($columns, $post_type) {

    $target_post_types = [
        'eatery',
        'facility',
        'tourism',
        'event-info',
    ];

    if (in_array($post_type, $target_post_types, true)) {
        $columns['thumbnail'] = 'サムネイル';
    }

    return $columns;
}, 10, 2);

add_action('manage_posts_custom_column', function ($column_name, $post_id) {

    if ($column_name !== 'thumbnail') {
        return;
    }

    if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, [80, 80]);
    } else {
        echo '—';
    }

}, 10, 2);

add_action('admin_head', function () {
    echo '<style>
        .wp-list-table .column-thumbnail {
            width: 100px;
            text-align: center;
        }
        .wp-list-table .column-thumbnail img {
            max-width: 80px;
            height: auto;
        }
    </style>';
});

/* column 用：サムネイルカラム追加 */
add_filter('manage_edit-column_columns', function ($columns) {
    $columns['thumbnail'] = 'サムネイル';
    return $columns;
});

/* column 用：サムネイル出力 */
add_action('manage_column_posts_custom_column', function ($column_name, $post_id) {
    if ($column_name !== 'thumbnail') return;

    if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, [80, 80]);
    } else {
        echo '—';
    }
}, 10, 2);

add_filter('manage_posts_columns', function ($columns, $post_type) {

    // column 以外は何もしない
    if ($post_type !== 'column') {
        return $columns;
    }

    $new_columns = [];

    foreach ($columns as $key => $label) {
        $new_columns[$key] = $label;

        // タイトルの直後にサムネイルを追加
        if ($key === 'title') {
            $new_columns['thumbnail'] = 'サムネイル';
        }
    }

    return $new_columns;
}, 10, 2);

// 投稿一覧に「スライド表示」カラムを追加
add_filter('manage_event-info_posts_columns', function($columns){
  $columns['show_in_slider'] = 'TOPのイベントスライド';
  return $columns;
});

add_action('manage_event-info_posts_custom_column', function($column, $post_id){
  if ($column === 'show_in_slider') {
    echo get_field('show_in_slider', $post_id) ? 'ON' : '—';
  }
}, 10, 2);

add_filter('flamingo_map_meta_cap', function($meta_caps){
  foreach($meta_caps as $key=>$value){
    if($value=='edit_users'){
      $meta_caps[$key] = 'edit_pages';
    }
  }
  return $meta_caps;
}, 10, 1);

//お知らせ表示
function getNewItems($atts) {
	extract(shortcode_atts(array(
		"num" => '',	//最新記事リストの取得数
		"cat" => ''	//表示する記事のカテゴリー指定
	), $atts));
	global $post;
	$oldpost = $post;
	$myposts = get_posts('numberposts='.$num.'&order=DESC&orderby=post_date&category='.$cat);
	$retHtml='<ul class="information_list inner">';
	foreach($myposts as $post) :
	$cat = get_the_category();
	$catname = $cat[0]->cat_name;
	$catslug = $cat[0]->slug;
		setup_postdata($post);
		$retHtml.='<li class="flex">';
		//$retHtml.='<span class="information_date">'.get_post_time( get_option( 'date_format' )).'</span>';
		$retHtml.='<span class="information_cat '.$catslug.'">'.$catname.'</span>';
		$retHtml.='<span class="information_title"><a href="'.get_permalink().'">'.the_title("","",false).'</a></span>';
		$retHtml.='</li>';
	endforeach;
	$retHtml.='</ul>';
	$post = $oldpost;
	wp_reset_postdata();
	return $retHtml;
}
add_shortcode("news", "getNewItems");

/**
 * 一覧・アーカイブからパスワード保護記事を除外、
 * 個別ページではパスワードフォームを正常表示
 */
function custom_exclude_password_posts( $query ) {
    // 管理画面では無視、メインクエリのみ
    if ( ! is_admin() && $query->is_main_query() ) {
        // 個別ページでは除外しない
        if ( ! is_singular() ) {
            // 公開記事のみ取得
            $query->set( 'post_status', 'publish' );
            // パスワード保護記事は除外
            $query->set( 'has_password', false );
        }
    }
}
add_action( 'pre_get_posts', 'custom_exclude_password_posts' );

/**
 * 指定 post_type を「日替わり固定ランダム順」にする
 */
function my_daily_rand_orderby( $orderby, $query ) {

	// 管理画面は除外
	if ( is_admin() ) {
		return $orderby;
	}

	// daily_rand が付いていないクエリは変更しない
	if ( ! $query->get( 'daily_rand' ) ) {
		return $orderby;
	}

	$post_type = $query->get( 'post_type' );

	// 対象の投稿タイプ
	$targets = array( 'eatery', 'facility' );

	if ( in_array( $post_type, $targets, true ) ) {

		$seed    = date( 'Ymd' ); // 日付で固定
		$orderby = "RAND($seed)";
	}

	return $orderby;
}
add_filter( 'posts_orderby', 'my_daily_rand_orderby', 10, 2 );