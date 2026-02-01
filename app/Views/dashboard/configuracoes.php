<div class="dashboard-container">

    <h2>Configurações da Conta</h2>
    <p class="subtitle">Gerencie suas informações pessoais e de segurança.</p>

    <div class="dashboard-layout">

        <?php include '../app/Views/dashboard/menu.php'; ?>

        <div class="dashboard-content">

            <div class="settings-layout">

                <div class="settings-profile-card">
                    <img src="<?= URL . '/public/images/icons/icon_120x120.png' ?>" alt="Avatar do Usuário" class="settings-avatar">
                    <h3><?= htmlspecialchars($dados['usuario']->nome) ?></h3>
                    <p class="user-email"><?= htmlspecialchars($dados['usuario']->email) ?></p>

                    <div class="settings-info-dates">
                        <div class="info-item">
                            <span class="info-label">
                                <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Cadastrado em:
                            </span>
                            <span class="info-value"><?= htmlspecialchars(Funcoes::data_por_extenso($dados['usuario']->cadastrado_em ?? 'DD/MM/AAAA')) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">
                                <svg xmlns="http://www.w3.org/2000/svg" class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Última alteração:
                            </span>
                            <span class="info-value"><?= htmlspecialchars(Funcoes::data_por_extenso($dados['usuario']->atualizado_em) ?? 'N/D') ?></span>
                        </div>
                    </div>
                </div>

                <div class="settings-profile-card">
                    <form action="<?= URL . '/dashboard/configuracoes/atualizar-dados' ?>" method="POST" class="form-wrapper user-dashboard">

                        <h3>Alterar Dados Pessoais</h3>

                        <div class="form-group">
                            <input type="text" id="nome" name="nome" class="form-input" placeholder=" " value="<?= htmlspecialchars($dados['usuario']->nome ?? '') ?>">
                            <label for="nome" class="form-label">Nome de Usuário</label>
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" name="email" class="form-input" placeholder=" " value="<?= htmlspecialchars($dados['usuario']->email ?? '') ?>">
                            <label for="email" class="form-label">E-mail</label>
                        </div>

                        <h3>Alterar Senha</h3>

                        <div class="form-group">
                            <input type="password" id="senha_atual" name="senha_atual" class="form-input" placeholder=" ">
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                        </div>
                        <div class="form-group">
                            <input type="password" id="nova_senha" name="nova_senha" class="form-input" placeholder=" ">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                        </div>
                        <div class="form-group">
                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" placeholder=" ">
                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        </div>

                        <button type="submit" class="form-button">Salvar Alterações</button>
                        <div class="resposta"></div>
                    </form>
                </div>

                <!-- <div class="settings-profile-card">
                    <h3>Alterar Senha</h3>
                    <form action="<?= URL . '/dashboard/configuracoes/atualizar-senha' ?>" method="POST" class="form-wrapper user-dashboard">
                        <div class="form-group">
                            <input type="password" id="senha_atual" name="senha_atual" class="form-input" placeholder=" " required>
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                        </div>
                        <div class="form-group">
                            <input type="password" id="nova_senha" name="nova_senha" class="form-input" placeholder=" " required>
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                        </div>
                        <div class="form-group">
                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" placeholder=" " required>
                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        </div>
                        <button type="submit" class="form-button">Alterar Senha</button>
                        <div class="resposta"></div>
                    </form>
                </div> -->


            </div>


        </div>

    </div>
</div>