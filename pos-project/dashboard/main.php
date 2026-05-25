<?php
ob_start();
session_start();
if (empty($_SESSION['reseller'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$db = Connection::get();
$resellerId = $_SESSION['reseller']['id'];

// Fetch account balance
$stmt = $db->prepare("SELECT balance, creditLimit FROM TblAccounts WHERE resellerId = ? LIMIT 1");
$stmt->bind_param('i', $resellerId);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();
$stmt->close();

$balance     = $account['balance']     ?? 0;
$creditLimit = $account['creditLimit'] ?? 0;

// Count today's transactions
$stmt = $db->prepare("SELECT COUNT(*) as total, COALESCE(SUM(amount),0) as soma FROM TblTransactions WHERE resellerId = ? AND DATE(createdAt) = CURDATE()");
$stmt->bind_param('i', $resellerId);
$stmt->execute();
$today = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Urafiki &mdash; Dashboard</title>
  <!-- Tabler icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <!-- Custom theme -->
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    body { background-color: #f4f6f9; }
    .sidebar { z-index: 1030; }
    .card-stat { border: none; border-radius: .75rem; }
    .card-stat .card-body { padding: 1.25rem 1.5rem; }
    .card-stat .stat-icon { width: 52px; height: 52px; border-radius: .5rem;
      display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .nav-link { border-radius: .375rem; color: #495057; font-size: .875rem; padding: .45rem .75rem; }
    .nav-link:hover, .nav-link.active { background-color: #e8f4ff; color: #0d6efd; }
    .nav-link.active { font-weight: 600; }
  </style>
</head>
<body>

<div class="d-flex">

  <!-- ==================== SIDEBAR ==================== -->
  <?php require_once __DIR__ . '/common/partials/sidebar.php'; ?>

  <!-- ==================== PAGE WRAPPER ==================== -->
  <div class="flex-grow-1 d-flex flex-column" style="min-width:0;">

    <!-- ==================== TOPBAR ==================== -->
    <?php require_once __DIR__ . '/common/partials/topbar.php'; ?>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main id="content" class="content py-4 px-3 px-md-4 flex-grow-1">
      <div class="container-fluid">

        <!-- Page heading -->
        <div class="mb-4">
          <h5 class="fw-bold mb-0">Painel Principal</h5>
          <p class="text-muted mb-0" style="font-size:.85rem;">
            Bem-vindo, <strong><?= htmlspecialchars($_SESSION['reseller']['name']) ?></strong> &mdash;
            <?= date('d/m/Y') ?>
          </p>
        </div>

        <!-- ===== KPI STAT CARDS ===== -->
        <div class="row g-3 mb-4">

          <!-- Saldo Disponível -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat shadow-sm">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                  <i class="ti ti-wallet"></i>
                </div>
                <div>
                  <p class="text-muted mb-0" style="font-size:.75rem;">Saldo Disponível</p>
                  <h5 class="fw-bold mb-0"><?= number_format($balance, 2, ',', '.') ?> MT</h5>
                </div>
              </div>
            </div>
          </div>

          <!-- Limite de Crédito -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat shadow-sm">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                  <i class="ti ti-credit-card"></i>
                </div>
                <div>
                  <p class="text-muted mb-0" style="font-size:.75rem;">Limite de Crédito</p>
                  <h5 class="fw-bold mb-0"><?= number_format($creditLimit, 2, ',', '.') ?> MT</h5>
                </div>
              </div>
            </div>
          </div>

          <!-- Carregamentos Hoje -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat shadow-sm">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                  <i class="ti ti-battery-charging"></i>
                </div>
                <div>
                  <p class="text-muted mb-0" style="font-size:.75rem;">Carregamentos Hoje</p>
                  <h5 class="fw-bold mb-0"><?= (int)($today['total'] ?? 0) ?></h5>
                </div>
              </div>
            </div>
          </div>

          <!-- Volume Hoje -->
          <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat shadow-sm">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                  <i class="ti ti-coins"></i>
                </div>
                <div>
                  <p class="text-muted mb-0" style="font-size:.75rem;">Volume Hoje</p>
                  <h5 class="fw-bold mb-0"><?= number_format($today['soma'] ?? 0, 2, ',', '.') ?> MT</h5>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /KPI STAT CARDS -->

        <!-- ===== QUICK ACTIONS ===== -->
        <div class="row g-3 mb-4">
          <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
              <div class="card-body">
                <h6 class="fw-semibold mb-3">Acções Rápidas</h6>
                <div class="d-flex flex-wrap gap-2">
                  <a href="carregamento.php" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-battery-charging"></i> Novo Carregamento
                  </a>
                  <a href="relatorios.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-receipt"></i> Ver Relatórios
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /QUICK ACTIONS -->

      </div>
    </main>
    <!-- /MAIN CONTENT -->

    <!-- ==================== FOOTER ==================== -->
    <footer class="border-top bg-white py-3 px-4 text-center">
      <small class="text-muted">&copy; <?= date('Y') ?> Urafiki Lda</small>
    </footer>

  </div>
  <!-- /PAGE WRAPPER -->

</div>
<!-- /d-flex -->

<!-- Bootstrap 5 JS bundle -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!-- Custom module -->
<script src="assets/js/main.js" type="module"></script>
</body>
</html>
