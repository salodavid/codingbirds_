<?php
// topbar.php — included from dashboard-level pages
// Requires session to already be started and $_SESSION['reseller'] to be set
$_resellerName  = htmlspecialchars($_SESSION['reseller']['name']  ?? 'Utilizador');
$_resellerEmail = htmlspecialchars($_SESSION['reseller']['email'] ?? '');
$_resellerInitial = strtoupper(substr($_SESSION['reseller']['name'] ?? 'U', 0, 1));
$_currentPage = basename($_SERVER['SCRIPT_FILENAME'], '.php');
?>
<nav id="topbar" class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-2 sticky-top">
  <div class="container-fluid">

    <!-- Mobile sidebar toggle -->
    <button class="btn btn-link text-dark d-lg-none me-2" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
      <i class="ti ti-menu-2 fs-5"></i>
    </button>

    <!-- Page title / breadcrumb -->
    <span class="navbar-brand mb-0 h6 text-secondary fw-normal d-none d-sm-inline">
      <?php
      $titles = [
        'main'         => 'Dashboard',
        'carregamento' => 'Carregamento',
        'relatorios'   => 'Relatórios',
      ];
      echo htmlspecialchars($titles[$_currentPage] ?? ucfirst($_currentPage));
      ?>
    </span>

    <div class="ms-auto d-flex align-items-center gap-3">

      <!-- Notification bell (shell only) -->
      <div class="dropdown">
        <button class="btn btn-link text-secondary position-relative p-0"
                id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ti ti-bell fs-5"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notifDropdown" style="min-width:260px">
          <li><h6 class="dropdown-header">Notificações</h6></li>
          <li><p class="text-muted text-sm px-3 py-2 mb-0">Sem notificações de momento.</p></li>
        </ul>
      </div>

      <!-- User dropdown -->
      <div class="dropdown">
        <button class="btn btn-link text-dark d-flex align-items-center gap-2 p-0 text-decoration-none"
                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                style="width:34px;height:34px;font-size:.85rem;">
            <?= $_resellerInitial ?>
          </span>
          <span class="d-none d-md-inline text-sm fw-semibold"><?= $_resellerName ?></span>
          <i class="ti ti-chevron-down text-muted" style="font-size:.75rem;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
          <li class="px-3 py-2">
            <p class="mb-0 fw-semibold text-sm"><?= $_resellerName ?></p>
            <p class="mb-0 text-muted" style="font-size:.75rem;"><?= $_resellerEmail ?></p>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          <li>
            <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="logout.php">
              <i class="ti ti-logout"></i> Sair
            </a>
          </li>
        </ul>
      </div>

    </div>
  </div>
</nav>
