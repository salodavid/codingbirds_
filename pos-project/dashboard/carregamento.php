<?php
ob_start();
session_start();
if (empty($_SESSION['reseller'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Carregamento — POS Urafiki</title>
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
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="fs-3 mb-1">Carregamento</h1>
              <p class="text-muted mb-0">Efectuar um carregamento</p>
            </div>
            <a href="relatorios.php" class="btn btn-outline-secondary btn-sm">Ver Relatórios</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body p-4">
              <form id="carregamentoForm" method="post" action="api/recharge.php">
                <div class="mb-3">
                  <label class="form-label">Tipo de Serviço</label>
                  <select class="form-select" name="serviceType" id="serviceType" required>
                    <option value="">Seleccione...</option>
                    <option value="mobile">Mobile (Recarrega)</option>
                    <option value="tv">TV (DSTV / ZAP)</option>
                    <option value="electricity">Electricidade</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" id="accountLabel">Número / Conta</label>
                  <input type="text" class="form-control" name="msisdn" id="msisdn" placeholder="Ex: 84xxxxxxx" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Valor (MT)</label>
                  <input type="number" class="form-control" name="amount" placeholder="0.00" step="0.01" min="1" required>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary">Carregar</button>
                  <button type="reset" class="btn btn-outline-secondary">Limpar</button>
                </div>
              </form>
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
  <script>
    document.getElementById('serviceType').addEventListener('change', function() {
      const labels = { mobile: 'Número de Telefone', tv: 'Smart Card / Conta', electricity: 'Número de Conta' };
      document.getElementById('accountLabel').textContent = labels[this.value] || 'Número / Conta';
    });
  </script>
</body>
</html>
