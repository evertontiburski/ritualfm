<div id="container-nav" class="fadeIn">
    <div id="content">
        <div id="news">
            <div class="ads_970x250"><img src="<?= URL . '/public/images/' ?>ads1.jpg" width="970" height="250" alt="" /></div>
            <div class="news-section">
                <div class="section">
                    <div class="title-section sticky-news">MÚSICA</div>

                    <?php
                    // Prepara as variáveis para os links de compartilhamento
                    $currentUrl = URL . '/musica/' . $dados->musica_slug->slug;
                    $encodedUrl = urlencode($currentUrl);
                    $shareText = urlencode($dados->musica_slug->titulo);
                    ?>

                    <div class="news-base">
                        <div class="news-feature" style="background-image: url('<?= URL . '/public/uploads/imagens/' . $dados->musica_slug->imagem_musica ?>');">
                            <div class="news-bg">
                                <div class="news-text">
                                    <span><?= $dados->musica_slug->titulo ?></span>
                                    <div class="description"><?= $dados->musica_slug->subtitulo ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="post-meta-actions">
                            <div class="post-metadata">
                                <div class="post-author">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="meta-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4" class="colors-icon"></circle>
                                    </svg>
                                    <span>Por <strong><?= htmlspecialchars($dados->musica_slug->usuario_id ?? 'RitualFM') ?></strong></span>
                                </div>
                                <div class="post-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="meta-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" class="colors-icon-border"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>Publicado <?= Funcoes::contarTempo($dados->musica_slug->data_criacao) ?></span>
                                </div>
                            </div>

                            <div class="post-share">
                                <div class="share-links">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" target="_blank" rel="noopener noreferrer" class="share-link facebook" title="Compartilhar no Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                        </svg>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>%20<?= $encodedUrl ?>" target="_blank" rel="noopener noreferrer" class="share-link whatsapp" title="Compartilhar no WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                        </svg>
                                    </a>
                                    <a href="mailto:contato@ritualfm.com" target="_blank" rel="noopener noreferrer" class="share-link email" title="Compartilhar por E-mail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </a>
                                    <a href="#" class="share-link copy-link" title="Copiar Link" onclick="navigator.clipboard.writeText('<?= $currentUrl ?>'); alert('Link copiado!'); return false;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.72"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.72-1.72"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="post-content">
                            <?= $dados->musica_slug->texto ?>

                            <?php
                            // Verifica se existe um ID de player antes de renderizar o componente
                            $player_id = $dados->musica_slug->player_id;
                            if (!empty($player_id)) :
                            ?>
                                <div class="music-player-container">

                                    <div class="player-video-wrapper">
                                        <iframe
                                            src="https://www.youtube.com/embed/<?= htmlspecialchars($player_id) ?>"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>

                                    <div class="player-details">
                                        <ul>
                                            <li>
                                                <span class="info-label">Artista</span>
                                                <strong class="info-value"><?= htmlspecialchars($dados->musica_slug->artista) ?></strong>
                                            </li>
                                            <li>
                                                <span class="info-label">Álbum</span>
                                                <strong class="info-value"><?= htmlspecialchars($dados->musica_slug->album) ?></strong>
                                            </li>
                                            <li>
                                                <span class="info-label">Lançamento</span>
                                                <strong class="info-value"><?= htmlspecialchars(Funcoes::dataFormatada($dados->musica_slug->lancamento)) ?></strong>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            <?php endif; ?>
                        </div>

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
            <div class="ads_970x250"><img src="<?= URL . '/public/images/' ?>ads4.jpg" width="970" height="250" alt="" /></div>
        </div>
    </div>
</div>

<!-- Player -->
<div id="mini_player"></div>