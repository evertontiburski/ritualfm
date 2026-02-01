<div class="dashboard-container">

    <h2>Painel de Controle</h2>
    <p class="subtitle">Seja bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>.</p>

    <div class="dashboard-layout">

        <?php include '../app/Views/dashboard/menu.php'; ?>

        <div class="dashboard-content">

            <div class="dashboard-status-grid">
                <div class="status-card">
                    <h3>Comentários</h3>
                    <span class="status-number"><?= $dados->comentarios[0]->total ?? 'N/D' ?></span>
                </div>
                <div class="status-card">
                    <h3>Horas online</h3>
                    <span class="status-number">56h</span>
                </div>
                <div class="status-card">
                    <h3>Notificações</h3>
                    <span class="status-number"><?= $dados->notificacoes[0]->total ?? 'N/D' ?></span>
                </div>
            </div>

            <div class="access-info-container">
                <div class="info-item">
                    <span class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                        Endereço IP
                    </span>
                    <span class="info-value"><?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        Navegador e SO
                    </span>
                    <span class="info-value"><?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.72"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.72-1.72"></path>
                        </svg>
                        Página Visitada (URL)
                    </span>
                    <span class="info-value"><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 17H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4l-3 3v-3z"></path>
                            <path d="m9 12-2-2 2-2"></path>
                            <path d="m15 12 2-2-2-2"></path>
                        </svg>
                        Página de Origem (Referrer)
                    </span>
                    <span class="info-value"><?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Acesso direto') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" class="colors-icon-border"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Data e Hora do Acesso
                    </span>
                    <span class="info-value"><?= date('d/m/Y H:i:s') ?></span>
                </div>
            </div>

        </div>
    </div>
</div>