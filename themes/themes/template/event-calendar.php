<?php

// ==============================
// 表示対象の年月（★統一）
// ==============================
$year  = isset($_GET['cal_y']) ? intval($_GET['cal_y']) : date('Y');
$month = isset($_GET['cal_m']) ? intval($_GET['cal_m']) : date('n');
$today = date('Ymd');


// ==============================
// 月情報
// ==============================
$first_day     = strtotime(sprintf('%04d-%02d-01', $year, $month));
$days_in_month = date('t', $first_day);
$start_week    = date('w', $first_day);

$month_start = date('Ymd', $first_day);
$month_end   = date('Ymd', strtotime(date('Y-m-t', $first_day)));

$prev = strtotime('-1 month', $first_day);
$next = strtotime('+1 month', $first_day);

// ==============================
// イベントマップ作成
// ==============================
$event_map = [];

$args = [
	'post_type'      => 'event-info',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'meta_query'     => [
		'relation' => 'AND',
		[
			'key'     => 'event-day_event-day-start',
			'value'   => $month_end,
			'compare' => '<=',
			'type'    => 'NUMERIC',
		],
		[
			'key'     => 'event-day_event-day-end',
			'value'   => $month_start,
			'compare' => '>=',
			'type'    => 'NUMERIC',
		],
	],
];

$query = new WP_Query($args);

while ($query->have_posts()) {
	$query->the_post();

	$event_day = get_field('event-day');
	$start = $event_day['event-day-start'] ?? '';
	$end   = $event_day['event-day-end'] ?? '';

	// 日付未設定イベントは除外
	if (empty($start) || empty($end)) {
		continue;
	}

	for ($d = strtotime($start); $d <= strtotime($end); $d = strtotime('+1 day', $d)) {
		$ymd = date('Ymd', $d);
		$event_map[$ymd][] = [
			'title' => get_the_title(),
			'link'  => get_permalink(),
		];
	}
}
wp_reset_postdata();
?>

<!-- ==============================
カレンダーHTML
============================== -->

<table class="event-calendar">
	<thead class="day-of-week">
		<tr>
			<th class="sun">日</th><th class="mon">月</th><th class="tue">火</th><th class="wed">水</th><th class="thu">木</th><th class="fri">金</th><th class="sat">土</th>
		</tr>
	</thead>
	<tbody>
		<tr>

			<?php for ($i = 0; $i < $start_week; $i++) : ?>
			<td class="empty"></td>
			<?php endfor; ?>


			<?php
			$week_classes = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
			$day = 1;
			$week_day = $start_week;

			while ($day <= $days_in_month) :
			if ($week_day === 7) {
				echo '</tr><tr>';
				$week_day = 0;
			}

			$ymd = sprintf('%04d%02d%02d', $year, $month, $day);

			// クラス整理
			$classes = [];

			// 曜日クラス（★追加）
			$classes[] = $week_classes[$week_day];

			if ($ymd === $today) {
				$classes[] = 'today';
			}
			if (!empty($event_map[$ymd])) {
				$classes[] = 'has-event';
			}
			if (is_japanese_holiday($ymd)) {
				$classes[] = 'holiday';
			}

			?>

			<td class="<?php echo esc_attr(implode(' ', $classes)); ?>">
				<span class="day"><?php echo esc_html($day); ?></span>

				<?php
				$events = $event_map[$ymd] ?? [];
				$total  = count($events);
				$max    = 2;
				?>

				<?php if ($total > 0) : ?>
				<ul class="events">
					<?php foreach ($events as $i => $event) : ?>
					<?php if ($i < $max) : ?>
					<li>
						<a href="<?php echo esc_url($event['link']); ?>">
							<?php echo esc_html($event['title']); ?>
						</a>
					</li>
					<?php endif; ?>
					<?php endforeach; ?>

					<?php if ($total > $max) : ?>
					<li class="more-toggle"
						data-more="<?php echo esc_attr($total - $max); ?>">
						他 +<?php echo esc_html($total - $max); ?>件を見る
					</li>

					<?php foreach ($events as $i => $event) : ?>
					<?php if ($i >= $max) : ?>
					<li class="event-hidden">
						<a href="<?php echo esc_url($event['link']); ?>">
							<?php echo esc_html($event['title']); ?>
						</a>
					</li>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>

				<?php endif; ?>
			</td>

			<?php
			$day++;
			$week_day++;
			endwhile;
			?>

			<?php while ($week_day < 7) : ?>
			<td class="empty"></td>
			<?php $week_day++; endwhile; ?>

		</tr>
	</tbody>
</table>
<div class="event-popup" id="eventPopup" aria-hidden="true">
	<div class="event-popup__bg"></div>

	<div class="event-popup__body">
		<button class="event-popup__close">×</button>
		<ul class="event-popup__content flex"></ul>
	</div>
</div>

<!-- ==============================
月ナビゲーション
============================== -->
<?php
$base_url = remove_query_arg(['cal_y', 'cal_m']);

$prev_url = add_query_arg(
	['cal_y' => date('Y', $prev), 'cal_m' => date('n', $prev)],
	$base_url
);

$next_url = add_query_arg(
	['cal_y' => date('Y', $next), 'cal_m' => date('n', $next)],
	$base_url
);
?>

<div class="event-calendar-nav">
	<a class="prev" href="<?php echo esc_url($prev_url); ?>">&laquo;</a>

	<span class="current">
		<?php echo esc_html($year); ?>年<?php echo esc_html($month); ?>月
	</span>

	<a class="next" href="<?php echo esc_url($next_url); ?>">&raquo;</a>
</div>
<span class="memo">※振替休日はカレンダー上、祝日になっていない日も場合もあります。</span>

<script>
	document.addEventListener('click', function (e) {
		const toggle = e.target.closest('.more-toggle');
		if (!toggle) return;

		const ul = toggle.closest('.events');
		const isOpen = ul.classList.toggle('is-open');

		// 表示文言切り替え
		if (isOpen) {
			toggle.textContent = '− 閉じる';
		} else {
			const more = toggle.dataset.more;
			toggle.textContent = `他 +${more}件を見る`;
		}
	});
</script>
<script>
	document.addEventListener('click', function (e) {
		if (window.innerWidth > 640) return;

		const cell = e.target.closest('.event-calendar td.has-event');
		if (!cell) return;

		// aタグの通常遷移を止める
		if (e.target.closest('a')) {
			e.preventDefault();
		}

		const events = cell.querySelector('.events');
		if (!events) return;

		const popup  = document.getElementById('eventPopup');
		const content = popup.querySelector('.event-popup__content');

		// ★ ここが差し替えポイント
		const cloned = events.cloneNode(true);

		// more-toggle はポップアップでは不要
		cloned.querySelectorAll('.more-toggle').forEach(el => el.remove());

		content.innerHTML = cloned.innerHTML;

		popup.classList.add('is-open');
		popup.setAttribute('aria-hidden', 'false');
	});
</script>

<script>
	function closePopup() {
		const popup = document.getElementById('eventPopup');
		if (!popup) return;

		popup.classList.remove('is-open');
		popup.setAttribute('aria-hidden', 'true');
	}

	const popupBg = document.querySelector('.event-popup__bg');
	if (popupBg) {
		popupBg.addEventListener('click', function (e) {
			e.stopPropagation();
			closePopup();
		});
	}

	const popupClose = document.querySelector('.event-popup__close');
	if (popupClose) {
		popupClose.addEventListener('click', function (e) {
			e.stopPropagation();
			closePopup();
		});
	}
</script>