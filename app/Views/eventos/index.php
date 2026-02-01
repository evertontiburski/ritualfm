<div id="container-nav" class="fadeIn">
    <div id="content">
        <div id="events">
            <div class="ads_970x250" style="margin-bottom: 3rem;"><img src="<?= URL . '/public/images/' ?>ads2.jpg" width="970" height="250" alt="Publicidade" /></div>

            <div class="horizontal-section">
                <div class="title-section">EVENTOS</div>

                <div class="cards" style="margin-bottom: 110px;">

                    <?php if (isset($dados) && !empty($dados->eventos)): ?>
                        <?php foreach ($dados->eventos as $evento): ?>
                        <div class="card_event">
                            <input type="checkbox" id="event-toggle-<?= $evento->id_evento ?>" class="event-toggle-css">

                            <label for="event-toggle-<?= $evento->id_evento ?>" class="event-trigger-label">
                                <img src="<?= URL . '/public/uploads/imagens/' . htmlspecialchars($evento->imagem_evento) ?>" alt="Pôster do evento: <?= htmlspecialchars($evento->imagem_evento) ?>">
                            </label>

                            <div class="event-modal-css">
                                <div class="event-modal-content-css">
                                    <label for="event-toggle-<?= $evento->id_evento ?>" class="close-modal-btn-css" title="Fechar"></label>

                                    <div class="modal-video-container-evento">
                                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($evento->video_id_youtube) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>

                                    <div class="modal-details">

                                        <h2 id="modal-title"><?= htmlspecialchars($evento->titulo) ?></h2>
                                        <p class="modal-attraction" id="modal-attraction"><?= htmlspecialchars($evento->atracao) ?></p>

                                        <div class="modal-info-list">
                                            <div class="info-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                                </svg>
                                                <span id="modal-date"><?= htmlspecialchars(Funcoes::dataFormatada($evento->data)) ?></span>
                                            </div>
                                            <div class="info-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                                </svg>
                                                <span id="modal-location"><?= htmlspecialchars($evento->local) ?></span>
                                            </div>
                                        </div>

                                        <p class="modal-description" id="modal-description"><?= htmlspecialchars($evento->texto) ?></p>

                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <?= 'Nenhum evento cadastrado' ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="ads_970x250" style="margin-top: 3rem;"><img src="<?= URL . '/public/images/' ?>ads4.jpg" width="970" height="250" alt="Publicidade" /></div>
        </div>
    </div>
</div>

<div id="mini_player"></div>