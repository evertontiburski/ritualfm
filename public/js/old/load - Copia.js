/**
 * load.js - Script Unificado de Controle de UI e Navegação AJAX
 */
(() => {
    // --- ELEMENTOS E VARIÁVEIS GLOBAIS DO SCRIPT ---
    const mainContainer = document.getElementById('conteudo-principal');

    // --- CONTROLES DE UI (Sua lógica original) ---
    const navControls = {
        toggleElement: (id, styleProp, value) => {
            const el = document.getElementById(id);
            if (el) el.style[styleProp] = value;
        },
        openNav: () => navControls.toggleElement("main-menu", "width", "35%"),
        closeNav: () => navControls.toggleElement("main-menu", "width", "0px"),
        openPlayer: () => navControls.toggleElement("mini_player", "height", "60px"),
        closePlayer: () => navControls.toggleElement("mini_player", "height", "0px"),
    };

    // Disponibiliza as funções de abrir/fechar menu para os botões do HTML
    window.openNav = navControls.openNav;
    window.closeNav = navControls.closeNav;

    const isHomePage = (path) => {
        try {
            if (typeof BASE_URL === 'undefined' || BASE_URL === '') {
                console.error("BASE_URL não está definida no HTML.");
                return path === '/' || path === '/index.php'; // Fallback
            }

            // Extrai o CAMINHO da sua BASE_URL e limpa.
            // Ex: 'http://localhost/ritualfm' -> '/ritualfm'
            const basePathname = new URL(BASE_URL).pathname.replace(/\/$/, '');

            // Extrai o CAMINHO da URL da página atual e limpa.
            // Ex: 'http://localhost/ritualfm/post/...' -> '/ritualfm/post/...'
            // Ex: 'http://localhost/ritualfm' -> '/ritualfm'
            const currentPathname = new URL(path).pathname.replace(/\/$/, '');

            // Compara APENAS os caminhos.
            // É a homepage se os caminhos forem idênticos ou se incluir index.php
            return currentPathname === basePathname || currentPathname === `${basePathname}/index.php`;

        } catch (e) {
            // Se a 'path' recebida não for uma URL completa, pode dar erro.
            // Este fallback trata a 'path' como se já fosse um caminho.
            if (e instanceof TypeError && path.startsWith('/')) {
                const basePathname = new URL(BASE_URL).pathname.replace(/\/$/, '');
                const currentPathname = path.replace(/\/$/, '');
                return currentPathname === basePathname || currentPathname === `${basePathname}/index.php`;
            }
            console.error("Erro ao verificar a página inicial:", e, "Path:", path);
            return false;
        }
    };

    const updateUIForPage = (path) => {
        const home = isHomePage(path);

        // ADICIONE ESTAS DUAS LINHAS PARA DEPURAR:
        console.log("Caminho (path) verificado:", path);
        console.log("É a homepage? (resultado de isHomePage):", home);

        // Tenta encontrar os elementos na página. Eles podem não existir dependendo da view carregada.
        const coverElement = document.getElementById('cover-site');
        const mainPlayerContainer = document.getElementById('row'); // O container do "player grande"

        if (home) {
            // ESTAMOS NA HOMEPAGE:

            // Esconde o mini player
            navControls.closePlayer();

            // Garante que os elementos da home (player grande e cover) estejam visíveis.
            // A verificação 'if' é importante para o caso de o DOM ainda não ter atualizado.
            if (coverElement) {
                coverElement.style.display = 'block';
            }
            if (mainPlayerContainer) {
                // Usamos 'flex' porque o CSS original para #row usa display: flex;
                mainPlayerContainer.style.display = 'flex';
            }

        } else {
            // ESTAMOS EM OUTRA PÁGINA:

            // Mostra o mini player
            navControls.openPlayer();

            // Esconde os elementos da home, caso ainda estejam visíveis por algum motivo.
            // O mais provável é que eles já tenham sido removidos pelo AJAX,
            // mas esta é uma garantia extra.
            if (coverElement) {
                coverElement.style.display = 'none';
            }
            if (mainPlayerContainer) {
                mainPlayerContainer.style.display = 'none';
            }
        }
    };


    // MOTOR DE NAVEGAÇÃO AJAX
    async function loadPage(url, pushState = true) {
        if (!mainContainer) {
            console.error("Container principal '#conteudo-principal' não encontrado. Abortando AJAX.");
            window.location.href = url;
            return;
        }

        document.body.style.cursor = 'wait';

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Falha ao carregar a página: ' + response.statusText);
            }

            const contentType = response.headers.get("content-type");

            if (contentType && contentType.indexOf("application/json") !== -1) {
                const data = await response.json();

                if (data.data && data.data.acao === 'logout') {
                    const loginUrl = data.data.redirecionar;

                    // Recria o HTML do menu do usuário no estado "deslogado"
                    const loggedOutMenuHTML = `
                    <a href="#" class="ajax-link">
                        <div class="menu_ico">
                            <svg width="100%" height="100%" viewBox="0 3 24 24" aria-label="Person">
                                <g fill="none" fill-rule="evenodd">
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                    <path d="M14 8a2 2 0 10-4 0 2 2 0 004 0zm2 0a4 4 0 11-8 0 4 4 0 018 0zM7 19a1 1 0 01-2 0 7 7 0 0114 0 1 1 0 01-2 0 5 5 0 00-10 0z" fill="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <a href="${loginUrl}" class="ajax-link">Minha conta</a>
                    </a>
                `;
                    // Substitui o HTML do topo antes de redirecionar
                    $('#user-menu-container').html(loggedOutMenuHTML);
                }

                if (data.data && data.data.redirecionar) {
                    loadPage(data.data.redirecionar, pushState);
                    return;
                }
            } else {
                // Trata como HTML normal
                const newContentHtml = await response.text();
                mainContainer.innerHTML = newContentHtml;

                if (typeof inicializarGraficosDoDashboard === 'function') {
                    inicializarGraficosDoDashboard();
                }

                const pageTitle = url.split('/').filter(Boolean).pop() || 'Início';
                document.title = "RITUAL FM | " + pageTitle.charAt(0).toUpperCase() + pageTitle.slice(1);
                if (pushState) {
                    history.pushState({ path: url }, document.title, url);
                }

                updateUIForPage(url);
                navControls.closeNav();
                if (isHomePage(url)) {
                    syncPlayerUI();
                }
            }

        } catch (error) {
            console.error('Erro no carregamento AJAX:', error);
            window.location.href = url;
        } finally {
            document.body.style.cursor = 'default';
        }
    }

    // torna a função loadPage global e permite que o arquivo proccess.js a chame
    window.loadPage = loadPage;


    // INICIALIZAÇÃO E EVENT LISTENERS
    const init = () => {
        // 1. Define o estado inicial da UI
        updateUIForPage(window.location.pathname);

        // 2. Intercepta os cliques nos links AJAX
        document.body.addEventListener('click', function (e) {
            const link = e.target.closest('a.ajax-link');
            if (link && !e.metaKey && !e.ctrlKey) {
                e.preventDefault();
                loadPage(link.href);
            }
        });

        // 3. Lida com os botões de voltar/avançar do navegador
        window.addEventListener('popstate', function (e) {
            if (e.state && e.state.path) {
                loadPage(e.state.path, false);
            }
        });

        // 4. Salva o estado da página inicial no histórico do navegador
        if (history.state === null) {
            history.replaceState({ path: window.location.href }, document.title, window.location.href);
        }
    };

    // Garante que o script rode após o DOM estar pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();