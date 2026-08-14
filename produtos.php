<?php
require 'conexao.php';

// Busca todos os produtos do banco, ordenados do mais recente para o mais antigo
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id_produto DESC")->fetchAll(PDO::FETCH_ASSOC);

// Mapeia categoria do banco para nome de exibição
$categoriasMap = [
    'anel'         => 'Anéis',
    'colar'        => 'Colares',
    'brinco'       => 'Brincos',
    'pulseira'     => 'Pulseiras',
    'personalizado'=> 'Personalizados'
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Coleções — Stubinski Joias</title>
  <link rel="stylesheet" href="style.css" />
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
        <li><a href="produtos.php" class="ativo">Coleções</a></li>
        <li><a href="sobre.html">Sobre Nós</a></li>
        <li><a href="contato.html">Contato</a></li>
        <li><a href="cadastro_produtos.php">Cadastrar Produto</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="page-hero">
  <div>
    <p class="page-hero-eyebrow">Alta Joalheria</p>
    <h1>Nossas Coleções</h1>
    <span class="linha-dourada"></span>
  </div>
</div>

<div class="secao">
  <p class="secao-subtitulo" style="margin-bottom:2rem;">
    Peças únicas criadas por nossos ourives, disponíveis para entrega ou personalização exclusiva.
  </p>

  <div class="filtros">
    <button class="filtro-btn ativo" data-filtro="todos">Todos</button>
    <button class="filtro-btn" data-filtro="anel">Anéis</button>
    <button class="filtro-btn" data-filtro="colar">Colares</button>
    <button class="filtro-btn" data-filtro="brinco">Brincos</button>
    <button class="filtro-btn" data-filtro="pulseira">Pulseiras</button>
    <button class="filtro-btn" data-filtro="personalizado">Personalizados</button>
  </div>

  <div class="produtos-grid">

    <?php if (empty($produtos)): ?>
      <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--texto);">
        <p>Nenhuma peça cadastrada ainda.</p>
        <p style="margin-top:1rem; font-size:0.85rem;">Acesse a área administrativa para cadastrar os primeiros produtos.</p>
      </div>
    <?php else: ?>
      <?php foreach ($produtos as $p): 
        $cat = strtolower($p['categoria'] ?? '');
        $catLabel = $categoriasMap[$cat] ?? ucfirst($cat);
        $img = !empty($p['imagem']) ? htmlspecialchars($p['imagem']) : 'imagens/produtos/Joia_Personalizada.jpg';
        $badge = ($p['destaque'] ?? '') === 'sim' ? 'Destaque' : '';
      ?>
      <div class="produto-card" data-categoria="<?= htmlspecialchars($cat) ?>">
        <div class="produto-imagem">
          <img src="<?= $img ?>" 
               alt="<?= htmlspecialchars($p['nome']) ?>" 
               loading="lazy"
               style="width:100%;height:100%;object-fit:cover;"
               onerror="this.src='imagens/produtos/Joia_Personalizada.jpg'">
          <?php if ($badge): ?>
            <span class="produto-badge"><?= $badge ?></span>
          <?php endif; ?>
        </div>
        <div class="produto-info">
          <p class="produto-categoria"><?= htmlspecialchars($catLabel) ?></p>
          <h3 class="produto-nome"><?= htmlspecialchars($p['nome']) ?></h3>
          <p class="produto-desc"><?= htmlspecialchars($p['descricao'] ?: 'Peça exclusiva Stubinski Joias.') ?></p>
          <div class="produto-footer">
            <div class="produto-preco">
              R$ <?= number_format((float)$p['preco'], 2, ',', '.') ?>
            </div>
            <a href="contato.html" class="btn btn-primario" style="padding:0.6rem 1.2rem;font-size:0.65rem;">Consultar</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>

  <div style="text-align:center;margin-top:4rem;padding:3rem 2rem;background:var(--card-bg);border:1px solid rgba(201,168,76,0.15);">
    <p class="hero-eyebrow" style="margin-bottom:0.5rem;">Não encontrou o que procurava?</p>
    <h3 style="font-family:var(--fonte-display);font-size:1.8rem;color:var(--creme);margin-bottom:0.75rem;font-weight:300;">
      Criamos peças exclusivas sob medida
    </h3>
    <p style="margin-bottom:1.5rem;max-width:500px;margin-left:auto;margin-right:auto;">
      Entre em contato com nossa equipe e desenvolva uma joia única que traduza exatamente o que você deseja expressar.
    </p>
    <a href="contato.html" class="btn btn-primario">Falar com um Especialista</a>
  </div>
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
      <div class="footer-col">
        <h4>Coleções</h4>
        <ul>
          <li><a href="produtos.php">Anéis</a></li>
          <li><a href="produtos.php">Colares</a></li>
          <li><a href="produtos.php">Brincos</a></li>
          <li><a href="produtos.php">Pulseiras</a></li>
          <li><a href="produtos.php">Personalizados</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contato</h4>
        <ul>
          <li><a href="#">📍 Rua Terezina, 2834 — PR</a></li>
          <li><a href="#">📞 (45) 3226-7890</a></li>
          <li><a href="#">✉ contato@stubinskijoias.com.br</a></li>
          <li><a href="#">⏰ Seg–Sáb: 10h–20h</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Stubinski Joias. Todos os direitos reservados.</span>
      <span>CNPJ: 12.345.678/0001-90</span>
    </div>
  </div>
</footer>

<script>
  // Filtro de categorias
  document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('ativo'));
      btn.classList.add('ativo');

      const filtro = btn.dataset.filtro;
      document.querySelectorAll('.produto-card').forEach(card => {
        if (filtro === 'todos' || card.dataset.categoria === filtro) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
</script>

</body>
</html>
