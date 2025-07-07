<div class="cont flex img mb-7">
  <img src="<?php echo BASE_URL; ?>imagens/logo-escolas-sociais.png" class="ml-3" width="250" style="margin-top:20px; position: relative; left: 20px;" class="img">

  <div class="textos txt w-sm">
    <span style="color:black;">SOS</span> 
    <span style="color:white;">ATRASO</span> 
  </div>

  <!-- Menu (começa escondido no mobile) -->
  <ul id="menu" class="menu x-xl hidden md:flex md:flex-row md:space-x-4 ml-auto">
    <li>
      <a href="<?php echo BASE_ADMIN; ?>total_turmas.php" class="bg-marista2 text-white px-6 py-2 rounded-lg drop-shadow-lg mt-6">
        Total por Turmas
      </a>
    </li>

    <li>
      <a href="<?php echo BASE_ADMIN; ?>relatorio.php" class="bg-marista2 text-white px-6 py-2 rounded-lg drop-shadow-lg mt-6">
        Relatório Alunos
      </a>
    </li>

    <?php if ($_SESSION['login']['permissao'] === 'admin'): ?>
      <li>
        <a href="<?php echo BASE_ADMIN; ?>alunos/alunos.php" class="bg-marista2 text-white px-6 py-2 rounded-lg drop-shadow-lg mt-6">
          Alunos
        </a>
      </li>
      <li>
        <a href="<?php echo BASE_ADMIN; ?>usuarios/listar-usuarios.php" class="bg-marista2 text-white px-2 py-2 rounded-lg drop-shadow-lg mt-6">
          usuários
        </a>
      </li>
    <?php endif; ?>

    <li>
      <a href="<?php echo BASE_ADMIN; ?>logoff.php" class="bg-marista2 text-white px-6 py-2 rounded-lg drop-shadow-lg mt-6">
        Logoff
      </a>
    </li>
  </ul>

  <!-- Botão hamburguer (aparece só no mobile) -->
  <button id="menu-btn" class="block md:hidden ml-auto mt-6 text-white text-3xl focus:outline-none" aria-label="Abrir menu">
    &#9776;
  </button>
</div>

<script>
  const menuBtn = document.getElementById('menu-btn');
  const menu = document.getElementById('menu');

  menuBtn.addEventListener('click', () => {
    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');
      menu.classList.add('block');
    } else {
      menu.classList.add('hidden');
      menu.classList.remove('block');
    }
  });

  // Fecha o menu se clicar fora (opcional)
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !menuBtn.contains(e.target)) {
      if (!menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
        menu.classList.remove('block');
      }
    }
  });
</script>
