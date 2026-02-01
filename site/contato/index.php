<?php
$title = "RITUAL FM | CONTATO";
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
    <h1>Contato</h1>
    <p>RITUAL FM<br>São Paulo - Brasil <br>
<a class="link-underline" href="mailto:info@ritualfm.com">contato@ritualfm.com</a></p>
  </div>

  <div class="contact-data">
  <div class="contact-line"></div>
    <div class="contact-line">
      <span>Anuncios e patrocinios</span>
      <a class="transition" href="mailto:publicidade@ritualfm.com">publicidade@ritualfm.com</a>
    </div>
    <div class="contact-line">
      <span>Comunicados de imprensa</span>
      <div>
        <a class="transition" href="mailto:info@ritualfm.com">info@ritualfm.com</a>
      </div>
    </div>
    <div class="contact-line">
      <span>Oportunidades</span>
      <a class="transition" href="mailto:rh@ritualfm.com">rh@ritualfm.com</a>
    </div>
    <div class="contact-line">
      <span>Editor-chefe</span>
      <a class="transition" href="mailto:robinson.m@ritualfm.com">robinson.m@ritualfm.com</a>
    </div>
  </div>
      <div class="write-text">
      <h2>Escreva<br>para nós</h2>
    </div>

<div class="contact-write">
  <div class="write-flex">
     <div class="right-form">
    <div class="form-box">
      <p class="form-info">Todos os campos com asterisco são de preenchimento obrigatório.</p>
      <form id="contatoForm">
  <input name="nome" type="text" placeholder="Nome*" required>
  <input name="assunto" type="text" placeholder="Assunto*" required>
  <input name="email" type="email" placeholder="E-mail*" required>
  <input name="estado" type="text" placeholder="Estado">
  <input name="telefone" type="tel" placeholder="Telefone">
  <textarea name="mensagem" placeholder="Escreva sua mensagem aqui*..." required></textarea>
  <button class="form-button" type="submit" id="btnsend">ENVIAR</button>
</form>
<script src="form_handler_php.js"></script>
    </div>
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
</script>
<?php
$pageContent = ob_get_clean();
include('../index2.php');
?>