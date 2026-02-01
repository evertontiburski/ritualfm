<div class="dashboard-container">

    <h2>Meus Comentários</h2>
    <p class="subtitle">Visualize e gerencie todos os comentários que você já fez.</p>

    <div class="dashboard-layout">

        <?php include '../app/Views/dashboard/menu.php'; ?>

        <div class="dashboard-content">

            <div class="content-layout">

                <div class='resposta'></div>

                <?php if (isset($dados) && !empty($dados->comentarios)): ?>
                    <?php foreach ($dados->comentarios as $comentario): ?>
                        <div class="admin-content-box">
                            <div class="comentario-item">
                                <blockquote>
                                    <?= $comentario->comentario ?>
                                </blockquote>
                                <div class="comentario-footer">
                                    Comentou <?= Funcoes::data_por_extenso($comentario->data_comentario) ?> na notícia <a href="<?= URL . '/post/' . $comentario->slug  ?>">"<?= $comentario->titulo_do_post ?>"</a>
                                </div>
                                <div class="actions-cell">
                                    <form class="form-delete" action="<?= URL . '/dashboard/comentarios/' . $comentario->id_comentario ?>" method="post" title="Deletar Comentário">
                                        <button type="submit" class="btn btn-sm btn-danger ajax-link">Deletar Comentário</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="admin-content-box">
                        <div class="novidade-item">
                            <div class="novidade-header">
                                <svg xmlns="http://www.w3.org/2000/svg" class="dashboard-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Opsss</span>
                            </div>
                            <h3 class="novidade-title">Nenhum comentário cadastrado</h3>
                            <p class="novidade-body">Você ainda não comentou em nosso site, assim que você começar a comentar seus comentários aparecerão aqui.</p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</div>