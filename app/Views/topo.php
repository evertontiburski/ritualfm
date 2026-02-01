<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= NOME_SITE ?></title>
    <script>
        const BASE_URL = '<?= rtrim(URL, '/') ?>';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="<?= URL ?>/public/css/animate.css">
    <link rel="stylesheet" href="<?= URL ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= URL ?>/public/css/editor.css">
</head>

<body>

    <div id="loader">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="100px" height="100px">
            <circle r="15" cx="40" cy="100" style="fill:#FF0000 !important; stroke:#FF0000 !important; stroke-width:15;">
                <animate attributeName="opacity" calcMode="spline" dur="0.9" values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4" />
            </circle>
            <circle r="15" cx="100" cy="100" style="fill:#FF0000 !important; stroke:#FF0000 !important; stroke-width:15;">
                <animate attributeName="opacity" calcMode="spline" dur="0.9" values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2" />
            </circle>
            <circle r="15" cx="160" cy="100" style="fill:#FF0000 !important; stroke:#FF0000 !important; stroke-width:15;">
                <animate attributeName="opacity" calcMode="spline" dur="0.9" values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0" />
            </circle>
        </svg>
    </div>

    <!-- Topo início -->
    <div id="header">
        <div id="header_secundary">
            <li class="mContent">
                <a href="<?= URL ?>" onclick="closeNav()" class="ajax-link">
                    <div class="logo"></div>
                </a>
            </li>
            <div class="menu mContent">
                <li>
                    <a href="<?= URL . '/posts' ?>" class="ajax-link">Notícias</a>
                </li>
                <li>
                    <a href="<?= URL . '/eventos' ?>" class="ajax-link">Eventos</a>
                </li>
                <li>
                    <a href="<?= URL . '/musicas' ?>" class="ajax-link">Músicas</a>
                </li>
            </div>
        </div>

        <li id="user-menu-container" class="mContent main-menu_txt">

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="#" class="ajax-link">
                    <div class="menu_ico">
                    </div>
                    <a href="<?= URL ?>/dashboard" class="ajax-link">Olá, <?= $_SESSION['usuario_nome'] ?>!</a>
                </a>
            <?php else: ?>
                <a href="#" class="ajax-link">
                    <div class="menu_ico">
                    </div>
                    <a href="<?= URL ?>/usuarios/login" class="ajax-link">Minha conta</a>
                </a>
            <?php endif; ?>

        </li>

        <button title="Menu principal" aria-label="Menu principal" aria-controls="main-menu_open" class="main-menu_open" onclick="openNav()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#fff" viewBox="0 -1 24 24">
                <g>
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M18 15l-.001 3H21v2h-3.001L18 23h-2l-.001-3H13v-2h2.999L16 15h2zm-7 3v2H3v-2h8zm10-7v2H3v-2h18zm0-7v2H3V4h18z"></path>
                </g>
            </svg>
        </button>
        <div id="main-menu">
            <button title="Fechar Menu" aria-label="Fechar Menu" aria-controls="main-menu_hide" class="main-menu_hide" onclick="closeNav()">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="-1 -1 30 30">
                    <path d="M 7 4 C 6.744125 4 6.4879687 4.0974687 6.2929688 4.2929688 L 4.2929688 6.2929688 C 3.9019687 6.6839688 3.9019687 7.3170313 4.2929688 7.7070312 L 11.585938 15 L 4.2929688 22.292969 C 3.9019687 22.683969 3.9019687 23.317031 4.2929688 23.707031 L 6.2929688 25.707031 C 6.6839688 26.098031 7.3170313 26.098031 7.7070312 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.682969 26.098031 23.317031 26.098031 23.707031 25.707031 L 25.707031 23.707031 C 26.098031 23.316031 26.098031 22.682969 25.707031 22.292969 L 18.414062 15 L 25.707031 7.7070312 C 26.098031 7.3170312 26.098031 6.6829688 25.707031 6.2929688 L 23.707031 4.2929688 C 23.316031 3.9019687 22.682969 3.9019687 22.292969 4.2929688 L 15 11.585938 L 7.7070312 4.2929688 C 7.5115312 4.0974687 7.255875 4 7 4 z"></path>
                </svg>
            </button>
            <div id="container-menu">
                <nav class="mContent nav-menu">
                    <a href="<?= URL ?>" class="nav-menu_main transition ajax-link">Início</a>
                    <a href="<?= URL . '/posts' ?>" class="nav-menu_main transition ajax-link">Notícias</a>
                    <a href="<?= URL . '/eventos' ?>" class="nav-menu_main transition ajax-link">Eventos</a>
                    <a href="<?= URL . '/musicas' ?>" class="nav-menu_main transition ajax-link" style="margin-bottom:40px;">Música</a>
                    <a href="<?= URL . '/sobre' ?>" class="nav-menu_second transition ajax-link">Sobre nós</a>
                    <a href="<?= URL . '/anuncie' ?>" class="nav-menu_second transition ajax-link">Anúncie</a>
                    <a href="<?= URL . '/contato' ?>" class="nav-menu_second transition ajax-link">Contato</a>
                </nav>
            </div>
            <div id="footer" class="mContent">
                <a href="<?= URL . '/termos' ?>" class="transition ajax-link">Termos e Condições</a> © Copyright 2006-2025 RITUAL FM - Todos os direitos reservados
            </div>
        </div>

        <div id="cookie-notification" class="CookieMessage" style="display: none;" role="dialog" aria-labelledby="cookie-title" aria-describedby="cookie-desc">
            <div class="CookieMessage-content">
                <div class="mContent">
                    <p id="cookie-desc"> Nosso site utiliza cookies para melhor desempenho. Concorda que coloquemos cookies no seu computador para esta finalidade? <a href="/termos" onclick="closeNav()" class="CookieMessage-content transition link-underline">Termos e Condições</a>
                    </p>
                </div>
                <p>
                    <a id="cookie-notification-close2" class="CookieMessage-content transition" href="javascript:void(0)">FECHAR</a>
                    <a id="cookie-notification-close" class="CookieMessage-button transition" href="javascript:void(0)">Aceitar</a>
                </p>
            </div>
        </div>

    </div>
    <!-- Topo fim -->

    <!-- Mini player início -->
    <div id="mini_player">
        <div id="playerButton2" class="mini_play" onclick="togglePlay()"></div>
        <div class="volumeButton volumeplayer" data-player="1">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="23 24 29 29">
                <g class="sound-icon" onclick="toggleMute()">
                    <rect fill="transparent" class="level-0" x="23" y="24" width="29" height="29"></rect>
                    <g class="icon">
                        <path fill="#ffffff" d="M39 41.499c0 .4-.41.651-.727.4l-2.591-1.904h-1.227c-.273 0-.455-.201-.455-.502v-3.008c0-.3.182-.501.455-.501h1.227l2.59-1.905c.319-.2.728 0 .728.4v7.02z"></path>
                        <path class="sound-icon" fill-rule="evenodd" d="M40 41.606a3.824 3.824 0 000-7.355v1.604a2.322 2.322 0 010 4.147v1.604z" clip-rule="evenodd"></path>
                    </g>
                </g>
            </svg>
            <div class="levels controlplayer">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="30" fill="none" viewBox="0 0 65 30">
                    <circle cx="9" cy="14" r="1" stroke-width="4" class="level-20" onclick="setVolumeLevel(20)"></circle>
                    <circle cx="22" cy="13" r="1" stroke-width="6" class="level-50" onclick="setVolumeLevel(50)"></circle>
                    <circle cx="37" cy="12" r="1" stroke-width="8" class="level-70" onclick="setVolumeLevel(70)"></circle>
                    <circle cx="54" cy="11" r="1" stroke-width="10" class="level-100" onclick="setVolumeLevel(100)"></circle>
                </svg>
            </div>
        </div>
        <div class="cover-album">
            <div id="currentCoverArt"></div>
        </div>
        <div id="lyricsSong"></div>

        <div class="social socialplayer">

            <?php foreach ($dados->sociais as $social): ?>
                <?php if ($social->status != 0): ?>
                    <a href="<?= $social->link ?>" target="new">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="<?= $social->largura ?>" height="<?= $social->altura ?>" fill="#fff" viewBox="0 0 48 48">
                            <path d="<?= $social->icone ?>"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>

        <div id="request"></div>
    </div>
    <!-- Mini player fim -->

    <main id="conteudo-principal">