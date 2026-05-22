<?php
// Cron: 10 0 * * * php /path/to/cron/daily-report.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/mobile.php';
require_once __DIR__ . '/../classes/reports.php';
require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';

$date = date('Y-m-d', strtotime('yesterday'));

$stmt      = $pdo->query("SELECT id, uid, name, email FROM TblResellers WHERE isActive = 1");
$resellers = $stmt->fetchAll();

foreach ($resellers as $reseller) {
    $mobile       = new Mobile();
    $transactions = $mobile->getByUid($reseller['uid'], $date, $date);

    if (empty($transactions)) continue;

    $accountName   = strtoupper(str_replace(' ', '_', $reseller['name']));
    $dateFormatted = str_replace('-', '', $date);
    $year          = date('Y', strtotime($date));
    $month         = date('m', strtotime($date));
    $day           = date('d', strtotime($date));
    $folder        = REPORT_PATH . $accountName . '/' . $year . '/' . $month . '/' . $day . '/';

    if (!is_dir($folder)) mkdir($folder, 0755, true);

    $fileName = $accountName . '_' . $dateFormatted . '.pdf';
    $filePath = $folder . $fileName;

    $totalAmount = array_sum(array_column($transactions, 'amount'));
    $totalDebit  = array_sum(array_column($transactions, 'debit'));

    // TODO: Generate PDF using TCPDF matching Transaction Summary format

    $reports = new Reports();
    $reports->log([
        ':uid'               => $reseller['uid'],
        ':accountName'       => $accountName,
        ':reportDate'        => $date,
        ':filePath'          => $filePath,
        ':totalTransactions' => count($transactions),
        ':totalAmount'       => $totalAmount,
        ':totalDebit'        => $totalDebit,
        ':sentTo'            => $reseller['email']
    ]);
}

echo "Daily report done for $date\n";
