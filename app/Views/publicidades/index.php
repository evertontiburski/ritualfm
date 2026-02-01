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


    <div id="content">
        <div id="news">

            <!-- <div class="ads_970x250"><img src="<?= URL . '/public/images/' ?>ads1.jpg" width="970" height="250" alt="" /></div> -->

            <div class="news-section">
                <div class="section">
                    <div class="title-section sticky-news">PUBLIC</div>
                    <div class="news-base">
                        <div class="news-feature" style="background-image: url('<?= URL . '/public/uploads/imagens/' . $dados->publicidade_slug->imagem_publicidade ?>');">
                            <div class="news-bg">
                                <div class="news-text">
                                    <span><?= $dados->publicidade_slug->titulo ?></span>
                                    <div class="description"><?= $dados->publicidade_slug->subtitulo ?></div>
                                </div>
                            </div>
                        </div>

                        <div><?= $dados->publicidade_slug->texto ?></div>
                        <small>Publicado: <?= Funcoes::contarTempo($dados->publicidade_slug->data_criacao) ?></small>

                    </div>
                    <div class="side">
                        <div class="ads_300x600"><img src="<?= URL . '/public/images/' ?>ads_coluna.jpg" width="300" height="600" alt="" /></div>
                        <div class="card_event">
                            <h1>EVENTOS</h1>
                            <img src="<?= URL . '/public/images/' ?>event_03.jpg">
                        </div>
                        <div class="card_music">
                            <h1>MÚSICA</h1>
                            <img src="<?= URL . '/public/images/' ?>music_02.jpg">
                            <div class="card-music-bg">
                                <div class="card-music-text">
                                    <span>Blessings Calvin Harris, Clementine Douglas</span>
                                    <div class="description">Calvin Harris lançou um novo single, "Blessings", com a vocalista britânica Clementine Douglas.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Player -->
<div id="mini_player"></div>