<div class="dashboard-container">

    <h2>Novidades</h2>
    <p class="subtitle">Fique por dentro das últimas atualizações e anúncios.</p>

    <div class="dashboard-layout">

        <?php include '../app/Views/dashboard/menu.php'; ?>

        <div class="dashboard-content">

            <div class="content-layout">

                <?php if (isset($dados) && !empty($dados->notificacoes)): ?>
                    <?php foreach ($dados->notificacoes as $notificacao): ?>
                        <div class="admin-content-box">
                            <div class="novidade-item">
                                <div class="novidade-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span><?= Funcoes::data_por_extenso($notificacao->data_criacao) ?></span>
                                </div>
                                <h3 class="novidade-title"><?= $notificacao->titulo ?></h3>
                                <p class="novidade-body"><?= $notificacao->texto ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="admin-content-box">
                        <div class="novidade-item">
                            <div class="novidade-header">
                                <svg xmlns="http://www.w3.org/2000/svg" class="dashboard-menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                <span>Opsss</span>
                            </div>
                            <h3 class="novidade-title">Nenhuma novidade</h3>
                            <p class="novidade-body">Fique tranquilo, você será o primeiro a saber quando lançarmos novos recursos no site.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- <div class="admin-content-box">
                    <div class="novidade-item">
                        <div class="novidade-header">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>15 de Agosto de 2025</span>
                        </div>
                        <h3 class="novidade-title">Manutenção Programada no Servidor</h3>
                        <p class="novidade-body">
                            Informamos que no próximo dia 02 de Setembro, entre 02:00 e 04:00 da manhã, nossos serviços passarão por uma manutenção programada para melhorias de performance e segurança. Agradecemos a compreensão.
                        </p>
                    </div>
                </div> -->

            </div>

        </div>

    </div>
</div>