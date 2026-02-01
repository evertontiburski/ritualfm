<div id="container-nav" class="fadeIn">

    <div class="form-wrapper">

        <form class="user-form" method="post" action="<?= URL ?>/usuarios/cadastrar">

            <h2>Crie sua Conta</h2>
            <p class="form-subtitle">É rápido e fácil. Preencha os dados abaixo para começar.</p>

            <div class="form-group">
                <input type="text" name="nome" id="nome" class="form-input" placeholder=" " required>
                <label for="nome" class="form-label">Nome completo</label>
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                <label for="email" class="form-label">E-mail</label>
            </div>

            <div class="form-group">
                <input type="password" name="senha" id="senha" class="form-input" placeholder=" " required>
                <label for="senha" class="form-label">Senha</label>
            </div>

            <div class="form-group">
                <input type="password" name="confirma_senha" id="confirma_senha" class="form-input" placeholder=" " required>
                <label for="confirma_senha" class="form-label">Confirme a senha</label>
            </div>

            <input class="form-button ajax-link" type="submit" value="Criar Conta">

            <div class="resposta"></div>

        </form>

        <div class="form-links">
            <a href="<?= URL . '/usuarios/login' ?>" class="ajax-link">Já tem uma conta? <strong>Faça o login</strong></a>
        </div>

    </div>

</div>