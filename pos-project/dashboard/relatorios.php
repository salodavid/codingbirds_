<?php
ob_start();
session_start();
if (empty($_SESSION['reseller'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$db = Connection::get();
$rid = (int)$_SESSION['reseller']['id'];

$dateFrom = $_GET['from'] ?? date('Y-m-01');
$dateTo   = $_GET['to']   ?? date('Y-m-d');

$stmt = $db->prepare("SELECT COUNT(*) c, COALESCE(SUM(amount),0) vol, COALESCE(SUM(debit),0) deb, SUM(estado=1) ok FROM TblTransactions WHERE resellerId=? AND DATE(createdAt) BETWEEN ? AND ?");
$stmt->bind_param('iss', $rid, $dateFrom, $dateTo); $stmt->execute();
$totals = $stmt->get_result()->fetch_assoc(); $stmt->close();

$stmt = $db->prepare("SELECT * FROM TblTransactions WHERE resellerId=? AND DATE(createdAt) BETWEEN ? AND ? ORDER BY createdAt DESC");
$stmt->bind_param('iss', $rid, $dateFrom, $dateTo); $stmt->execute();
$transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();

$badge = fn($e) => match((int)$e) {
    1 => '<span class="badge bg-success-subtle text-success border border-success">Sucesso</span>',
    0 => '<span class="badge bg-secondary-subtle text-secondary">Pendente</span>',
    5 => '<span class="badge bg-warning-subtle text-warning border border-warning">Duplicado</span>',
    default => '<span class="badge bg-danger-subtle text-danger border border-danger">Erro</span>',
};
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Relatórios — POS Urafiki</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div id="overlay" class="overlay"></div>
  <?php require_once __DIR__ . '/common/partials/topbar.php'; ?>
  <?php require_once __DIR__ . '/common/partials/sidebar.php'; ?>
  <main id="content" class="content py-10">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-12">
          <h1 class="fs-3 mb-1">Relatórios</h1>
          <p class="text-muted mb-0">Histórico de transacções</p>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-12">
          <form method="get" class="d-flex gap-2 flex-wrap align-items-end">
            <div>
              <label class="form-label mb-1 small">De</label>
              <input type="date" class="form-control form-control-sm" name="from" value="<?= htmlspecialchars($dateFrom) ?>">
            </div>
            <div>
              <label class="form-label mb-1 small">Até</label>
              <input type="date" class="form-control form-control-sm" name="to" value="<?= htmlspecialchars($dateTo) ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
          </form>
        </div>
      </div>
      <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card h-100"><div class="card-body p-4">
            <h6 class="mb-3">Total Transacções</h6>
            <h3 class="fw-bold mb-0"><?= $totals['c'] ?></h3>
          </div></div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card h-100"><div class="card-body p-4">
            <h6 class="mb-3">Volume Total</h6>
            <h3 class="fw-bold mb-0"><?= number_format($totals['vol'], 2, ',', '.') ?> MT</h3>
          </div></div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card h-100"><div class="card-body p-4">
            <h6 class="mb-3">Sucessos</h6>
            <h3 class="fw-bold mb-0 text-success"><?= $totals['ok'] ?></h3>
          </div></div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card h-100"><div class="card-body p-4">
            <h6 class="mb-3">Débito Total</h6>
            <h3 class="fw-bold mb-0"><?= number_format($totals['deb'], 2, ',', '.') ?> MT</h3>
          </div></div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card table-responsive">
            <table class="table mb-0 text-nowrap table-hover">
              <thead class="table-light">
                <tr>
                  <th class="px-4">Data/Hora</th>
                  <th>MSISDN / Conta</th>
                  <th>Serviço</th>
                  <th>Valor</th>
                  <th>Débito</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($transactions)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">Sem transacções no período seleccionado.</td></tr>
                <?php else: foreach ($transactions as $t): ?>
                <tr class="align-middle">
                  <td class="px-4"><?= date('d/m/Y H:i', strtotime($t['createdAt'])) ?></td>
                  <td><?= htmlspecialchars($t['msisdn'] ?? $t['accountRef'] ?? '—') ?></td>
                  <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($t['serviceType'] ?? '—') ?></span></td>
                  <td><?= number_format($t['amount'] ?? 0, 2, ',', '.') ?> MT</td>
                  <td><?= number_format($t['debit'] ?? 0, 2, ',', '.') ?> MT</td>
                  <td><?= $badge($t['estado'] ?? -1) ?></td>
                </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <footer class="text-center py-3 mt-4 text-secondary">
        <p class="mb-0 small">© <?= date('Y') ?> Urafiki Lda — pos.urafiki.co.mz</p>
      </footer>
    </div>
  </main>
  <script src="assets/js/main.js" type="module"></script>
</body>
</html>
