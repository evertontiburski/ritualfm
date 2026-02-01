/**
 * @preserve
 * FreeYess - Player de Streaming de Áudio
 * Versão: 3.0
 * Originalmente de: YesStreaming / Radiowink
 *
 * Este é o código desofuscado e comentado de um player de rádio online.
 * O script original foi processado para dificultar a leitura, e esta versão
 * reverte esse processo para fins de estudo e compreensão.
 *
 * Funcionalidades principais:
 * - Toca streams de áudio de servidores Shoutcast e Icecast.
 * - Cria dinamicamente a interface do player (HTML/CSS).
 * - Busca e exibe periodicamente os metadados da música atual ("Now Playing").
 * - Usa a API do iTunes para tentar encontrar e exibir a capa do álbum da música.
 * - Garante que apenas uma instância do player toque por vez na página.
 */

// IIFE (Immediately Invoked Function Expression) para encapsular o código e não poluir o escopo global.
(function () {
    'use strict'; // Habilita o modo estrito do JavaScript para um código mais seguro.

    // --- Variáveis Globais do Player ---
    // Estas arrays rastreiam todas as instâncias do player na página.
    // Isso é usado para garantir que apenas um player possa tocar por vez.
    let audioInstances = []; // Armazena os elementos <audio> de cada player.
    let playerTargets = []; // Armazena os IDs dos elementos <div> de cada player.

    /**
     * Injeta dinamicamente o arquivo CSS necessário para estilizar o player na página.
     * Isso evita que o usuário precise adicionar a tag <link> manualmente no HTML.
     */
    const stylesheet = document.createElement('link');
    stylesheet.rel = 'stylesheet';
    stylesheet.href = 'https://radiowink.com/dist/freeV3.css'; // URL do CSS para o estilo do player.
    stylesheet.type = 'text/css';
    document.getElementsByTagName('head')[0].appendChild(stylesheet);

    /**
     * @class FreeYessPlayer
     * @description O construtor principal do player de áudio.
     * @param {object} userConfig - Um objeto de configuração fornecido pelo usuário.
     */
    function FreeYessPlayer(userConfig) {
        // Configurações padrão do player. Serão mescladas com as configurações do usuário.
        this.conf = {
            target: null, // O ID do elemento <div> onde o player será inserido (ex: '#meu-player').
            url: null, // A URL principal do servidor de streaming (ex: 'https://servidor.com:8000').
            platform: 'sc', // A plataforma do servidor: 'sc' (Shoutcast) ou 'ic' (Icecast).
            mountPoint: 'stream', // O ponto de montagem do stream (ex: 'stream' ou 'live').
            sid: 1, // O Stream ID, geralmente usado em Shoutcast.
            volume: 0.75, // Volume inicial (de 0.0 a 1.0).
            logo: 'https://www.yesstreaming.com/img/yesstreaming.png', // Logo padrão.
            artwork: true, // Se deve ou não buscar a capa do álbum.
            artistc: 'f8bab0', // Cor do texto do artista (hex).
            songtitlec: 'ffffff', // Cor do texto do título da música (hex).
            buttonc: 'ffffff', // Cor dos botões (play/pause, volume) (hex).
            autoplay: false, // Se o player deve começar a tocar automaticamente.
            yesLogo: 'https://www.yesstreaming.com', // URL do link do logo.
            src: '' // Opcional: URL direta do stream.
        };

        // Nomes das classes CSS usadas para construir o player.
        this.cls = {
            wrapper: 'freeYess-wrapper',
            player: 'freeYess-player',
            artwork: 'freeYess-artwork',
            metadata: 'freeYess-metadata',
            controls: 'freeYess-controls',
            artistInfo: 'freeYess-artistInfo',
            songtitle: 'freeYess-title',
            artistName: 'freeYess-artist',
            albumName: 'freeYess-albumName',
            ppBtn: 'freeYess-ppBtn material-icons playBtn', // Play/Pause Button
            volSlider: 'freeYess-volSlider',
            volSliderBar: 'freeYess-volSliderBar',
            volIcon: 'freeYess-volIcon material-icons freeYess-vol3',
            yesLink: 'freeYess-yesLink',
            yesImg: 'freeYess-yesImg'
        };

        // Mescla as configurações do usuário com as configurações padrão.
        // Se o usuário forneceu uma configuração, ela substitui a padrão.
        for (let key in this.conf) {
            if (userConfig.hasOwnProperty(key)) {
                this.conf[key] = userConfig[key];
            }
        }

        // Inicia a construção e configuração do player.
        this.init();
    }

    // Adiciona todos os métodos ao protótipo do nosso construtor.
    // Isso é mais eficiente em termos de memória do que definir os métodos dentro do construtor.
    FreeYessPlayer.prototype = {
        /**
         * Inicializa o player: cria os elementos HTML, define os eventos e inicia os loops.
         */
        init: function () {
            // Encontra o elemento <div> alvo no HTML.
            if (typeof this.conf.target === 'object') {
                this.input = this.conf.target;
            } else {
                this.input = document.getElementById(this.conf.target.replace('#', ''));
            }

            // Se o elemento alvo não for encontrado, exibe um aviso e para a execução.
            if (!this.input) {
                return console.warn('Cannot find target element...');
            }

            // Constrói a estrutura HTML do player.
            this.createPlayer();

            // Determina a URL do stream e a URL dos metadados com base na plataforma.
            let streamUrl, metadataUrl;
            if (this.conf.platform === 'sc') { // Shoutcast
                streamUrl = `${this.conf.url}/${this.conf.mountPoint}`;
                metadataUrl = `${this.conf.url}/stats?sid=${this.conf.sid}&json=1`;
            } else if (this.conf.platform === 'ic') { // Icecast
                streamUrl = `${this.conf.url}/${this.conf.mountPoint}`;
                metadataUrl = `${this.conf.url}/status-json.xsl`;
            }

            // Cria o elemento <audio> que irá tocar a rádio.
            this.audio = document.createElement('audio');
            this.audio.src = streamUrl;
            this.audio.crossOrigin = 'anonymous'; // Necessário para evitar problemas de CORS com áudio.
            this.audio.load(); // Pré-carrega o áudio.
            this.audio.volume = this.conf.volume; // Define o volume inicial.

            // Adiciona a instância de áudio e o alvo às listas globais.
            audioInstances.push(this.audio);
            playerTargets.push(this.conf.target.replace('#', ''));

            // --- Define os Eventos ---
            // Evento de clique no botão de Play/Pause.
            this.ppBtn.onclick = () => {
                this.play(streamUrl);
            };

            // Evento de clique no ícone de volume (para mutar/desmutar).
            this.volIcon.onclick = () => {
                this.volumeIcon();
            };

            // Evento de deslizar o controle de volume.
            this.volSlider.addEventListener('input', () => {
                this.setVolume();
            }, false);

            // --- Inicia os Loops de Atualização ---
            // Configura uma tarefa recorrente para buscar os metadados da música.
            if (this.conf.platform === 'sc') {
                this.getSC(metadataUrl); // Faz a primeira chamada imediatamente.
                setInterval(() => {
                    this.getSC(metadataUrl);
                }, 8000); // Repete a cada 8 segundos.
            } else if (this.conf.platform === 'ic') {
                this.getIC(metadataUrl); // Faz a primeira chamada imediatamente.
                setInterval(() => {
                    this.getIC(metadataUrl);
                }, 8000); // Repete a cada 8 segundos.
            }

            // Aplica as cores customizadas definidas pelo usuário.
            if (this.conf.artistc.length > 0) {
                this.artistName.style.color = '#' + this.conf.artistc;
            }
            if (this.conf.songtitlec.length > 0) {
                this.songtitle.style.color = '#' + this.conf.songtitlec;
            }
            if (this.conf.buttonc.length > 0) {
                this.ppBtn.style.color = '#' + this.conf.buttonc;
                this.volIcon.style.color = '#' + this.conf.buttonc;
                // Adiciona uma regra de estilo para a "bolinha" do slider de volume.
                const styleElement = document.createElement('style');
                styleElement.type = 'text/css';
                const thumbStyle = `.freeYess-controls input[type=range]::-webkit-slider-thumb {background-color: #${this.conf.buttonc}}`;
                styleElement.appendChild(document.createTextNode(thumbStyle));
                document.getElementsByTagName('head')[0].appendChild(styleElement);
            }

            return this;
        },

        /**
         * Constrói todos os elementos DOM (divs, spans, etc.) que compõem o player.
         */
        createPlayer: function () {
            // Usa uma função auxiliar para criar os elementos de forma mais limpa.
            const cls = this.cls;
            this.wrapper = createElementHelper('div', cls.wrapper);
            this.player = createElementHelper('div', cls.player);
            this.artwork = createElementHelper('div', cls.artwork);
            this.metadata = createElementHelper('div', cls.metadata);
            this.songtitle = createElementHelper('div', cls.songtitle);
            this.artistInfo = createElementHelper('div', cls.artistInfo);
            this.artistName = createElementHelper('span', cls.artistName);
            this.albumName = createElementHelper('span', cls.albumName);
            this.ppBtn = createElementHelper('div', cls.ppBtn, ['id', 'ppBtn']);
            this.controls = createElementHelper('div', cls.controls);
            this.volSlider = createElementHelper('input', cls.volSlider);
            this.volSliderBar = createElementHelper('div', cls.volSliderBar);
            this.volIcon = createElementHelper('div', cls.volIcon);
            this.yesLink = createElementHelper('a', cls.yesLink);
            this.yesImg = createElementHelper('img', cls.yesImg);

            // Monta a estrutura do player aninhando os elementos.
            this.wrapper.appendChild(this.player);
            this.player.appendChild(this.artwork);
            this.player.appendChild(this.metadata);
            this.metadata.appendChild(this.songtitle);
            this.metadata.appendChild(this.artistInfo);
            this.artistInfo.appendChild(this.artistName);
            this.artistInfo.appendChild(this.albumName);
            this.player.appendChild(this.controls);
            this.controls.appendChild(this.volIcon);
            this.controls.appendChild(this.volSlider);
            this.controls.appendChild(this.ppBtn);
            this.controls.appendChild(this.yesLink);
            this.yesLink.appendChild(this.yesImg);

            // Configura atributos específicos dos elementos.
            this.yesLink.setAttribute('href', this.conf.yesLogo);
            this.yesLink.setAttribute('target', '_blank');
            this.yesImg.setAttribute('src', this.conf.logo);

            this.volSlider.type = 'range';
            this.volSlider.min = 0;
            this.volSlider.max = 100;
            this.volSlider.step = 1;
            this.volSlider.value = this.conf.volume * 100;
            this.volSlider.autocomplete = 'off';

            // Insere o player montado no elemento <div> alvo da página.
            this.input.appendChild(this.wrapper, this.input.nextSibling);
        },

        /**
         * Controla a lógica de tocar e pausar o áudio.
         * @param {string} streamUrl - A URL do stream de áudio.
         */
        play: function (streamUrl) {
            // Verifica se o player já está tocando (pela classe CSS 'playing').
            if (this.ppBtn.classList.contains('playing')) {
                // Se está tocando, pausa.
                this.ppBtn.classList.remove('playing');
                this.ppBtn.classList.toggle('playBtn'); // Mostra o ícone de play
                this.ppBtn.classList.toggle('pauseBtn'); // Esconde o ícone de pause
                this.audio.src = this.conf.src; // Limpa o src para parar o buffer
            } else {
                // Se não está tocando, inicia a reprodução.

                // **LÓGICA IMPORTANTE**: Pausa todos os outros players na página.
                // Itera sobre a lista de todas as instâncias de áudio.
                for (let i = 0; i < audioInstances.length; i++) {
                    audioInstances[i].src = this.conf.src; // Para o buffer de todos.
                }

                // Itera sobre todos os players para resetar a aparência dos botões.
                for (let i = 0; i < playerTargets.length; i++) {
                    const targetElement = document.getElementById(playerTargets[i]);
                    const playButton = targetElement.querySelector('.freeYess-ppBtn');
                    playButton.classList.remove('playing');
                    playButton.classList.remove('pauseBtn');
                    playButton.classList.add('playBtn');
                }

                // Agora, configura este player para tocar.
                this.ppBtn.classList.toggle('playing');
                this.ppBtn.classList.remove('playBtn'); // Esconde o ícone de play
                this.ppBtn.classList.add('pauseBtn'); // Mostra o ícone de pause
                this.audio.src = streamUrl; // Define a URL do stream
                this.audio.play(); // Toca o áudio
            }
        },

        /**
         * Ajusta o volume do áudio com base na posição do slider.
         */
        setVolume: function () {
            const newVolume = this.volSlider.value;
            const icon = this.volIcon;

            // Muda o ícone de volume com base no nível.
            if (newVolume < 55 && newVolume > 0) {
                icon.classList.add('freeYess-vol2');
                icon.classList.remove('freeYess-vol1', 'freeYess-vol3');
            } else if (newVolume == 0) {
                icon.classList.add('freeYess-vol1'); // Ícone de mudo
                icon.classList.remove('freeYess-vol2', 'freeYess-vol3');
            } else if (newVolume > 55) {
                icon.classList.add('freeYess-vol3'); // Ícone de volume alto
                icon.classList.remove('freeYess-vol1', 'freeYess-vol2');
            }

            // Define o volume no elemento <audio> (valor entre 0.0 e 1.0).
            this.audio.volume = newVolume / 100;
        },

        /**
         * Alterna entre mudo e não-mudo quando o ícone de volume é clicado.
         */
        volumeIcon: function () {
            const currentVolume = this.volSlider.value;
            const icon = this.volIcon;

            if (icon.classList.contains('freeYess-vol1')) { // Se está mudo
                // Reativa o som e restaura o ícone anterior.
                icon.classList.remove('freeYess-vol1');
                icon.classList.add('freeYess-vol2'); // Ou vol3, dependendo do volume
                this.audio.volume = currentVolume / 100;
            } else { // Se não está mudo
                // Muta o som.
                icon.classList.remove('freeYess-vol2', 'freeYess-vol3');
                icon.classList.add('freeYess-vol1');
                this.audio.volume = 0;
            }
        },

        /**
         * Busca e processa metadados de um servidor Shoutcast.
         * @param {string} url - A URL da API JSON do Shoutcast.
         */
        getSC: function (url) {
            // Usa uma requisição JSONP para buscar os dados.
            jsonpRequest(url, (data) => {
                const currentSongTitle = data.songtitle;
                // Se o título da música mudou, atualiza a interface.
                if (currentSongTitle != this.getNP()) {
                    this.setMeta(data);
                }
            });
        },

        /**
         * Busca e processa metadados de um servidor Icecast.
         * @param {string} url - A URL da API JSON do Icecast.
         */
        getIC: function (url) {
            // Usa uma requisição AJAX padrão.
            ajaxRequest(url, (data) => {
                // Acha o stream correto nos dados retornados pelo Icecast.
                data = this.findMP(data);
                const currentSongTitle = data.title || data.song;
                // Se o título da música mudou, atualiza a interface.
                if (currentSongTitle != this.getNP()) {
                    this.setMeta(data);
                }
            });
        },

        /**
         * Atualiza a interface do player com os novos metadados da música.
         * @param {object} metadata - O objeto com os dados da música (título, artista).
         */
        setMeta: function (metadata) {
            const fullTitle = metadata.songtitle || metadata.title || metadata.song;
            const parts = splitTitle(fullTitle);
            const artist = parts[0] || 'Unknown';
            const song = parts[1] || 'Undefined';

            this.setNP(fullTitle); // Armazena o título completo para comparação futura.
            this.artistName.innerHTML = artist;

            // Adiciona e remove uma classe para uma animação suave ao atualizar.
            this.artistInfo.classList.add('blink-1');
            setTimeout(() => {
                this.artistInfo.classList.remove('blink-1');
            }, 3000);

            this.songtitle.innerHTML = song;
            this.songtitle.classList.add('blink-1');
            setTimeout(() => {
                this.songtitle.classList.remove('blink-1');
            }, 3000);

            // Tenta buscar a capa do álbum se a opção estiver ativa.
            if (this.conf.artwork) {
                this.getAlbumInfo(artist, song);
            }
        },

        /**
         * Busca informações do álbum (incluindo a capa) na API do iTunes.
         * @param {string} artist - O nome do artista.
         * @param {string} song - O nome da música.
         */
        getAlbumInfo: function (artist, song) {
            // Limpa os nomes para melhorar a chance de encontrar na busca.
            const cleanArtist = cleanTrackString(artist);
            const cleanSong = cleanTrackString(song);

            // Monta a URL da API do iTunes.
            const itunesUrl = `https://itunes.apple.com/search?term=${cleanArtist}-${cleanSong}&media=music&limit=1`;

            // Faz a requisição JSONP para o iTunes.
            jsonpRequest(itunesUrl, (data) => {
                this.setAlbumInfo(data);
            });
        },

        /**
         * Define a capa do álbum e o nome do álbum na interface.
         * @param {object} data - Os dados retornados pela API do iTunes.
         */
        setAlbumInfo: function (data) {
            let artworkUrl, albumName, linkUrl;

            // Verifica se a API retornou algum resultado.
            if (data.results.length == 1) {
                artworkUrl = data.results[0].artworkUrl100;
                // Pede uma imagem de maior resolução.
                artworkUrl = artworkUrl.replace('100x100', '200x200');
                albumName = data.results[0].collectionName;
                linkUrl = data.results[0].collectionViewUrl;
            } else {
                // Se não encontrou, usa imagens e textos padrão.
                artworkUrl = 'https://www.yesstreaming.com/img/default.png';
                albumName = 'Unknown';
                linkUrl = 'https://music.apple.com/us/album/unknown';
            }

            // Define a capa do álbum como imagem de fundo da div 'artwork'.
            this.artwork.style.backgroundImage = `url("${artworkUrl}")`;
            this.albumName.innerHTML = ` - ${albumName}`;

            // Adiciona uma animação para a transição da capa.
            this.artwork.classList.add('rotate-in-center');
            setTimeout(() => {
                this.artwork.classList.remove('rotate-in-center');
            }, 1500);
        },

        /**
         * Encontra o ponto de montagem (mount point) correto nos dados do Icecast.
         */
        findMP: function (data) {
            if (data.icestats.source.length === undefined) {
                return data.icestats.source;
            } else {
                for (let i = 0; i < data.icestats.source.length; i++) {
                    const source = data.icestats.source[i].listenurl;
                    if (source.includes(this.conf.mountPoint)) {
                        return data.icestats.source[i];
                    }
                }
            }
        },

        /**
         * Obtém o título da música que está atualmente armazenado.
         * NP = Now Playing
         */
        getNP: function () {
            return this.wrapper.getAttribute('data-np');
        },

        /**
         * Armazena o título da música atual em um atributo de dados.
         * Isso é usado para comparar e ver se a música mudou.
         */
        setNP: function (title) {
            this.wrapper.setAttribute('data-np', title);
        }
    };

    // --- Funções Auxiliares (Helpers) ---

    /**
     * Função auxiliar para criar elementos DOM de forma mais limpa.
     * @param {string} tag - A tag HTML a ser criada (ex: 'div').
     * @param {string} className - A classe CSS a ser aplicada.
     * @param {array} [attr] - Um array opcional para definir um atributo [chave, valor].
     * @returns {HTMLElement} O elemento criado.
     */
    function createElementHelper(tag, className, attr) {
        const element = document.createElement(tag);
        if (className) element.className = className;
        if (attr && attr.length === 2) {
            element.setAttribute(attr[0], attr[1]);
        }
        return element;
    }

    /**
     * Separa uma string no formato "Artista - Música" em um array [Artista, Música].
     */
    function splitTitle(fullTitle) {
        return fullTitle.split(' - ');
    }

    /**
     * Limpa uma string de música ou artista, removendo informações extras
     * que podem atrapalhar a busca na API (ex: "ft.", "&", "[remix]").
     */
    function cleanTrackString(str) {
        str = str.toLowerCase().trim();
        if (str.includes('&')) str = str.substring(0, str.indexOf(' &'));
        else if (str.includes(' feat')) str = str.substring(0, str.indexOf(' feat'));
        else if (str.includes(' ft.')) str = str.substring(0, str.indexOf(' ft.'));
        else if (str.includes('[')) str = str.substring(0, str.indexOf(' ['));
        return str;
    }

    /**
     * Realiza uma requisição JSONP, uma técnica para buscar dados de um domínio
     * diferente sem ser bloqueado pela política de mesma origem (CORS).
     */
    function jsonpRequest(url, callback) {
        // Cria um nome de função de callback único e aleatório.
        const callbackName = 'jsonp_callback_' + Math.round(100000 * Math.random());

        // Cria essa função no escopo global para que o script retornado possa chamá-la.
        window[callbackName] = function (data) {
            delete window[callbackName]; // Limpa a função global.
            document.body.removeChild(script); // Remove a tag <script> da página.
            callback(data); // Executa o callback original com os dados.
        };

        // Adiciona a tag <script> na página, o que fará o navegador requisitar o URL.
        const script = document.createElement('script');
        script.src = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'callback=' + callbackName;
        document.body.appendChild(script);
    }

    /**
     * Realiza uma requisição AJAX padrão.
     */
    function ajaxRequest(url, callback) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState !== 4) return; // Espera a requisição terminar.
            if (xhr.status === 200) {
                // Se a requisição foi bem-sucedida, chama o callback com os dados.
                callback(JSON.parse(xhr.responseText));
            } else {
                console.warn('request_error');
            }
        };
        xhr.crossOrigin = 'anonymous';
        xhr.open('GET', url, true);
        xhr.send();
    }

    // --- Exposição Global ---
    // Torna o construtor do player acessível globalmente através de `window.freeYess`.
    // Isso permite que o usuário crie uma nova instância com `new freeYess({...});` em seu próprio script.
    window.freeYess = FreeYessPlayer;

}()); // Fim da IIFE