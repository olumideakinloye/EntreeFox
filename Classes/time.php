<?php
class Time
{
    function evaluate_large_numbers($number)
    {
        if ($number > 0 && $number < 999) {
            return $number;
        } elseif ($number > 1000 && $number < 1000000) {
            $thousand_comment = $number / 1000;
            return (int)$thousand_comment . "K";
        } elseif ($number > 1000000 && $number < 1099999) {
            $million_comments = $number / 1000000;
            return (int)$million_comments . "M";
        } elseif ($number >= 1100000) {
            $million_comments = $number / 1000000;
            $milli = explode('.', $million_comments);
            $decimal_milli = str_split($milli[1]);
            if ($decimal_milli[0] >= 1) {
                if ($milli[0] >= 100) {
                    return ($milli[0] / $milli[0]) . "." . $decimal_milli[0] . "M+";
                } else {
                    return $milli[0] . "." . $decimal_milli[0] . "M";
                }
            } else {
                return $milli[0] . "M";
            }
        }
    }
    function eval_message_time($datetime)
    {
        $now = new DateTime();
        $time = new DateTime($datetime);
        $isFuture = $time > $now;
        $diff = $now->diff($time);

        $suffix = $isFuture ? "from now" : "ago";

        // Check if the date is today
        $isToday = $now->format('Y-m-d') === $time->format('Y-m-d');

        if ($diff->y > 0 || $diff->m > 0 || $diff->d > 7) {
            // Return exact date for older dates
            return $time->format("d") . "-" . $time->format("m") . "-" . $time->format("Y");
        } elseif ($diff->d > 0) {
            if ($diff->d == 1) {
                return $isFuture ? "Tomorrow at " . $time->format("h:i A") : "Yesterday at " . $time->format("h:i A");
            }
            return $diff->d . " day" . ($diff->d > 1 ? "s" : "") . " " . $suffix;
        } elseif ($diff->h > 0) {
            if ($isToday && !$isFuture) {
                return "Today at " . $time->format("h:i A");
            }
            return $diff->h . " hour" . ($diff->h > 1 ? "s" : "") . " " . $suffix;
        } elseif ($diff->i > 0) {
            return $diff->i . " minute" . ($diff->i > 1 ? "s" : "") . " " . $suffix;
        } elseif ($diff->s > 10) {
            return $diff->s . " seconds " . $suffix;
        } elseif ($diff->s >= 0) {
            return $isFuture ? "In a moment" : "Just now";
        }

        return "Unknown time";
    }
    function get_time2($pasttime)
    {
        $today = new DateTime();
        $date = new DateTime($pasttime);
        $interval = $today->diff($date);

        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;
        $hours = $interval->h;
        $minutes = $interval->i;
        $seconds = $interval->s;

        $totalMinutes = ($years * 365 * 24 * 60) + ($months * 30 * 24 * 60) + ($days * 24 * 60) + ($hours * 60) + $minutes;

        $totalSeconds = ($totalMinutes * 60) + $seconds;
        if ($years >= 1) {
            if ($years == 1 && $months == 1) {
                return $date->format('l, F j, Y');
            } elseif ($years == 1 && $months > 1) {
                return $date->format('l, F j, Y');
            } elseif ($years > 1 && $months == 1) {
                return $date->format('l, F j, Y');
            } else {
                return $date->format('l, F j, Y');
            }
        } elseif ($months >= 1) {
            if ($months == 1) {
                return $date->format('F j');
            } else {
                return  $date->format('F j');
            }
        } elseif ($days >= 1) {
            if ($days == 1) {
                return "Yesterday";
            } else {
                if ($days < 6) {
                    return $date->format('l');
                } else {
                    return $date->format('F j');
                }
            }
        } elseif ($hours >= 1) {
            if ($hours == 1) {
                return $interval->format('%h hour ago');
            } else {
                return $interval->format('%h hours ago');
            }
        } elseif ($minutes >= 1) {
            if ($minutes == 1) {
                return $interval->format('%i minute ago');
            } else {
                return $interval->format('%i minutes ago');
            }
        } else {
            return "Just now";
        }
    }
    function get_time3($pasttime)
    {
        $today = new DateTime();
        $date = new DateTime($pasttime);
        $interval = $today->diff($date);

        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;
        $hours = $interval->h;
        $minutes = $interval->i;
        $seconds = $interval->s;

        $totalMinutes = ($years * 365 * 24 * 60) + ($months * 30 * 24 * 60) + ($days * 24 * 60) + ($hours * 60) + $minutes;

        $totalSeconds = ($totalMinutes * 60) + $seconds;
        return $date->format('F j, Y');
    }
    function get_future_time($futureTimeString)
    {
        // Current time
        $currentDateTime = new DateTime();

        // Future time (parse from given string in 'Y-m-d' format)
        $futureDateTime = DateTime::createFromFormat('Y-m-d', $futureTimeString);

        // Check if the parsing was successful
        if ($futureDateTime === false) {
            return "Invalid date format. Please use the format: 'YYYY-MM-DD'.";
        }

        // Calculate the difference
        $interval = $currentDateTime->diff($futureDateTime);

        // Check if the future time is in the past
        if ($interval->invert === 1) {
            return "Today";
        }
        if ($interval->invert < 1) {
            return "The specified time is in the past!";
        }

        // Create a readable output for the future date: "Saturday, 28 September"
        $formattedFutureDate = $futureDateTime->format('l, d F');
        return $formattedFutureDate;
    }
    function get_time($pasttime, $today = 0, $differenceformat = '%y')
    {

        // $today = date("Y-m-d H:i:s");
        $today = date("Y-m-d ") . date("H") - 1 . date(":i:s");
        if (date("H") <= 9) {
            $today = date("Y-m-d ") . "0" . date("H") - 1 . date(":i:s");
        }
        if (date("H") == "00") {
            $today = date("Y-m-d ") . 23 . date(":i:s");
        }
        $datetime1 = date_create($pasttime);
        $datetime2 = date_create($today);

        $interval = date_diff($datetime1, $datetime2);
        $answerY = $interval->format($differenceformat);

        $differenceformat = '%m';
        $answerM = $interval->format($differenceformat);

        $differenceformat = '%d';
        $answer = $interval->format($differenceformat);

        $differenceformat = '%H';
        $answer2 = $interval->format($differenceformat);

        if ($answerY >= 1) {

            $answerY = date(" F j, Y", strtotime($pasttime));
            return $answerY;
        } else if ($answerM >= 1) {

            $answerM = date("F j, Y ", strtotime($pasttime));
            return $answerM;
        } elseif ($answer > 2) {
            return $answer . " days " . "ago";
        } elseif ($answer == 2 && $answer2 > 0) {
            return $answer . " days " . "ago";
        } elseif ($answer == 1 && $answer2 > 1) {
            return $answer . "d, " . $answer2 . "hr ago";
        } else {
            $differenceformat = '%h';
            $answer = $interval->format($differenceformat);

            $differenceformat = '%i';
            $answer2 = $interval->format($differenceformat);
            if (($answer < 24) && ($answer > 1)) {
                return $answer .  " hr ago";
            } else {
                $differenceformat = '%i';
                $answer = $interval->format($differenceformat);

                if (($answer < 60) && ($answer > 1)) {
                    return $answer . "m ago";
                } elseif ($answer == 1) {
                    return "Just now";
                } else {

                    $differenceformat = '$s';
                    $answer = $interval->format($differenceformat);
                    if (($answer < 60) && ($answer > 10)) {
                        return "Just now";
                    } elseif ($answer < 10) {
                        return "Just now";
                    }
                }
            }
        }
    }
}
