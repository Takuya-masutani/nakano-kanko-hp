<?php
if (!function_exists('get_og_image')) {
    function get_og_image($url) {

        // ★ OG画像の個別キャッシュ
        $og_cache_key = 'og_image_' . md5($url);
        $cached = get_transient($og_cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $response = wp_remote_get($url, array(
            'timeout'     => 5,
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

$cache_key = 'nakano_rss_cache';

// ★ テスト用：一度だけキャッシュ削除
if ( current_user_can('administrator') ) {
    delete_transient($cache_key);
}


$rss_html = get_transient($cache_key);

if (false === $rss_html) {
    ob_start();

    $rss_url = 'https://nakano.keizai.biz/rss.xml';

    // ★ RSS取得を安全高速化
    $response = wp_remote_get($rss_url, array('timeout' => 5));

    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $rss = simplexml_load_string($body);
    } else {
        $rss = false;
    }

    if ($rss) :
        echo '<div class="rss-wrapper">';
        echo '<ul class="rss-list">';

        $i = 0;
        foreach ($rss->channel->item as $item) :
            if ($i >= 5) break;

            $title       = (string) $item->title;
            $link        = (string) $item->link;
            $description = strip_tags((string) $item->description);
            $date        = date('Y.m.d', strtotime($item->pubDate));
            $img_url     = get_og_image($link);
            ?>

            <li class="rss-item flex">
                <?php if ($img_url) : ?>
                    <div class="rss-thumb">
                        <img src="<?php echo esc_url($img_url); ?>" alt="">
                    </div>
                <?php endif; ?>

                <div class="rss-content">
                    <p class="rss-date"><?php echo esc_html($date); ?></p>
                    <a class="rss-title"
                       href="<?php echo esc_url($link); ?>"
                       target="_blank"
                       rel="noopener noreferrer">
						<h3><?php echo esc_html($title); ?></h3>
                    </a>
                    <p class="rss-description">
                        <?php echo esc_html(mb_strimwidth($description, 0, 200, '...')); ?>
                    </p>
                </div>
            </li>

            <?php
            $i++;
        endforeach;

        echo '</ul>';
        echo '</div>'; // .rss-wrapper

    else :
        echo '<p>RSSを読み込めませんでした。</p>';
    endif;

    // ★ 本番キャッシュ有効化
    $rss_html = ob_get_clean();
    set_transient($cache_key, $rss_html, 12 * HOUR_IN_SECONDS);
}

echo $rss_html;
?>
