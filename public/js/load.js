/**
 * load.js - Script Unificado de Controle de UI e Navegação AJAX
 * (Versão com sistema de loader integrado)
 */
(() => {
    // --- ELEMENTOS E VARIÁVEIS GLOBAIS DO SCRIPT ---
    const mainContainer = document.getElementById('conteudo-principal');
    const loaderElement = document.getElementById('loader'); // NOVO: Elemento do loader


    /**
     * Mostra a tela de carregamento.
     */
    const showLoader = () => {
        if (loaderElement) {
            loaderElement.classList.remove('fade-out');
        }
    };

    /**
     * Esconde a tela de carregamento com uma animação de fade.
     */
    const hideLoader = () => {
        if (loaderElement) {
            loaderElement.classList.add('fade-out');
        }
    };


    // --- CONTROLES DE UI
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
            const basePathname = new URL(BASE_URL).pathname.replace(/\/$/, '');
            const currentPathname = new URL(path).pathname.replace(/\/$/, '');
            return currentPathname === basePathname || currentPathname === `${basePathname}/index.php`;
        } catch (e) {
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
        const coverElement = document.getElementById('cover-site');
        const mainPlayerContainer = document.getElementById('row');

        if (home) {
            navControls.closePlayer();
            if (coverElement) coverElement.style.display = 'block';
            if (mainPlayerContainer) mainPlayerContainer.style.display = 'flex';
        } else {
            navControls.openPlayer();
            if (coverElement) coverElement.style.display = 'none';
            if (mainPlayerContainer) mainPlayerContainer.style.display = 'none';
        }
    };

    // MOTOR DE NAVEGAÇÃO AJAX
    async function loadPage(url, pushState = true) {
        if (!mainContainer) {
            console.error("Container principal '#conteudo-principal' não encontrado. Abortando AJAX.");
            window.location.href = url;
            return;
        }

        showLoader(); // Mostra o loader no início da navegação

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error('Falha ao carregar a página: ' + response.statusText);
            }

            const contentType = response.headers.get("content-type");

            if (contentType && contentType.indexOf("application/json") !== -1) {

                const data = await response.json();

                // lógica para JSON/logout
                if (data.data && data.data.acao === 'logout') {
                    const loginUrl = data.data.redirecionar;
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
                     </a>`;
                    $('#user-menu-container').html(loggedOutMenuHTML);
                }


                if (data.data && data.data.redirecionar) {
                    loadPage(data.data.redirecionar, pushState);
                    return;
                }
                // Se não houver redirecionamento, esconda o loader
                setTimeout(hideLoader, 300);

            } else {
                // Trata como HTML normal
                const newContentHtml = await response.text();
                mainContainer.innerHTML = newContentHtml;

                if (typeof inicializarEditorDeTexto === 'function') {
                    inicializarEditorDeTexto();
                }

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

                // Logica para esconder o loader
                if (isHomePage(url)) {

                    let loaderHidden = false;
                    const hideLoaderOnEvent = () => {
                        if (!loaderHidden) {
                            hideLoader();
                            loaderHidden = true;
                        }
                    };

                    document.addEventListener('coverArtLoaded', hideLoaderOnEvent, { once: true });
                    setTimeout(hideLoaderOnEvent, 3000);

                    if (typeof syncPlayerUI === 'function') {
                        syncPlayerUI(true);
                    }

                } else {

                    // Esconde o loader rapidamente, se estamos indo para qualquer outra página
                    setTimeout(hideLoader, 300);
                }
            }

        } catch (error) {
            console.error('Erro no carregamento AJAX:', error);
            // Em caso de erro, esconde o loader e redireciona.
            hideLoader();
            window.location.href = url;
        }

    }

    // torna a função loadPage global e permite que o arquivo proccess.js a chame
    window.loadPage = loadPage;

    const init = () => {

        let loaderHidden = false;

        const hideInitialLoaderOnce = () => {
            if (!loaderHidden) {
                hideLoader();
                loaderHidden = true;
            }
        };

        // Espera pelo sinal do script do player
        document.addEventListener('coverArtLoaded', hideInitialLoaderOnce);

        // (Segurança): Esconde o loader após 5 segundos, caso o evento não dispare
        setTimeout(hideInitialLoaderOnce, 3000);


        // Define o estado inicial da UI
        updateUIForPage(window.location.href);

        // Intercepta os cliques nos links AJAX
        document.body.addEventListener('click', function (e) {
            const link = e.target.closest('a.ajax-link');
            if (link && !e.metaKey && !e.ctrlKey) {
                e.preventDefault();
                loadPage(link.href);
            }
        });

        // Lida com os botões de voltar/avançar do navegador
        window.addEventListener('popstate', function (e) {
            if (e.state && e.state.path) {
                loadPage(e.state.path, false);
            }
        });

        // Salva o estado da página inicial no histórico do navegador
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


    // Script de gerenciamento de input de imagem, exibe os nomes dos arquivos de imagem
    document.addEventListener('change', function (event) {
        // Verifica se o evento foi disparado por um dos nossos inputs de imagem
        const inputFile = event.target;
        if (inputFile && inputFile.matches('[data-role="file-input-target"]')) {

            // Encontra o container do componente mais próximo
            const componentContainer = inputFile.closest('[data-component="file-input"]');
            if (!componentContainer) return;

            // Dentro daquele container, encontra o span de display correspondente
            const fileNameDisplay = componentContainer.querySelector('[data-role="file-input-display"]');

            if (fileNameDisplay) {
                if (inputFile.files.length > 0) {
                    fileNameDisplay.textContent = inputFile.files[0].name;
                } else {
                    const originalText = fileNameDisplay.getAttribute('data-original-text');
                    fileNameDisplay.textContent = originalText;
                }
            }
        }
    });

})();