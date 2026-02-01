<div class="admin-container">

    <div class="admin-layout">

        <aside class="admin-sidebar">

            <?php include '../app/Views/admin/menu.php'; ?>

        </aside>

        <main class="admin-content">

            <div class="admin-content-header">

                <div>
                    <h2>Lista de mensagens recebida</h2>
                    <p class="subtitle">Leia, analise ou delete as mensagens.</p>
                </div>

            </div>

            <div class="admin-content-box" style="background: none !important;">
                <span id="resposta"></span>
                <div class="comment-moderation-list">

                    <?php foreach ($dados->contatos as $c): ?>
                        <?php if ($c->status != 1): ?>

                            <div class="comment-card pending">
                                <div class="comment-card-header">
                                    <div class="comment-card-author-msg">
                                        <strong><?= htmlspecialchars($c->nome) ?></strong>
                                        <a href="mailto:<?= htmlspecialchars($c->email) ?>"><?= htmlspecialchars($c->email) ?></a>
                                    </div>
                                    <div class="comment-card-date"><?= Funcoes::contarTempo($c->data_criacao) ?></div>
                                </div>
                                <div class="comment-card-body">
                                    <p><?= htmlspecialchars($c->mensagem) ?></p>
                                </div>
                                <div class="comment-card-actions">
                                    <form class="form-delete" action="<?= URL . '/admin/contatos/deletar/' . $c->id_contato ?>" method="post">
                                        <button type="submit" class="btn btn-sm btn-danger ajax-link">Deletar</button>
                                    </form>
                                    <form class="form-delete" action="<?= URL . '/admin/contatos/visto/' . $c->id_contato ?>" method="post">
                                        <button type="submit" class="btn btn-sm btn-success ajax-link">Visto</button>
                                    </form>
                                </div>
                            </div>

                        <?php else: ?>

                            <div class="comment-card">
                                <div class="comment-card-header">
                                    <div class="comment-card-author-msg">
                                        <strong><?= htmlspecialchars($c->nome) ?></strong>
                                        <a href="mailto:<?= htmlspecialchars($c->email) ?>" class="ajax-link"><?= htmlspecialchars($c->email) ?></a>
                                    </div>
                                    <div class="comment-card-date"><?= Funcoes::contarTempo($c->data_criacao) ?></div>
                                </div>
                                <div class="comment-card-body">
                                    <p><?= htmlspecialchars($c->mensagem) ?></p>
                                </div>
                                <div class="comment-card-actions">
                                    <span class="status-approved">Visto</span>
                                    <form class="form-delete" action="<?= URL . '/admin/contatos/deletar/' . $c->id_contato ?>" method="post">
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