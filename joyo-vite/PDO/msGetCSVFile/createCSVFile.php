<?php
// 直接產生csv檔案到指定路徑
include("../conn.php");
$dsn = 'mysql:host=' . $host . ';dbname=' . $select . ";charset=utf8";

$pdo = new PDO($dsn, $user, $pas);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$startDate = date('Y-m-d', strtotime($_GET['startDate']));
$endDate = date('Y-m-d', strtotime($_GET['endDate']));


$sql = 'SELECT BUY_ID, TOTAL_PRICE, DATE, MAIL FROM BUY
LEFT JOIN MEMBER ON BUY.MEMBER_ID = MEMBER.MEMBER_ID
WHERE DATE BETWEEN "' . $startDate . '" AND "' . $endDate . '"
ORDER BY DATE DESC';

$statement = $pdo->prepare($sql);
$statement->execute();
$data = $statement->fetchAll();
if (count($data) > 0) {
    $outputFilePath = '../../public/csvs/'.$startDate.'_'.$endDate.'.csv';

    // 打开输出流，将CSV数据写入文件
    $output = fopen($outputFilePath, 'w');

    // 写入CSV文件的表头
    fputcsv($output, array_keys($data[0]));

    // 逐行写入数据
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // 关闭输出流
    fclose($output);

    // 输出成功消息或进行其他操作
    echo "文件保存成功";
} else {
    echo "查询失败";
}
?>