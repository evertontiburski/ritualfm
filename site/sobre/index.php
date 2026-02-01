<?php
$title = "RITUAL FM | SOBRE NÓS";
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
    <div>
      <div id="title_inst">Sobre a Ritual fm</div>
    </div>
    <div class="content-inst">
      <p>
        <strong>A RITUAL FM</strong>  foi fundada em   <strong>2006,</strong>  com o nome de Rádio da Web. Na época, a estação era uma das primeiras a transmitir música eletrônica 24 horas por dia, 7 dias por semana, via internet.
      <p> A equipe de fundadores era composta por DJs e produtores musicais que queriam criar um espaço para compartilhar sua paixão pela música eletrônica.</p>
      </p>
      <p>Com o passar do tempo, a Rádio da Web ganhou popularidade e começou a se destacar na cena musical eletrônica brasileira. Em 2010, a equipe de fundadores decidiu mudar o nome da estação para Ritual FM, refletindo a atmosfera ritualística e imersiva que a música eletrônica pode criar.</p>
      <p>A Ritual FM é conhecida por sua programação exclusiva de música eletrônica, que inclui gêneros como <strong>Psy trance, techno, house e trance.</strong> A estação apresenta uma variedade de DJs e produtores musicais, tanto brasileiros quanto internacionais, e é uma referência para os amantes de música eletrônica no Brasil. </p>
      <p>Hoje em dia, a Ritual FM é uma das principais estações de rádio de música eletrônica do Brasil, com uma audiência fiel e engajada. A estação continua a inovar e a se adaptar às mudanças na indústria musical, sempre mantendo sua programação exclusiva de música eletrônica.</p>
      <p>
        <strong>RITUAL FM - O Ritual da Música Eletrônica! </strong>
      </p>
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