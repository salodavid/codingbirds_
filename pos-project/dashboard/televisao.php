<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/common/session/session.php';
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$pageTitle = 'Televisão';
$uid       = $sessionReseller['uid'];
$db        = Connection::get();

$dateFrom = $_GET['from'] ?? date('Y-m-d');
$dateTo   = $_GET['to']   ?? date('Y-m-d');

$stmt = $db->prepare(
    "SELECT * FROM TblTv WHERE uid = ? AND DATE(createdAt) >= ? AND DATE(createdAt) <= ? ORDER BY createdAt DESC"
);
$stmt->bind_param('sss', $uid, $dateFrom, $dateTo);
$stmt->execute();
$transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalAmount = array_sum(array_column($transactions, 'amount'));
$totalDebit  = array_sum(array_column($transactions, 'debit'));

$estadoLabel = fn($e) => match((int)$e) {
    1       => '<span class="badge badge-sm bg-gradient-success">Sucesso</span>',
    0       => '<span class="badge badge-sm bg-gradient-secondary">Pendente</span>',
    5       => '<span class="badge badge-sm bg-gradient-warning">Duplicado</span>',
    default => '<span class="badge badge-sm bg-gradient-danger">Erro</span>',
};
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
    <div class="col-md-4">
      <div class="card text-center"><div class="card-body">
        <h5 class="mb-0"><?= count($transactions) ?></h5>
        <p class="text-sm text-muted mb-0">Transacções</p>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card text-center"><div class="card-body">
        <h5 class="mb-0"><?= number_format($totalAmount, 2) ?> MT</h5>
        <p class="text-sm text-muted mb-0">Total Pago</p>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card text-center"><div class="card-body">
        <h5 class="mb-0"><?= number_format($totalDebit, 2) ?> MT</h5>
        <p class="text-sm text-muted mb-0">Total Debitado</p>
      </div></div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Transacções Televisão — <?= date('d/m/Y', strtotime($dateFrom)) ?> a <?= date('d/m/Y', strtotime($dateTo)) ?></h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Decoder</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pacote</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Débito</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                  <th class="text-secondary text-xxs opacity-7">Data/Hora</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($transactions)): ?>
                <tr><td colspan="6" class="text-center text-sm py-4 text-muted">Nenhuma transacção encontrada.</td></tr>
                <?php else: ?>
                <?php foreach ($transactions as $t): ?>
                <tr>
                  <td class="ps-4">
                    <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($t['decoderNumber']) ?></p>
                    <p class="text-xs text-secondary mb-0"><?= htmlspecialchars($t['transactionId']) ?></p>
                  </td>
                  <td><p class="text-xs text-secondary mb-0"><?= htmlspecialchars($t['package'] ?? '—') ?></p></td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold"><?= number_format($t['amount'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold"><?= number_format($t['debit'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center text-sm"><?= $estadoLabel($t['estado']) ?></td>
                  <td><span class="text-xs text-secondary"><?= date('d/m H:i', strtotime($t['createdAt'])) ?></span></td>
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
