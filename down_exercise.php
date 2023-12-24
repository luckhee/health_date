<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('데이터베이스 연결 실패: '. $conn->connect_error);
}

$sql = "SELECT * FROM exercise_records";
$result = $conn->query($sql);

// 저장된 운동 기록 데이터를 배열로 저장
$exerciseData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = $row["exercise_date"];
        $exerciseName = $row["exercise_name"];

        if (!isset($exerciseData[$date])) {
            $exerciseData[$date] = [];
        }

        $exerciseData[$date][] = $exerciseName;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>운동 기록 달력</title>
    <style>
        table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #ccc;
        }
        .exercise-list {
            margin-top: 20px;
        }
        .exercise-details {
            margin-top: 20px;
        }
    </style>
    <script>
        function showExerciseDetails(date) {
            var exerciseData = <?php echo json_encode($exerciseData); ?>;

            var detailsContainer = document.getElementById("exercise-details-container");
            var detailsContent = "";

            if (exerciseData.hasOwnProperty(date)) {
                detailsContent += "<ul>";
                exerciseData[date].forEach(function(exercise) {
                    detailsContent += "<li>" + exercise + "</li>";
                });
                detailsContent += "</ul>";
            } else {
                detailsContent = "운동 기록이 없습니다.";
            }

            detailsContainer.innerHTML = detailsContent;
        }
    </script>
</head>
<body>
    <h1>운동 기록 달력</h1>

    <?php
    // 현재 년도와 월
    $year = date('Y');
    $month = date('n');

    // 월의 첫째 날과 마지막 날 계산
    $firstDayOfMonth = strtotime("$year-$month-01");
    $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

    // 이번 달 첫째 날의 요일과 마지막 날의 날짜
    $firstDayOfWeek = date('N', $firstDayOfMonth);
    $lastDay = date('d', $lastDayOfMonth);

    // 달력 출력
    echo "<table>";
    echo "<tr>";
    echo "<th>일</th>";
    echo "<th>월</th>";
    echo "<th>화</th>";
    echo "<th>수</th>";
    echo "<th>목</th>";
    echo "<th>금</th>";
    echo "<th>토</th>";
    echo "</tr>";

    $day = 1;
    $totalDays = $lastDay + $firstDayOfWeek - 1;

    while ($day <= $totalDays) {
        echo "<tr>";

        for ($i = 0; $i < 7; $i++) {
            if ($day >= $firstDayOfWeek && $day - $firstDayOfWeek + 1 <= $lastDay) {
                $currentDay = $day - $firstDayOfWeek + 1;
                $date = date('Y-m-d', strtotime("$year-$month-$currentDay"));

                echo "<td>";
                echo "<a href='javascript:void(0)' onclick='showExerciseDetails(\"$date\")'>$currentDay</a>";
                echo "</td>";
            } else {
                echo "<td></td>";
            }
            $day++;
        }

        echo "</tr>";
    }

    echo "</table>";
    ?>

    <div id="exercise-details-container" class="exercise-details"></div>
</body>
</html>
