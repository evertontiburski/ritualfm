<div id="container-nav" class="fadeIn">

    <div class="form-wrapper">

        <form class="user-form" method="post" action="<?= URL ?>/usuarios/login">

            <h2>Acessar sua Conta</h2>
            <p class="form-subtitle">Faça o login para continuar.</p>

            <div class="form-group">
                <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                <label for="email" class="form-label">E-mail</label>
            </div>

            <div class="form-group">
                <input type="password" name="senha" id="senha" class="form-input" placeholder=" " required>
                <label for="senha" class="form-label">Senha</label>
            </div>

            <input class="form-button" type="submit" value="Login">

            <div class="resposta"></div>

        </form>

        <div class="form-links">
            <a href="<?= URL . '/usuarios/recuperacao' ?>" class="ajax-link">Esqueceu a senha?</a>
            <span>•</span>
            <a href="<?= URL . '/usuarios/cadastrar' ?>" class="ajax-link">Não tem uma conta? <strong>Cadastre-se</strong></a>
        </div>

    </div>

</div>