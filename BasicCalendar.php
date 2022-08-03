<?php
/**
 * 
 * BasicCalendar.php
 * 
 * 07/30/2022		Ron Boutilier
 *
 */

 
class BasicCalendar {
	protected $month;
	protected $year;
	protected $day_displays = array(
		"Su", "Mo", "Tu", "We", "Th", "Fr", "Sa",
	);
	protected $days_in_month;
	protected $first_of_month_timestamp;
	protected $first_of_month_display;
	protected $last_of_month_display;
	protected $first_of_month_info;
	protected $last_of_prev_month_info;
	protected $first_of_next_month_info;
	protected $first_display_day_info;
	protected $number_of_display_weeks;
	protected $full_dates_array;

		
	public function __construct($args=array()) {
		$default_args = array(
			'month' => date("m"),
			'year' => date("Y"),
		);
		$classArgs = array_merge($default_args, $args);
		$this->month = $classArgs['month'];
		$this->year = $classArgs['year'];

		$this->days_in_month = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$this->first_of_month_display = date("{$this->year}-{$this->month}-01");
		$this->last_of_month_display = date("{$this->year}-{$this->month}-{$this->days_in_month}");
		$this->first_of_month_timestamp = strtotime($this->first_of_month_display);
		$this->first_of_month_info = getdate($this->first_of_month_timestamp);
		$this->build_last_of_prev_month_info();
		$this->build_first_of_next_month_info();
		$this->build_first_display_day_info();
		$this->number_of_display_weeks = ceil(($this->first_of_month_info['wday'] + $this->days_in_month) / 7);
		$this->build_date_array();

	}

	public function display() {
		// figure out number of weeks to display 

		// build table with header of weekdays
		ob_start();
		?>
		<div class="tsd-basic-calendar">
			<?php  $this->display_title()	?>
			<table class="tsd-basic-calendar-table">
				<?php $this->display_table_head() ?>
				<?php $this->display_table_rows() ?>
			</table>
		</div>
		<?php

		// build each day of week with non-month days gray'd out

		$table_display = ob_get_clean();

		echo $table_display;
	}

	protected function display_table_head() {
		?>
			<thead>
				<tr>
					<?php 
						foreach($this->day_displays as $day_display) {
							echo "<th scope='col'>$day_display</th>";
						}
					?>
				</tr>
			</thead>
		<?php
	}

	protected function display_title() {
		?>
		<div class="tsd-calendar-title">
			<h3><?php echo $this->first_of_month_info['month'] . " " . $this->year ?></h3>
		</div>
		<?php
	}

	protected function build_date_array() {
		// day of week for 1st - 
		$this->full_dates_array = array();
		$week_cnt = $this->number_of_display_weeks;

		$number_of_full_days = $week_cnt * 7;
		$start_timestamp = $this->first_display_day_info[0];
		$tmpdate = date_create("@$start_timestamp");
		for ($day_cnt = 0; $day_cnt < $number_of_full_days; $day_cnt++) {
			$tmp_timestamp = $tmpdate->getTimestamp();
			$tmpdate_info = getdate($tmp_timestamp);
			// print_r($tmpdate_info);
			$wday = $tmpdate_info['wday'];
			$week = (int) ($day_cnt / 7);
			$this->full_dates_array[$week][$wday] = array(
				'timestamp' => $tmp_timestamp,
				'date_display' => date('Y-m-d', $tmp_timestamp),
				'day_display' => date('j', $tmp_timestamp),
				'month' => $tmpdate_info['mon'],
				'year' => $tmpdate_info['year'],
				'weekday' => $tmpdate_info['wday'],
				'yearday' => $tmpdate_info['yday'],
			);
			date_add($tmpdate, date_interval_create_from_date_string("1 day"));
		}
	}

	protected function display_table_rows() {
		$week_cnt = $this->number_of_display_weeks;
		for($week = 0; $week < $week_cnt; $week++) {
			echo "<tr>";
			for ($weekday = 0; $weekday < 7; $weekday++) {
				$this->display_calendar_cell($this->full_dates_array[$week][$weekday]);
			}
			echo "</tr>";
		}
	}

	protected function display_calendar_cell($day_info) {
		$month_class = ($day_info['month'] == $this->month) ? "tsd-in-month-day" : "tsd-non-month-day";
		?>
			<td class="<?php echo $month_class ?>">
				<div class="tsd-month-day-div">
					<span class='tsd-day-of-month-display'>
						<?php echo $day_info['day_display'] ?>
					</span>
					<div class='tsd-daily-display'>
						<?php echo 'tsd-in-month-day' == $month_class ? "Avail" : "" ?>
					</div>
				</div>
			</td>
		<?php
	}

	protected function build_last_of_prev_month_info() {
		$tmpdate = date_create($this->first_of_month_display);
		date_sub($tmpdate, date_interval_create_from_date_string("1 day"));
		$tmp_timestamp = $tmpdate->getTimestamp();
		$this->last_of_prev_month_info = getdate($tmp_timestamp);
	}

	protected function build_first_display_day_info() {
		$tmpdate = date_create($this->first_of_month_display);
		$first_day_of_week = $this->first_of_month_info['wday'];
		if ($first_day_of_week) {
			date_sub($tmpdate, date_interval_create_from_date_string("$first_day_of_week days"));
		}
		$tmp_timestamp = $tmpdate->getTimestamp();
		$this->first_display_day_info = getdate($tmp_timestamp);
	}

	protected function build_first_of_next_month_info() {
		$tmpdate = date_create($this->last_of_month_display);
		date_add($tmpdate, date_interval_create_from_date_string("1 day"));
		$tmp_timestamp = $tmpdate->getTimestamp();
		$this->first_of_next_month_info = getdate($tmp_timestamp);
	}
}