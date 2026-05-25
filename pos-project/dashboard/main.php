<?php
ob_start();
session_start();
if (empty($_SESSION['reseller'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$db = Connection::get();
$rid = (int)$_SESSION['reseller']['id'];

$stmt = $db->prepare("SELECT balance, creditLimit FROM TblAccounts WHERE resellerId=? LIMIT 1");
$stmt->bind_param('i', $rid); $stmt->execute();
$account = $stmt->get_result()->fetch_assoc() ?? []; $stmt->close();

$stmt = $db->prepare("SELECT COUNT(*) c, COALESCE(SUM(debit),0) d FROM TblTransactions WHERE resellerId=? AND serviceType='mobile' AND DATE(createdAt)=CURDATE()");
$stmt->bind_param('i', $rid); $stmt->execute();
$mobile = $stmt->get_result()->fetch_assoc(); $stmt->close();

$stmt = $db->prepare("SELECT COUNT(*) c, COALESCE(SUM(debit),0) d FROM TblTransactions WHERE resellerId=? AND serviceType='tv' AND DATE(createdAt)=CURDATE()");
$stmt->bind_param('i', $rid); $stmt->execute();
$tv = $stmt->get_result()->fetch_assoc(); $stmt->close();

$stmt = $db->prepare("SELECT COUNT(*) c, COALESCE(SUM(debit),0) d FROM TblTransactions WHERE resellerId=? AND serviceType='electricity' AND DATE(createdAt)=CURDATE()");
$stmt->bind_param('i', $rid); $stmt->execute();
$elec = $stmt->get_result()->fetch_assoc(); $stmt->close();

$stmt = $db->prepare("SELECT * FROM TblTransactions WHERE resellerId=? ORDER BY createdAt DESC LIMIT 10");
$stmt->bind_param('i', $rid); $stmt->execute();
$recent = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();

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
  <title>Dashboard — POS Urafiki</title>
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
          <h1 class="fs-3 mb-1">Dashboard</h1>
          <p class="text-muted mb-0"><?= date('d/m/Y') ?></p>
        </div>
      </div>
      <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
          <div class="card p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
            <div class="d-flex gap-3">
              <div class="icon-shape icon-md bg-primary text-white rounded-2"><i class="ti ti-wallet fs-4"></i></div>
              <div>
                <h2 class="mb-1 fs-6">Saldo Disponível</h2>
                <h3 class="fw-bold mb-0"><?= number_format($account['balance'] ?? 0, 2, ',', '.') ?> MT</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25">
            <div class="d-flex gap-3">
              <div class="icon-shape icon-md bg-success text-white rounded-2"><i class="ti ti-device-mobile fs-4"></i></div>
              <div>
                <h2 class="mb-1 fs-6">Mobile Hoje</h2>
                <h3 class="fw-bold mb-0"><?= $mobile['c'] ?></h3>
                <p class="text-success mb-0 small">Débito: <?= number_format($mobile['d'], 2, ',', '.') ?> MT</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25">
            <div class="d-flex gap-3">
              <div class="icon-shape icon-md bg-warning text-white rounded-2"><i class="ti ti-tv fs-4"></i></div>
              <div>
                <h2 class="mb-1 fs-6">TV Hoje</h2>
                <h3 class="fw-bold mb-0"><?= $tv['c'] ?></h3>
                <p class="text-warning mb-0 small">Débito: <?= number_format($tv['d'], 2, ',', '.') ?> MT</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card p-4 bg-danger bg-opacity-10 border border-danger border-opacity-25">
            <div class="d-flex gap-3">
              <div class="icon-shape icon-md bg-danger text-white rounded-2"><i class="ti ti-bolt fs-4"></i></div>
              <div>
                <h2 class="mb-1 fs-6">Electricidade Hoje</h2>
                <h3 class="fw-bold mb-0"><?= $elec['c'] ?></h3>
                <p class="text-danger mb-0 small">Débito: <?= number_format($elec['d'], 2, ',', '.') ?> MT</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3">
              <h4 class="mb-0 h5">Últimas Transacções</h4>
              <a href="relatorios.php" class="small text-primary text-decoration-underline">Ver Todas</a>
            </div>
            <div class="table-responsive">
              <table class="table mb-0 text-nowrap table-hover">
                <thead class="table-light">
                  <tr>
                    <th class="px-4">MSISDN / Conta</th>
                    <th>Serviço</th>
                    <th>Valor</th>
                    <th>Débito</th>
                    <th>Data/Hora</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($recent)): ?>
                  <tr><td colspan="6" class="text-center py-4 text-muted">Sem transacções.</td></tr>
                  <?php else: foreach ($recent as $t): ?>
                  <tr class="align-middle">
                    <td class="px-4"><?= htmlspecialchars($t['msisdn'] ?? $t['accountRef'] ?? '—') ?></td>
                    <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($t['serviceType'] ?? '—') ?></span></td>
                    <td><?= number_format($t['amount'] ?? 0, 2, ',', '.') ?> MT</td>
                    <td><?= number_format($t['debit'] ?? 0, 2, ',', '.') ?> MT</td>
                    <td><?= date('d/m H:i', strtotime($t['createdAt'])) ?></td>
                    <td><?= $badge($t['estado'] ?? -1) ?></td>
                  </tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>
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
