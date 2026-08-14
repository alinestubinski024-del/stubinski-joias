<?php
session_start();
require 'conexao.php';

// Bloqueia o acesso se não estiver logado como admin
if (empty($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit;
}

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $categoria = $_POST['categoria'] ?? '';
    $preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));
    $descricao = trim($_POST['descricao'] ?? '');
    $destaque = $_POST['destaque'] ?? 'nao';
    $imagem = ''; // nome final do arquivo salvo, preenchido durante o upload

    if ($nome === '' || $categoria === '' || $preco === '') {
        $erro = "Preencha os campos obrigatórios: nome, categoria e preço.";
    } elseif (!is_numeric($preco)) {
        $erro = "O preço precisa ser um número (use ponto para casas decimais, ex: 1500.00).";
    } elseif (empty($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        $erro = "Selecione uma imagem para a peça.";
    } else {
        // Só aceita esses formatos de imagem
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            $erro = "Formato de imagem inválido. Use JPG, PNG ou WEBP.";
        } else {
            // Cria a pasta de imagens se ainda não existir
            $pastaDestino = __DIR__ . '/imagens/produtos/';
            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0777, true);
            }

            // Gera um nome de arquivo único, baseado no nome do produto, pra não sobrescrever fotos
            $nomeArquivo = preg_replace('/[^a-z0-9]+/', '_', strtolower($nome));
            $nomeArquivo = trim($nomeArquivo, '_') . '_' . time() . '.' . $extensao;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $pastaDestino . $nomeArquivo)) {
                $imagem = 'imagens/produtos/' . $nomeArquivo;
            } else {
                $erro = "Não foi possível salvar a imagem no servidor.";
            }
        }
    }

    if (!$erro) {
        try {
            $sql = "INSERT INTO produtos (nome, categoria, preco, descricao, imagem, destaque)
                    VALUES (:nome, :categoria, :preco, :descricao, :imagem, :destaque)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':categoria' => $categoria,
                ':preco' => $preco,
                ':descricao' => $descricao,
                ':imagem' => $imagem,
                ':destaque' => $destaque,
            ]);
            $mensagem = "Produto cadastrado com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}

$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id_produto DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastrar Produto — Stubinski Joias</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .admin-form {
      max-width: 700px;
      margin: 0 auto;
      background: var(--card-bg);
      border: 1px solid rgba(201,168,76,0.2);
      padding: 2.5rem;
    }
    .admin-form label {
      display: block;
      margin-top: 1.2rem;
      font-size: 0.7rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--dourado);
    }
    .admin-form input[type=text],
    .admin-form input[type=number],
    .admin-form select,
    .admin-form textarea {
      width: 100%;
      margin-top: 0.5rem;
      padding: 0.7rem;
      background: var(--preto);
      border: 1px solid rgba(201,168,76,0.3);
      color: var(--creme);
      font-family: var(--fonte-corpo);
      box-sizing: border-box;
    }
    .admin-form textarea {
      min-height: 90px;
      resize: vertical;
    }
    .admin-form button {
      margin-top: 2rem;
      width: 100%;
      padding: 1rem;
      background: var(--dourado);
      color: var(--preto);
      border: none;
      font-size: 0.85rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      cursor: pointer;
      transition: var(--transicao);
    }
    .admin-form button:hover {
      background: var(--dourado2);
    }
    .msg-sucesso, .msg-erro {
      max-width: 700px;
      margin: 0 auto 1.5rem;
      padding: 1rem;
      text-align: center;
      font-size: 0.85rem;
      letter-spacing: 0.05em;
    }
    .msg-sucesso {
      border: 1px solid var(--dourado);
      color: var(--dourado);
    }
    .msg-erro {
      border: 1px solid #c0392b;
      color: #e74c3c;
    }
    .produtos-tabela {
      max-width: 1000px;
      margin: 3rem auto 0;
      width: 100%;
      border-collapse: collapse;
    }
    .produtos-tabela th, .produtos-tabela td {
      padding: 0.8rem;
      text-align: left;
      font-size: 0.85rem;
      border-bottom: 1px solid rgba(201,168,76,0.15);
      color: var(--texto);
    }
    .produtos-tabela th {
      color: var(--dourado);
      text-transform: uppercase;
      font-size: 0.7rem;
      letter-spacing: 0.1em;
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
    <nav>
      <ul>
        <li><a href="index.html">Início</a></li>
        <li><a href="produtos.php">Coleções</a></li>
        <li><a href="sobre.html">Sobre Nós</a></li>
        <li><a href="contato.html">Contato</a></li>
        <li><a href="cadastro_produtos.php" class="ativo">Cadastrar Produto</a></li>
        <li><a href="logout.php">Sair (<?= htmlspecialchars($_SESSION['admin_usuario']) ?>)</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="page-hero">
  <div>
    <p class="page-hero-eyebrow">Área Administrativa</p>
    <h1>Cadastrar Produto</h1>
    <span class="linha-dourada"></span>
  </div>
</div>

<div class="secao">

  <?php if ($mensagem): ?>
    <div class="msg-sucesso">✦ <?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

  <?php if ($erro): ?>
    <div class="msg-erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form class="admin-form" method="POST" action="" enctype="multipart/form-data">
    <label for="nome">Nome da Peça *</label>
    <input type="text" id="nome" name="nome" required>

    <label for="categoria">Categoria *</label>
    <select id="categoria" name="categoria" required>
      <option value="" disabled selected>Selecione a categoria</option>
      <option value="anel">Anel</option>
      <option value="colar">Colar</option>
      <option value="brinco">Brinco</option>
      <option value="pulseira">Pulseira</option>
      <option value="personalizado">Personalizado</option>
    </select>

    <label for="preco">Preço (R$) *</label>
    <input type="text" id="preco" name="preco" placeholder="Ex: 1500.00" required>

    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" placeholder="Ex: Anel solitário em ouro 18k com safira natural de 1,5ct..."></textarea>

    <label for="imagem">Foto da Peça *</label>
    <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/webp" required>

    <label for="destaque">Peça em destaque?</label>
    <select id="destaque" name="destaque">
      <option value="nao" selected>Não</option>
      <option value="sim">Sim</option>
    </select>

    <button type="submit">Cadastrar Produto ✦</button>
  </form>

  <h2 class="secao-titulo" style="margin-top:4rem;">Produtos Cadastrados</h2>
  <span class="linha-dourada"></span>

  <table class="produtos-tabela">
    <tr>
      <th>Foto</th>
      <th>ID</th>
      <th>Nome</th>
      <th>Categoria</th>
      <th>Preço</th>
      <th>Destaque</th>
    </tr>
    <?php foreach ($produtos as $p): ?>
    <tr>
      <td>
        <?php if (!empty($p['imagem'])): ?>
          <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($p['id_produto']) ?></td>
      <td><?= htmlspecialchars($p['nome']) ?></td>
      <td><?= htmlspecialchars(ucfirst($p['categoria'])) ?></td>
      <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
      <td><?= $p['destaque'] === 'sim' ? '✦ Sim' : '—' ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

</div>

<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-logo">Stubinski <span>Joias</span></div>
        <p class="footer-desc">Joalheria de alta qualidade com mais de 5 anos de tradição. Cada peça é uma obra de arte criada com paixão e expertise.</p>
      </div>
      <div class="footer-col">
        <h4>Navegação</h4>
        <ul>
          <li><a href="index.html">Início</a></li>
          <li><a href="produtos.php">Coleções</a></li>
          <li><a href="sobre.html">Sobre Nós</a></li>
          <li><a href="contato.html">Contato</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Stubinski Joias. Todos os direitos reservados.</span>
      <span>CNPJ: 12.345.678/0001-90</span>
    </div>
  </div>
</footer>

</body>
</html>
