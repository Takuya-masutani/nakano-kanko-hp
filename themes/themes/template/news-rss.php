<?php
if (!function_exists('get_og_image')) {
    function get_og_image($url) {

        // ★ 個別OGキャッシュ
        $og_cache_key = 'og_image_' . md5($url);
        $cached = get_transient($og_cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $response = wp_remote_get($url, array(
            'timeout' => 5,
            'redirection' => 3,
        ));

        if (is_wp_error($response)) {
            return '';
        }

        $html = wp_remote_retrieve_body($response);
        if (!$html) return '';

        if (preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $matches)) {
            $image = $matches[1];
            set_transient($og_cache_key, $image, DAY_IN_SECONDS);
            return $image;
        }

        set_transient($og_cache_key, '', DAY_IN_SECONDS);
        return '';
    }
}

$cache_key = 'nakano_rss_cache_' . ( is_front_page() ? 'front' : 'news' );
$rss_html = get_transient($cache_key);

if (false === $rss_html) {
    ob_start();

    $rss_url = 'https://nakano.keizai.biz/rss.xml';

    // ★ RSSもwp_remote_getへ変更
    $response = wp_remote_get($rss_url, array('timeout' => 5));

    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $rss = simplexml_load_string($body);
    } else {
        $rss = false;
    }

    if ($rss) :
        echo '<div class="rss-wrapper">';
        echo '<div class="rss-slider swiper">';
        echo '<div class="swiper-wrapper">';

        $i = 0;
        foreach ($rss->channel->item as $item) :
            if ($i >= 5) break;
            $title = (string) $item->title;
            $link = (string) $item->link;
            $description = (string) strip_tags($item->description);
            $date = date('Y.m.d', strtotime($item->pubDate));
            $img_url = get_og_image($link);

            echo '<div class="swiper-slide">';
            echo '<div class="rss-item flex">';

            if ($img_url) {
                echo '<div class="rss-thumb"><img src="' . esc_url($img_url) . '" alt=""></div>';
            }

            echo '<div class="rss-content">';
            echo '<p class="rss-date">' . esc_html($date) . '</p>';
            echo '<a class="rss-title" href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer">' . esc_html($title) . '</a>';
            echo '<p class="rss-description">' . esc_html(mb_strimwidth($description, 0, 100, '...')) . '</p>';
            echo '</div>';

            echo '</div>';
            echo '</div>';

            $i++;
        endforeach;

        echo '</div>';
        echo '</div>';

        echo '<div class="rss-slider-nav">';
        echo '<div class="swiper-button-prev"></div>';
        echo '<div class="swiper-pagination"></div>';
        echo '<div class="swiper-button-next"></div>';
        echo '</div>';

        echo '</div>';

    else :
        echo '<p>RSSを読み込めませんでした。</p>';
    endif;

    // ★ 本番キャッシュ有効
    $rss_html = ob_get_clean();
    set_transient($cache_key, $rss_html, 12 * HOUR_IN_SECONDS);
}

echo $rss_html;
?>
