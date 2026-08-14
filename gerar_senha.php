<?php
// Página de uso único: gera o hash da senha do admin.
// Depois de usar, você pode apagar este arquivo por segurança.

$hash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    if ($senha !== '') {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerar Senha de Admin</title>
  <style>
    body { font-family: Arial, sans-serif; background: #111; color: #eee; padding: 2rem; }
    .box { max-width: 500px; margin: 0 auto; background: #1c1a17; padding: 2rem; border: 1px solid #C9A84C; }
    input { width: 100%; padding: 0.6rem; margin: 0.5rem 0 1rem; box-sizing: border-box; }
    button { padding: 0.7rem 1.5rem; background: #C9A84C; border: none; cursor: pointer; }
    .resultado { margin-top: 1.5rem; padding: 1rem; background: #000; word-break: break-all; font-family: monospace; color: #C9A84C; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Gerar Hash de Senha</h2>
    <form method="POST">
      <label>Digite a senha que o admin vai usar:</label>
      <input type="text" name="senha" required>
      <button type="submit">Gerar Hash</button>
    </form>

    <?php if ($hash): ?>
      <div class="resultado">
        <?= htmlspecialchars($hash) ?>
      </div>
      <p style="font-size:0.85rem;margin-top:1rem;">
        Copie esse hash inteiro e cole no INSERT que vou te passar, no lugar de SENHA_HASH_AQUI.
      </p>
    <?php endif; ?>
  </div>
</body>
</html>
