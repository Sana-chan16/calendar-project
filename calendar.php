<?php 
date_default_timezone_set("Asia/Manila");

if ($_GET) {

    $currentDate = getdate($_GET["timestamp"]);
    $currentTimestamp = intval($_GET["timestamp"]);

    $lastMonthDate = mktime(0, 0, 0, $currentDate["mon"] - 1, $currentDate["mday"], $currentDate["year"]);
    $nextMonthDate = mktime(0, 0, 0, $currentDate["mon"] + 1, $currentDate["mday"], $currentDate["year"]);

    $numOfDaysForThisMonth = ($nextMonthDate - $currentTimestamp) / 3600 / 24;
    $startingWeekDay = getdate(mktime(0, 0, 0, $currentDate["mon"], 1, $currentDate["year"]));
} else {
    $currentDate = getdate();
    $currentTimestamp = time();
    $lastMonthDate = strtotime("last month");
    $nextMonthDate = strtotime("next month");

    $numOfDaysForThisMonth = ($nextMonthDate - $currentTimestamp) / 3600 / 24;
    $startingWeekDay = getdate(mktime(0, 0, 0, $currentDate["mon"], 1, $currentDate["year"]));
} 

$today = date("j");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <style>
        #cal-table {
            margin: 0 auto;
            width: 99vw;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 1.8rem;
            table-layout: fixed;
        }
        #cal-table tr:first-of-type {
            background-color: skyblue;
            font-size: 2rem;
            text-align: center;
        }
        tr:first-of-type a {
            display: inline-block;
            width: 100%;
            text-decoration: none;
            color: black;
        }
        tr:first-of-type a:hover {
            color: white;
            background-color: blue;
        }
        .day-headings {
            text-align: center;
            font-weight: normal;
            border-bottom: 1px solid black;
        }
        .day-cells {
            height: 4em;
            line-height: 4em;
            vertical-align: middle;
            text-align: center;
            border: 1px solid black;
            transition: background-color 0.3s; 
        }
        .day-cells:hover {
            background-color: pink; 
        }
        .today {
            font-size: 50px;
            color: brown;
        }
        
    </style>
</head>
<body>
    <table id="cal-table">
        <tr>
            <td><a href="<?php echo $_SERVER["PHP_SELF"]. "?timestamp=".$lastMonthDate ?>"><< Previous</a> </td>
            <th colspan="5" style="text-align: center;">
                <div style= "text-align:left; float:left;"><a href="<?php echo $_SERVER["PHP_SELF"]. "?timestamp=".time()?>">Today</a></div>
                <div style="display: inline-block; text-align: center;"><?php echo $currentDate["month"].", ".$currentDate["year"]; ?></div>
            </th>

            <td><a href="<?php echo $_SERVER["PHP_SELF"]. "?timestamp=".$nextMonthDate ?>">Next >></a></td>
        </tr>
        <tr>
            <td class="day-headings">Sunday</td>
            <td class="day-headings">Monday</td>
            <td class="day-headings">Tuesday</td>
            <td class="day-headings">Wednesday</td>
            <td class="day-headings">Thursday</td>
            <td class="day-headings">Friday</td>
            <td class="day-headings">Saturday</td>
        </tr>

        <?php
        function renderColumn($repeat = null, $dayOfWeek = null) {
            global $today; 
            if ($repeat > 0 || $repeat !== null) {
                for ($repetition = 0; $repetition < $repeat; $repetition++) {
                    echo "\t<td class=\"day-cells\">\n";
        
                    if ($repetition === ($repeat)) {
                        if ($dayOfWeek == $today) {
                            echo "\t\t<strong class='today'>" . $dayOfWeek . "</strong>\n"; 
                        } else {
                            echo "\t\t" . $dayOfWeek . "\n"; 
                        }
                    } else {
                        echo "&nbsp;";
                    }
                    echo "\t</td>\n";
                }
            } else {
                echo "\t<td class=\"day-cells\">\n";
                if ($dayOfWeek == $today) {
                    echo "\t\t<strong class='today'>" . $dayOfWeek . "</strong>\n"; 
                } else {
                    echo "\t\t" . $dayOfWeek . "\n"; 
                }
                echo "\t</td>\n";
            }
        }

        $dayCounter = 1;
        $daysInAWeek = 7;
        $endRendering = false;

        while ($dayCounter <= $numOfDaysForThisMonth) {
            $colCounter = 0;
            echo "<tr>\n";
            while ($colCounter < $daysInAWeek) {

                if ($colCounter === 0 && $dayCounter === 1) {
                    match ($startingWeekDay["wday"]) {
                        0 => renderColumn(0, $dayCounter),
                        1 => renderColumn(1, $dayCounter),
                        2 => renderColumn(2, $dayCounter),
                        3 => renderColumn(3, $dayCounter),
                        4 => renderColumn(4, $dayCounter),
                        5 => renderColumn(5, $dayCounter),
                        6 => renderColumn(6, $dayCounter),
                    };

                    $colCounter += $startingWeekDay["wday"];
                }

                if ($dayCounter === $numOfDaysForThisMonth) {
                    renderColumn(null, $dayCounter);
                    $endRendering = true;
                    break;
                } else {
                    renderColumn(null, $dayCounter);
                    $colCounter++;
                    $dayCounter++;
                }
            }
            echo "</tr>\n";
            
            if ($endRendering) {
                break;
            }
        }
        ?>
    </table>
</body>
</html>