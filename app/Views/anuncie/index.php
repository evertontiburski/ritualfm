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


    <!-- <div id="content"> -->
    <?php if (isset($dados->anuncies) && !empty($dados->anuncies)): ?>

        <?php foreach ($dados->anuncies as $anuncie): ?>

            <div class="contact-container">
                <div class="contact-info">
                    <h1><?= $anuncie->titulo ?></h1>
                    <p><?= $anuncie->endereco ?> <br>
                        <a class="link-underline" href="mailto:<?= $anuncie->email ?>"><?= $anuncie->email ?></a>
                    </p>
                </div>
                <div class="container-announcement">
                    <?= $anuncie->texto ?>
                    <div class="contact-line"></div>
                    <div class="signature">
                        <div class="name">
                            <strong><?= $anuncie->nome ?>,</strong><br>
                            <strong><?= $anuncie->cargo ?></strong>
                        </div>
                        <div class="contact" style="text-align: right">
                            <strong><?= $anuncie->telefone ?></strong><br>
                            <strong><?= $anuncie->email ?></strong>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="content-inst"><?= "Nenhum conteúdo de Anuncie cadastrado." ?></div>

    <?php endif; ?>
    <!-- </div> -->

</div>

<!-- Player -->
<div id="mini_player"></div>