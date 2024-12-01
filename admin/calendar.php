<?php
class Calendar {
    private $active_year, $active_month, $active_day;
    private $events = [];

    public function __construct($date = null) {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function __toString() {
        $num_days = date('t', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);

        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';

        // Day names
        foreach ($days as $day) {
            $html .= '<div class="day_name">' . $day . '</div>';
        }

        // Previous month's days
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '<div class="day_num ignore">' . ($num_days_last_month - $i + 1) . '</div>';
        }

        // Current month's days
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = ($i == $this->active_day) ? ' selected' : '';
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';

            // Events for the day
            $day_events = [];
            foreach ($this->events as $event) {
                for ($d = 0; $d < $event[2]; $d++) {
                    if (date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i)) ==
                        date('Y-m-d', strtotime($event[1] . ' +' . $d . ' days'))) {
                        $day_events[] = '<div class="event' . $event[3] . '">' . $event[0] . '</div>';
                    }
                }
            }

            // Event container with scroll
            if (!empty($day_events)) {
                $html .= '<div class="events-container">';
                $html .= implode('', $day_events);
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        // Next month's days
        for ($i = 1; $i <= (42 - $num_days - max($first_day_of_week, 0)); $i++) {
            $html .= '<div class="day_num ignore">' . $i . '</div>';
        }

        $html .= '</div>'; // Close days
        $html .= '</div>'; // Close calendar
        return $html;
    }
}
?>
