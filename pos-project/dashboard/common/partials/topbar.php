<nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
  <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
    <i class="ti ti-layout-sidebar-left-expand"></i>
  </button>
  <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
    <i class="ti ti-layout-sidebar-left-expand"></i>
  </button>
  <div class="ms-auto">
    <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
      <li class="dropdown">
        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="d-flex align-items-center gap-2 text-dark text-decoration-none">
          <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:14px;">
            <?= strtoupper(substr($_SESSION['reseller']['name'], 0, 1)) ?>
          </span>
          <span class="d-none d-md-inline small"><?= htmlspecialchars($_SESSION['reseller']['name']) ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" style="min-width:200px;">
          <div class="d-flex gap-3 align-items-center border-bottom px-3 py-3">
            <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:40px;height:40px;min-width:40px;font-size:16px;">
              <?= strtoupper(substr($_SESSION['reseller']['name'], 0, 1)) ?>
            </span>
            <div>
              <h6 class="mb-0 small"><?= htmlspecialchars($_SESSION['reseller']['name']) ?></h6>
              <p class="mb-0 small text-muted"><?= htmlspecialchars($_SESSION['reseller']['email']) ?></p>
            </div>
          </div>
          <div class="p-2">
            <a href="logout.php" class="dropdown-item small text-danger">
              <i class="ti ti-logout me-2"></i>Sair
            </a>
          </div>
        </div>
      </li>
    </ul>
  </div>
</nav>
