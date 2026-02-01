/**
 * load.js - Versão Final com Parser Inteligente de Conteúdo
 */
(() => {
    // --- ELEMENTOS GLOBAIS ---
    const mainContainer = document.getElementById('conteudo-principal');
    const transitionOverlay = document.getElementById('page-transition-overlay');

    // --- CONTROLES DE UI ---
    const navControls = {
        closeNav: () => {
            const el = document.getElementById("main-menu");
            if (el) el.style.width = "0px";
        },
        openPlayer: () => {
            const el = document.getElementById("mini_player");
            if (el) el.style.height = "60px";
        },
        closePlayer: () => {
            const el = document.getElementById("mini_player");
            if (el) el.style.height = "0px";
        },
    };
    window.openNav = () => {
        const el = document.getElementById("main-menu");
        if (el) el.style.width = "35%";
    };
    window.closeNav = navControls.closeNav;

    // --- FUNÇÕES AUXILIARES ---
    const isHomePage = (path) => {
        try {
            const basePathname = new URL(BASE_URL).pathname.replace(/\/$/, '');
            const urlObject = new URL(path, BASE_URL);
            const currentPathname = urlObject.pathname.replace(/\/$/, '');
            return currentPathname === basePathname || currentPathname === `${basePathname}/index.php`;
        } catch (e) {
            return path === '/' || path === '/index.php';
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
    
    // --- LÓGICA DE ANIMAÇÃO ---
    const animationDuration = 800;
    function leavePage(directionClass) {
        return new Promise(resolve => {
            transitionOverlay.className = 'page-transition-overlay';
            transitionOverlay.classList.add(directionClass);
            transitionOverlay.offsetHeight;
            transitionOverlay.classList.add('is-active');
            setTimeout(resolve, animationDuration);
        });
    }
    function enterPage() {
        return new Promise(resolve => {
            transitionOverlay.classList.remove('is-active');
            setTimeout(resolve, animationDuration);
        });
    }
    
    // NOVO: Parser de conteúdo HTML
    function parseAndInjectContent(htmlString) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlString, 'text/html');
        const newContent = doc.getElementById('conteudo-principal');

        if (newContent && newContent.innerHTML.trim() !== "") {
            mainContainer.innerHTML = newContent.innerHTML;
        } else {
            // Fallback: se não encontrar o container ou ele estiver vazio, injeta o corpo todo
            mainContainer.innerHTML = doc.body.innerHTML;
        }
    }

    // --- MOTOR DE NAVEGAÇÃO AJAX ---
    async function loadPage(url, pushState = true) {
        const currentUrl = new URL(window.location.href);
        const destinationUrl = new URL(url);
        if (currentUrl.pathname.replace(/\/$/, '') === destinationUrl.pathname.replace(/\/$/, '')) {
            navControls.closeNav();
            return;
        }

        const isNavigatingToHome = isHomePage(url);
        const directionClass = isNavigatingToHome ? 'to-home' : 'from-home';

        await leavePage(directionClass);

        try {
            document.body.style.cursor = 'wait';
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error(`Falha ao carregar: ${response.statusText}`);
            
            const htmlString = await response.text();
            parseAndInjectContent(htmlString);

            document.title = "RITUAL FM | " + (url.split('/').filter(Boolean).pop() || 'Início');
            if (pushState) history.pushState({ path: url }, document.title, url);
            
            updateUIForPage(url);
            navControls.closeNav();
            
            if (isNavigatingToHome) {
                syncPlayerUI();
                const coverPromise = new Promise(resolve => { window.onHomepageCoverLoaded = resolve; });
                await coverPromise;
            }
        } catch (error) {
            console.error('Erro no AJAX:', error);
            window.location.href = url;
            return;
        } finally {
            document.body.style.cursor = 'default';
            window.scrollTo(0, 0);
        }

        const coverSite = document.getElementById('cover-site');
        if (isNavigatingToHome && coverSite) {
            coverSite.classList.remove('is-hidden-for-reveal');
            coverSite.classList.add('animated', 'clipIn');
        }
        await enterPage();
    }

    window.loadPage = loadPage;
    
    // --- LÓGICA DE INICIALIZAÇÃO UNIFICADA ---
    async function init() {
        try {
            const response = await fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Falha ao buscar conteúdo inicial.');
            
            const htmlString = await response.text();
            parseAndInjectContent(htmlString);

        } catch (error) {
            console.error("Erro no carregamento inicial:", error);
            mainContainer.innerHTML = `<div class="error-message">Não foi possível carregar o conteúdo. Por favor, <a href="${window.location.href}">recarregue a página</a>.</div>`;
        }

        const page = new Page();
        page.changeTitlePage();
        page.setVolume();

        const minimumTimePromise = new Promise(resolve => setTimeout(resolve, 1500));
        const coverLoadedPromise = new Promise(resolve => {
            window.onInitialCoverLoaded = resolve;
        });
        
        getStreamingData();
        setInterval(getStreamingData, 4000);

        Promise.all([minimumTimePromise, coverLoadedPromise]).then(() => {
            window.hideLoader();
        });

        document.body.addEventListener('click', (e) => {
            const link = e.target.closest('a.ajax-link');
            if (link && !e.metaKey && !e.ctrlKey) {
                e.preventDefault();
                loadPage(link.href);
            }
        });

        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.path) {
                loadPage(e.state.path, false);
            }
        });

        if (history.state === null) {
            history.replaceState({ path: window.location.href }, document.title, window.location.href);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();