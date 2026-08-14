<?php
session_start();
require 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_usuario'] = $admin['usuario'];
        header('Location: cadastro_produtos.php');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Administrativo — Stubinski Joias</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .login-box {
      max-width: 400px;
      margin: 6rem auto;
      background: var(--card-bg);
      border: 1px solid rgba(201,168,76,0.2);
      padding: 2.5rem;
    }
    .login-box h2 {
      color: var(--creme);
      font-family: var(--fonte-display);
      font-weight: 300;
      margin-bottom: 0.3rem;
    }
    .login-box label {
      display: block;
      margin-top: 1.2rem;
      font-size: 0.7rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--dourado);
    }
    .login-box input {
      width: 100%;
      margin-top: 0.5rem;
      padding: 0.7rem;
      background: var(--preto);
      border: 1px solid rgba(201,168,76,0.3);
      color: var(--creme);
      box-sizing: border-box;
    }
    .login-box button {
      margin-top: 2rem;
      width: 100%;
      padding: 1rem;
      background: var(--dourado);
      color: var(--preto);
      border: none;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      cursor: pointer;
    }
    .msg-erro {
      max-width: 400px;
      margin: 1rem auto 0;
      padding: 0.8rem;
      text-align: center;
      border: 1px solid #c0392b;
      color: #e74c3c;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>

<header>
  <div class="header-inner">
    <a href="index.html" class="logo">
      <div class="logo-icone">◆</div>
      <span class="logo-nome">Stubinski <span>Joias</span></span>
    </a>
  </div>
</header>

<div class="login-box">
  <p class="hero-eyebrow" style="text-align:left;">Área Restrita</p>
  <h2>Login Administrativo</h2>
  <span class="linha-dourada" style="margin-left:0;"></span>

  <form method="POST" action="">
    <label for="usuario">Usuário</label>
    <input type="text" id="usuario" name="usuario" required>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" required>

    <button type="submit">Entrar</button>
  </form>
</div>

<?php if ($erro): ?>
  <div class="msg-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

</body>
</html>
