<?php
// sidebar.php — included from dashboard-level pages
$_activePage = basename($_SERVER['SCRIPT_FILENAME']);

function sidebarLink(string $href, string $icon, string $label, string $active): string {
    $isActive = ($active === $href) ? ' active' : '';
    $iconHtml = '<i class="ti ' . htmlspecialchars($icon) . ' me-2"></i>';
    return '<li class="nav-item">'
         . '<a class="nav-link d-flex align-items-center' . $isActive . '" href="' . htmlspecialchars($href) . '">'
         . $iconHtml . htmlspecialchars($label)
         . '</a></li>';
}
?>
<!-- ============================================================
     Sidebar — desktop (static)
     ============================================================ -->
<aside id="sidebar" class="sidebar d-none d-lg-flex flex-column bg-white border-end"
       style="width:240px;min-height:100vh;position:sticky;top:0;height:100vh;overflow-y:auto;">

  <!-- Brand -->
  <div class="sidebar-brand d-flex align-items-center justify-content-center border-bottom py-4 px-3">
    <span class="fw-bold fs-5 text-primary">Urafiki POS</span>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav flex-grow-1 px-2 py-3">

    <p class="text-uppercase text-muted fw-semibold px-3 mb-1" style="font-size:.65rem;letter-spacing:.08em;">Principal</p>
    <ul class="nav flex-column mb-3">
      <?= sidebarLink('main.php',         'ti-home',              'Dashboard',    $_activePage) ?>
      <?= sidebarLink('carregamento.php', 'ti-battery-charging',  'Carregamento', $_activePage) ?>
      <?= sidebarLink('relatorios.php',   'ti-receipt',           'Relatórios',   $_activePage) ?>
    </ul>

    <p class="text-uppercase text-muted fw-semibold px-3 mb-1" style="font-size:.65rem;letter-spacing:.08em;">Conta</p>
    <ul class="nav flex-column">
      <?= sidebarLink('logout.php', 'ti-logout', 'Sair', $_activePage) ?>
    </ul>

  </nav>

  <!-- Footer -->
  <div class="sidebar-footer border-top px-3 py-3">
    <p class="text-muted mb-0" style="font-size:.7rem;">&copy; <?= date('Y') ?> Urafiki Lda</p>
  </div>

</aside>

<!-- ============================================================
     Sidebar — mobile (offcanvas)
     ============================================================ -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
  <div class="offcanvas-header border-bottom">
    <span class="fw-bold fs-5 text-primary" id="sidebarOffcanvasLabel">Urafiki POS</span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
  </div>
  <div class="offcanvas-body px-2 py-3">

    <p class="text-uppercase text-muted fw-semibold px-3 mb-1" style="font-size:.65rem;letter-spacing:.08em;">Principal</p>
    <ul class="nav flex-column mb-3">
      <?= sidebarLink('main.php',         'ti-home',              'Dashboard',    $_activePage) ?>
      <?= sidebarLink('carregamento.php', 'ti-battery-charging',  'Carregamento', $_activePage) ?>
      <?= sidebarLink('relatorios.php',   'ti-receipt',           'Relatórios',   $_activePage) ?>
    </ul>

    <p class="text-uppercase text-muted fw-semibold px-3 mb-1" style="font-size:.65rem;letter-spacing:.08em;">Conta</p>
    <ul class="nav flex-column">
      <?= sidebarLink('logout.php', 'ti-logout', 'Sair', $_activePage) ?>
    </ul>

  </div>
</div>
