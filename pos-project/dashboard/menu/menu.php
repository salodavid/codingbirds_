<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="<?= APP_URL ?>dashboard/main.php">
      <span class="ms-1 font-weight-bold">POS Urafiki</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'main.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/main.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="12px" height="12px" viewBox="0 0 45 45" version="1.1" xmlns="http://www.w3.org/2000/svg">
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                  <g transform="translate(1716.000000, 291.000000)">
                    <g transform="translate(0.000000, 148.000000)">
                      <path class="color-background" d="M46.7467,-0.0000204861 C43.2052,-0.0000204861 37.5003,3.09929 37.5003,6.64075 L37.5003,43.3598 C37.5003,46.9009 43.2052,50.0003 46.7467,50.0003 L46.7467,-0.0000204861 Z" opacity="0.2"></path>
                      <path class="color-background" d="M22.5002,0 C19.2765,0 0,18.7508 0,22.5002 C0,26.2496 19.2765,45.0004 22.5002,45.0004 L47.5002,45.0004 C47.5002,20.1472 28.5535,0 22.5002,0 Z" opacity="0.2"></path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class="nav-link-text ms-1">Painel Principal</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Transacções</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'mobile.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/mobile.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-mobile-button text-info text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Mobile</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'televisao.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/televisao.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-tv-2 text-warning text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Televisão</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'credelec.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/credelec.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-bolt text-warning text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Credelec</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Conta</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'carregamento.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/carregamento.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-money-coins text-success text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Carregamentos</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'billing.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/billing.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-credit-card text-danger text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Extracto</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Relatórios</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/reports.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-info text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Relatórios PDF</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Configuração</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'parametros.php' ? 'active' : '' ?>" href="<?= APP_URL ?>dashboard/parametros.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-settings text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Parâmetros</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?= APP_URL ?>dashboard/common/session/logout.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-user-run text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Terminar Sessão</span>
        </a>
      </li>

    </ul>
  </div>
</aside>
