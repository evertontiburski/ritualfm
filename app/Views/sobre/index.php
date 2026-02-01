<div id="container-nav" class="fadeIn">

    <!-- <li class="mContent main-menu_txt">
        <a href="<?= URL ?>" class="ajax-link">
            <div class="menu_ico">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" aria-label="arrow">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"></path>
                </svg>
            </div>Ir para o início
        </a>
    </li> -->

    <?php if (isset($dados->sobres) && !empty($dados->sobres)): ?>

        <?php foreach ($dados->sobres as $sobre): ?>

            <div>
                <div id="title_inst"><?= $sobre->titulo ?></div>
            </div>
            <div class="content-inst"><?= $sobre->texto ?></div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="content-inst"><?= "Nenhum conteúdo de Sobre-nós cadastrado." ?></div>

    <?php endif; ?>

</div>

<!-- Player -->
<div id="mini_player"></div>