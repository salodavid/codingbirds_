<?php $cur = basename($_SERVER['SCRIPT_FILENAME']); ?>
<aside id="sidebar" class="sidebar">
  <div class="logo-area">
    <a href="main.php" class="d-inline-flex align-items-center text-decoration-none">
      <span class="fw-bold text-primary fs-5">Urafiki POS</span>
    </a>
  </div>
  <ul class="nav flex-column">
    <li class="px-4 py-2"><small class="nav-text">Principal</small></li>
    <li>
      <a class="nav-link <?= $cur === 'main.php' ? 'active' : '' ?>" href="main.php">
        <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
      </a>
    </li>
    <li>
      <a class="nav-link <?= $cur === 'carregamento.php' ? 'active' : '' ?>" href="carregamento.php">
        <i class="ti ti-battery-charging"></i><span class="nav-text">Carregamento</span>
      </a>
    </li>
    <li>
      <a class="nav-link <?= $cur === 'relatorios.php' ? 'active' : '' ?>" href="relatorios.php">
        <i class="ti ti-receipt"></i><span class="nav-text">Relatórios</span>
      </a>
    </li>
    <li class="px-4 pt-4 pb-2"><small class="nav-text">Conta</small></li>
    <li>
      <a class="nav-link" href="logout.php">
        <i class="ti ti-logout"></i><span class="nav-text">Sair</span>
      </a>
    </li>
  </ul>
</aside>
