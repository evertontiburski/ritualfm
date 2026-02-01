<?php
$title = "RITUAL FM | ANÚNCIE";
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
<div class="contact-container">
  <div class="contact-info">
    <h1>Anuncie</h1>
    <p>RITUAL FM<br>São Paulo - Brasil <br>
<a class="link-underline" href="mailto:info@ritualfm.com">contato@ritualfm.com</a></p>
  </div>
 <div class="container-announcement">
    <p class="intro">
      É assim que é. Vale a pena anunciar no rádio. Por quê?
    </p>
    <ul>
      <li>O rádio é o companheiro diário número 1.</li>
      <li>O rádio é um amigo. As pessoas confiam no rádio e o percebem de forma muito pessoal.</li>
      <li>O rádio pode ser ouvido a qualquer hora e em qualquer lugar.</li>
      <li>O rádio alcança pessoas em todos os lugares.</li>
      <li>O rádio está presente imediatamente antes da compra.</li>
      <li>O rádio é direto e espontâneo.</li>
      <li>O rádio é um meio flexível que reage rapidamente às necessidades dos anunciantes.</li>
      <li>O rádio é seletivo e permite uso direcionado.</li>
    </ul>
    <p class="final">
      Nossa tarefa e dever é ter um bom interlocutor ao nosso lado que possa sugerir uma propaganda de rádio eficaz. Você não quer jogar dinheiro fora com uma campanha imprudente, certo?
    </p>
    <div class="contact-line"></div>
    <div class="signature">
      <div class="name">
        <strong>Gustavo Pereira,</strong><br>
        <strong>Diretor de Vendas</strong>
      </div>
      <div class="contact" style="text-align: right">
        <strong>011 999 999 999</strong><br>
        <strong>marcelo.p@ritualfm.com</strong>
      </div>
    </div>
  </div>
</div>
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
include('../index2.php');
?>