<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('데이터베이스 연결 실패: '. $conn->connect_error);
}

$exerciseDate = $_POST['exercise_date'];
$exerciseName = $_POST['exercise_name'];

$sql = "INSERT INTO exercise_records (exercise_date, exercise_name) VALUES ('$exerciseDate','$exerciseName')";

if ($conn->query($sql) === TRUE) {
    echo "운동 기록이 성공적으로 저장되었습니다.";
    sleep(2);
    header("Location: down_exercise.php"); // 자동 이동을 위한 헤더 설정
    exit;
} else {
    echo "운동 기록 저장 실패: ". $conn->error;
}

$conn->close();
?>
