<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/common/session/session.php';
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$pageTitle = 'Relatórios PDF';
$uid       = $sessionReseller['uid'];
$db        = Connection::get();

$stmt = $db->prepare(
    "SELECT * FROM TblReports WHERE uid = ? ORDER BY reportDate DESC LIMIT 90"
);
$stmt->bind_param('s', $uid);
$stmt->execute();
$reportsList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>POS Urafiki — <?= $pageTitle ?></title>
  <link rel="icon" type="image/png" href="assets/img/logos/urafiki-icon.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="assets/css/nucleo-icons.css" rel="stylesheet">
  <link href="assets/css/nucleo-svg.css" rel="stylesheet">
  <link href="assets/css/soft-ui-dashboard.min.css" rel="stylesheet">
</head>
<body class="g-sidenav-show bg-gray-100">
<?php include __DIR__ . '/menu/menu.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<?php include __DIR__ . '/menu/search.php'; ?>
<div class="container-fluid py-4">

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Relatórios Diários PDF (últimos 90 dias)</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Transacções</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Débito</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Enviado</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PDF</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($reportsList)): ?>
                <tr><td colspan="6" class="text-center text-sm py-4 text-muted">Nenhum relatório disponível ainda.</td></tr>
                <?php else: ?>
                <?php foreach ($reportsList as $r): ?>
                <tr>
                  <td class="ps-4">
                    <p class="text-xs font-weight-bold mb-0"><?= date('d/m/Y', strtotime($r['reportDate'])) ?></p>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold"><?= $r['totalTransactions'] ?></span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold"><?= number_format($r['totalAmount'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold"><?= number_format($r['totalDebit'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center">
                    <?php if ($r['sentAt']): ?>
                    <span class="badge badge-sm bg-gradient-success"><?= date('d/m H:i', strtotime($r['sentAt'])) ?></span>
                    <?php else: ?>
                    <span class="badge badge-sm bg-gradient-secondary">Pendente</span>
                    <?php endif; ?>
                  </td>
                  <td class="align-middle text-center">
                    <?php if ($r['filePath'] && file_exists($r['filePath'])): ?>
                    <a href="<?= APP_URL ?>reports/<?= urlencode($r['accountName']) ?>/<?= date('Y/m/d', strtotime($r['reportDate'])) ?>/<?= urlencode(basename($r['filePath'])) ?>"
                       target="_blank" class="btn btn-link text-info text-sm mb-0 px-0">
                      <i class="ni ni-cloud-download-95 me-1"></i>Download
                    </a>
                    <?php else: ?>
                    <span class="text-xs text-muted">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include __DIR__ . '/footer/footer.php'; ?>
</div>
</main>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="assets/js/soft-ui-dashboard.min.js"></script>
</body>
</html>
