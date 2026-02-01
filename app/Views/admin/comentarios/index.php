<div class="admin-container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <?php include '../app/Views/admin/menu.php'; ?>
        </aside>

        <main class="admin-content">
            <div class="admin-content-header">
                <div>
                    <h2>Moderação de Comentários</h2>
                    <p class="subtitle">Aprove ou remova os comentários.</p>
                </div>
            </div>

            <div class="admin-content-box" style="background: none !important;">
                <span id="resposta"></span>
                <div class="comment-moderation-list">

                    <?php foreach ($dados->comentarios as $c): ?>

                        <?php if ($c->status != 1): ?>

                            <div class="comment-card pending">
                                <div class="comment-card-header">
                                    <div class="comment-card-author">
                                        <strong><?= $c->nome ?></strong> comentou em
                                        <a href="<?= URL . '/post/' . $c->slug ?>" class="ajax-link" target="_blank"><?= $c->titulo ?></a>
                                    </div>
                                    <div class="comment-card-date"><?= Funcoes::contarTempo($c->data_comentario) ?></div>
                                </div>
                                <div class="comment-card-body">
                                    <p><?= $c->comentario ?></p>
                                </div>
                                <div class="comment-card-actions">
                                    <form class="form-delete" action="<?= URL . '/admin/comentarios/deletar/' . $c->id_comentario ?>" method="post">
                                            <button type="submit" class="btn btn-sm btn-danger ajax-link">Deletar</button>
                                    </form>
                                    <form class="form-delete" action="<?= URL . '/admin/comentarios/aprovar/' . $c->id_comentario ?>" method="post">
                                            <button type="submit" class="btn btn-sm btn-success ajax-link">Aprovar</button>
                                    </form>
                                </div>
                            </div>

                        <?php else: ?>
                            
                            <div class="comment-card">
                                <div class="comment-card-header">
                                    <div class="comment-card-author">
                                        <strong><?= $c->nome ?></strong> comentou em
                                        <a href="<?= URL . '/post/' . $c->slug ?>" class="ajax-link" target="_blank"><?= $c->titulo ?></a>
                                    </div>
                                    <div class="comment-card-date"><?= Funcoes::contarTempo($c->data_comentario) ?></div>
                                </div>
                                <div class="comment-card-body">
                                    <p><?= $c->comentario ?></p>
                                </div>
                                <div class="comment-card-actions">
                                    <span class="status-approved">Aprovado</span>
                                    <form class="form-delete" action="<?= URL . '/admin/comentarios/deletar/' . $c->id_comentario ?>" method="post">
                                            <button type="submit" class="btn btn-sm btn-danger ajax-link">Deletar</button>
                                    </form>
                                </div>
                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>
            </div>
        </main>
    </div>
</div>