<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/../classes/resellers.php';
require_once __DIR__ . '/../classes/mobile.php';
require_once __DIR__ . '/../classes/reports.php';

// Run via cron at 00:10 every day
// 10 0 * * * php /path/to/urafiki-pos/cron/daily-report.php

$date     = date('Y-m-d', strtotime('yesterday'));
$db       = DB::getInstance();

$stmt = $db->query("SELECT id, uid, name FROM TblResellers WHERE isActive = 1");
$resellers = $stmt->fetchAll();

foreach ($resellers as $reseller) {
    $mobile       = new Mobile();
    $transactions = $mobile->getByUid($reseller['uid'], $date, $date);

    if (empty($transactions)) continue;

    $accountName  = strtoupper(str_replace(' ', '_', $reseller['name']));
    $dateFormatted = str_replace('-', '', $date);
    $folder       = REPORT_PATH . $accountName . '/' . date('Y', strtotime($date)) . '/' . date('m', strtotime($date)) . '/' . date('d', strtotime($date)) . '/';

    if (!is_dir($folder)) mkdir($folder, 0755, true);

    $fileName = $accountName . '_' . $dateFormatted . '.pdf';
    $filePath = $folder . $fileName;

    // TODO: Generate PDF using TCPDF/FPDF matching Transaction Summary format

    $totalAmount = array_sum(array_column($transactions, 'amount'));
    $totalDebit  = array_sum(array_column($transactions, 'debit'));

    $reports = new Reports();
    $reports->log([
        ':uid'               => $reseller['uid'],
        ':accountName'       => $accountName,
        ':reportDate'        => $date,
        ':filePath'          => $filePath,
        ':totalTransactions' => count($transactions),
        ':totalAmount'       => $totalAmount,
        ':totalDebit'        => $totalDebit,
        ':sentTo'            => ''  // TODO: add email per reseller
    ]);
}
