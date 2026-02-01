<?php
$title = "RITUAL FM | Eventos";
ob_start();
?>
<!-- Início do conteúdo da página -->
  <div id="container-nav" class="fadeIn">
    <li class="mContent main-menu_txt">
      <a href="/" onclick="closeNav2(), closeNav3()">
        <div class="menu_ico">
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" aria-label="arrow">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"></path>
          </svg>
        </div>Ir para o início
      </a>
    </li>
  </div>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    // Se a página foi carregada diretamente (não via AJAX)
    if (!window.history.state) {
      const mainNav = document.getElementById('main-nav');
      const miniPlayer = document.getElementById('mini_player');
      if (mainNav) mainNav.style.height = '100%';
      if (miniPlayer) miniPlayer.style.height = '60px';
    }
  });
</script> <?php
$pageContent = ob_get_clean();
include('../../index2.php');
?>