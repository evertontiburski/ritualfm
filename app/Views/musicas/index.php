<div id="container-nav" class="fadeIn">
    <div id="content">
        
        <div id="music">
            <div class="ads_970x250"><img src="<?= URL . '/public/images/' ?>ads3.jpg" width="970" height="250" alt="Publicidade" /></div>

            <div class="horizontal-section">
                <div class="title-section">MÚSICA</div>
                <div class="cards">
                    <?php foreach($dados->musicas as $musica): ?>
                        <div class="card_music">
                            <img src="<?= URL . '/public/uploads/imagens/' . htmlspecialchars($musica->imagem_musica) ?>">
                            <div class="card-music-bg">
                                <div class="card-music-text">
                                    <span><a href="<?= htmlspecialchars(URL . '/musica/' . $musica->slug) ?>"><?= htmlspecialchars($musica->titulo) ?></a></span>
                                    <div class="description"><?= htmlspecialchars($musica->subtitulo) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                </div>
            </div>
            
            <button class="form-button events-button">VEJA MAIS</button>
            <div class="ads_970x250"><img src="<?= URL . '/public/images/' ?>ads4.jpg" width="970" height="250" alt="Publicidade" /></div>
        </div>

    </div>
</div>

<div id="mini_player"></div>