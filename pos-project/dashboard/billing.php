<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/common/session/session.php';
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$pageTitle = 'Extracto';
$uid       = $sessionReseller['uid'];
$db        = Connection::get();

$dateFrom = $_GET['from'] ?? date('Y-m', strtotime('-30 days')) . '-01';
$dateTo   = $_GET['to']   ?? date('Y-m-d');

$stmt = $db->prepare(
    "SELECT 'Mobile' as tipo, transactionId, msisdn as referencia, amount, debit, estado, createdAt
     FROM TblMobile WHERE uid = ? AND DATE(createdAt) >= ? AND DATE(createdAt) <= ?
     UNION ALL
     SELECT 'TV' as tipo, transactionId, decoderNumber as referencia, amount, debit, estado, createdAt
     FROM TblTv WHERE uid = ? AND DATE(createdAt) >= ? AND DATE(createdAt) <= ?
     UNION ALL
     SELECT 'Electricity' as tipo, transactionId, meterNumber as referencia, amount, debit, estado, createdAt
     FROM TblElectricity WHERE uid = ? AND DATE(createdAt) >= ? AND DATE(createdAt) <= ?
     ORDER BY createdAt DESC"
);
$stmt->bind_param(
    'sssssssss',
    $uid, $dateFrom, $dateTo,
    $uid, $dateFrom, $dateTo,
    $uid, $dateFrom, $dateTo
);
$stmt->execute();
$entries = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalDebit = array_sum(array_column($entries, 'debit'));

$tipoColor = ['Mobile' => 'info', 'TV' => 'warning', 'Electricity' => 'danger'];
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

  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body pb-2">
          <form method="get" class="row g-3 align-items-end">
            <div class="col-md-4">
              <label class="form-label text-xs">De</label>
              <input type="date" name="from" class="form-control form-control-sm" value="<?= htmlspecialchars($dateFrom) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label text-xs">Até</label>
              <input type="date" name="to" class="form-control form-control-sm" value="<?= htmlspecialchars($dateTo) ?>">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-sm bg-gradient-info w-100">Filtrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-center"><div class="card-body">
        <h5 class="mb-0"><?= count($entries) ?></h5>
        <p class="text-sm text-muted mb-0">Transacções no período</p>
      </div></div>
    </div>
    <div class="col-md-6">
      <div class="card text-center"><div class="card-body">
        <h5 class="mb-0 text-danger"><?= number_format($totalDebit, 2) ?> MT</h5>
        <p class="text-sm text-muted mb-0">Total Debitado</p>
      </div></div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Extracto — <?= date('d/m/Y', strtotime($dateFrom)) ?> a <?= date('d/m/Y', strtotime($dateTo)) ?></h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Serviço</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Referência</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Débito</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                  <th class="text-secondary text-xxs opacity-7">Data/Hora</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($entries)): ?>
                <tr><td colspan="6" class="text-center text-sm py-4 text-muted">Nenhum movimento no período.</td></tr>
                <?php else: ?>
                <?php foreach ($entries as $e): ?>
                <tr>
                  <td class="ps-4">
                    <span class="badge badge-sm bg-gradient-<?= $tipoColor[$e['tipo']] ?? 'secondary' ?>"><?= $e['tipo'] ?></span>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($e['referencia']) ?></p>
                    <p class="text-xxs text-secondary mb-0"><?= htmlspecialchars($e['transactionId']) ?></p>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold"><?= number_format($e['amount'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold text-danger"><?= number_format($e['debit'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <?php $es = (int)$e['estado']; ?>
                    <?php if ($es === 1): ?>
                    <span class="badge badge-sm bg-gradient-success">OK</span>
                    <?php elseif ($es === 0): ?>
                    <span class="badge badge-sm bg-gradient-secondary">Pend.</span>
                    <?php elseif ($es === 5): ?>
                    <span class="badge badge-sm bg-gradient-warning">Dup.</span>
                    <?php else: ?>
                    <span class="badge badge-sm bg-gradient-danger">Erro</span>
                    <?php endif; ?>
                  </td>
                  <td><span class="text-xs text-secondary"><?= date('d/m H:i', strtotime($e['createdAt'])) ?></span></td>
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
