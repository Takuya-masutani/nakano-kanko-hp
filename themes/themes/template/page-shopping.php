<?php
/*
Template Name: お土産
*/

// ===============================
// 祝日判定関数
// ===============================
if (!function_exists('is_japanese_holiday')) {
	function is_japanese_holiday($ymd) {
		$time = strtotime($ymd);
		if ($time === false) return false;

		$y = (int)date('Y', $time);
		$m = (int)date('n', $time);
		$d = (int)date('j', $time);
		$w = (int)date('w', $time);

		$fixed = [
			'1-1', '2-11', '4-29', '5-3', '5-4', '5-5',
			'8-11', '11-3', '11-23'
		];
		if (in_array("$m-$d", $fixed, true)) return true;

		if (
			($m === 1 && $w === 1 && $d >= 8 && $d <= 14) ||
			($m === 7 && $w === 1 && $d >= 15 && $d <= 21) ||
			($m === 9 && $w === 1 && $d >= 15 && $d <= 21) ||
			($m === 10 && $w === 1 && $d >= 8 && $d <= 14)
		) return true;

		$spring = floor(20.8431 + 0.242194 * ($y - 1980) - floor(($y - 1980) / 4));
		$autumn = floor(23.2488 + 0.242194 * ($y - 1980) - floor(($y - 1980) / 4));
		if (($m === 3 && $d === $spring) || ($m === 9 && $d === $autumn)) return true;

		return false;
	}
}

if (!function_exists('get_japanese_holidays_for_year')) {
	function get_japanese_holidays_for_year($year) {
		$holidays = [];
		for ($m = 1; $m <= 12; $m++) {
			$days = cal_days_in_month(CAL_GREGORIAN, $m, $year);
			for ($d = 1; $d <= $days; $d++) {
				$ymd = sprintf('%04d%02d%02d', $year, $m, $d);
				if (is_japanese_holiday($ymd)) {
					$holidays[] = sprintf('%04d-%02d-%02d', $year, $m, $d);
				}
			}
		}
		return $holidays;
	}
}

get_header();

// ===============================
// ▼ NG日データ作成
// ===============================
$current_year = (int)date('Y');

// 祝日
$holidays = get_japanese_holidays_for_year($current_year);

// ACF NG日（グループ）
$acf_ng_dates = [];
$ng_group = get_field('ng_dates');

if (is_array($ng_group)) {
	foreach ($ng_group as $value) {
		if (!empty($value)) {
			$acf_ng_dates[] = str_replace('/', '-', $value); // 念のため正規化
		}
	}
}

// すべてのNG日を統合
$all_ng_dates = array_values(array_unique(array_merge(
	$holidays,
	$acf_ng_dates
)));
?>

<div class="shopping-content">
	<section class="subpage-header">
		<div class="subpage-head-content inner">
			<h1><?php the_title(); ?></h1>
			<div class="breadcrumb">
				<?php if ( function_exists('bcn_display') ) bcn_display(); ?>
			</div>
		</div>
	</section>

	<section class="entry-content inner">

		<script>
			const ngDates = <?php echo json_encode($all_ng_dates); ?>;
		</script>

		<div class="page-content">
			<?php the_content(); ?>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const dateInput = document.getElementById('preferred-date');
				if (!dateInput) return;

				const today = new Date();
				today.setDate(today.getDate() + 2);
				dateInput.min = today.toISOString().split('T')[0];

				if (!dateInput.value) {
					dateInput.value = dateInput.min;
				}

				const form = dateInput.form;
				if (!form) return;

				  // Safari判定関数
				  function isSafari() {
					const ua = navigator.userAgent.toLowerCase();
					return ua.includes('safari') && !ua.includes('chrome') && !ua.includes('android');
				  }

				form.addEventListener('submit', function (e) {
					const selectedDateStr = dateInput.value;
					if (!selectedDateStr) return;

					const selectedDate = new Date(selectedDateStr);
					const dayOfWeek = selectedDate.getDay();

					// 土日
					if (dayOfWeek === 0 || dayOfWeek === 6) {
						e.preventDefault();
						e.stopImmediatePropagation();
					alert('Weekends (Saturday and Sunday) are unavailable.');

					if (isSafari()) {
					// Safariの場合はスクロールのみ
					dateInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
					} else {
					// それ以外はフォーカス
					dateInput.focus();
					}

					return false;
					}

					// 祝日 + ACF NG日
					if (ngDates.includes(selectedDateStr)) {
						e.preventDefault();
						e.stopImmediatePropagation();
					alert('This date is unavailable. Please choose another date.');

					if (isSafari()) {
					dateInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
					} else {
					dateInput.focus();
					}

					return false;
					}

				}, true);
			});
		</script>

	</section>
</div>

<?php get_footer(); ?>
