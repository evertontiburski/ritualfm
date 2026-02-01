<div id="cover-site">
    <div class="bg-mask"></div>
</div>

<div id="row">
    <div id="playerButton" class="bt_play" onclick="togglePlay()"></div>
    <div id="Song">
        <div id="currentArtist"></div>
        <div id="currentSong"></div>
    </div>
    <button aria-label="Volume" id="vl" class="volumeButton" data-player="2">
        <svg xmlns="http://www.w3.org/2000/svg" width="76" height="76" fill="none" viewBox="0 0 76 76">
            <g class="levels" id="volume">
                <circle cx="38" cy="38" r="36.5" stroke-width="3" class="level-100" onclick="setVolumeLevel(100)"></circle>
                <circle cx="38" cy="38" r="30.5" stroke-width="3" class="level-70" onclick="setVolumeLevel(70)"></circle>
                <circle cx="38" cy="38" r="24.5" stroke-width="3" class="level-50" onclick="setVolumeLevel(50)"></circle>
                <circle cx="38" cy="38" r="18.5" stroke-width="3" class="level-20" onclick="setVolumeLevel(20)"></circle>
            </g>
            <g fill="#f00" class="sound level-20" onclick="toggleMute()">
                <circle style="stroke:#f00;" cx="38" cy="38" r="12.5" fill="#000" stroke-width="3" class="level-0"></circle>
                <g class="icon">
                    <path d="M39 41.499c0 .4-.41.651-.727.4l-2.591-1.904h-1.227c-.273 0-.455-.201-.455-.502v-3.008c0-.3.182-.501.455-.501h1.227l2.59-1.905c.319-.2.728 0 .728.4v7.02z" class="mega"></path>
                    <path fill-rule="evenodd" d="M40 41.606a3.824 3.824 0 000-7.355v1.604a2.322 2.322 0 010 4.147v1.604z" clip-rule="evenodd" class="sound"></path>
                </g>
            </g>
        </svg>
    </button>
    <div class="historic">
        <div id="historicSong">
            <div class="historic_txt">ÚLTIMA TOCADA</div>
            <div class="music-info">
                <div class="artist"></div>
                <div class="song"></div>
            </div>
        </div>
    </div>
    <div id="latest_news">

        <div class="headline-cover">

            <?php foreach ($dados->posts as $post): ?>
                <div class="headline transition mContent">
                    <a href="<?= URL . '/post/' . $post->slug ?>" class="ajax-link">
                        <picture class="headline-img" style="background-image:url(<?= URL . '/public/uploads/imagens/' . $post->imagem_post ?>"></picture>
                        <div class="headline-title">
                            <span><?= strtoupper($post->titulo); ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            <li class="mContent">
                <a href="<?= URL . '/posts' ?>" class="transition ajax-link">
                    <div id="plus">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" fill="#fff" viewBox="0 0 128 128">
                            <path d="M 64 6.0507812 C 49.15 6.0507812 34.3 11.7 23 23 C 0.4 45.6 0.4 82.4 23 105 C 34.3 116.3 49.2 122 64 122 C 78.8 122 93.7 116.3 105 105 C 127.6 82.4 127.6 45.6 105 23 C 93.7 11.7 78.85 6.0507812 64 6.0507812 z M 64 12 C 77.3 12 90.600781 17.099219 100.80078 27.199219 C 121.00078 47.499219 121.00078 80.500781 100.80078 100.80078 C 80.500781 121.10078 47.500781 121.10078 27.300781 100.80078 C 7.0007813 80.500781 6.9992188 47.499219 27.199219 27.199219 C 37.399219 17.099219 50.7 12 64 12 z M 64 42 C 62.3 42 61 43.3 61 45 L 61 61 L 45 61 C 43.3 61 42 62.3 42 64 C 42 65.7 43.3 67 45 67 L 61 67 L 61 83 C 61 84.7 62.3 86 64 86 C 65.7 86 67 84.7 67 83 L 67 67 L 83 67 C 84.7 67 86 65.7 86 64 C 86 62.3 84.7 61 83 61 L 67 61 L 67 45 C 67 43.3 65.7 42 64 42 z"></path>
                        </svg>
                    </div>
                </a>
            </li>
        </div>

        <a href="https://bit.ly/3czSEww" target="new" title="LogicaHost - Soluções em Hospedagem" class="ico_logica"></a>
    </div>

    <div class="social">

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
</div>