const RADIO_NAME = "RITUAL FM";
const URL_STREAMING = "https://radio.saopaulo01.com.br:10996";
const HISTORIC = true;
let lastSongKey = "";
let lastCoverUrl = "";
let previousVolumeBeforeMute = null;
let introAudioPlayed = false;
const INTRO_AUDIO_URL = "";
let isPlaying = false;

window.shouldShowLoaderUntilCoverLoads = true;

window.showLoader = function () {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'flex';
        loader.classList.remove('fade-out');
    }
}

// window.hideLoader = function () {
//     const loader = document.getElementById('loader');
//     const siteWrapper = document.getElementById('site-wrapper');

//     if (loader && !loader.classList.contains('fade-out')) {
//         loader.classList.add('fade-out');

//         if (siteWrapper) {
//             siteWrapper.classList.remove('content-hidden');
//             siteWrapper.classList.add('content-visible');

//             // --- LÓGICA PARA ATIVAR A ANIMAÇÃO DA CAPA ---
//             // const coverSite = document.getElementById('cover-site');
//             // if (coverSite) {
//             //     // Remove a classe que a mantinha "fechada"
//             //     coverSite.classList.remove('is-hidden-for-reveal');
//             //     // Adiciona as classes do animate.css para iniciar a animação
//             //     coverSite.classList.add('animated', 'clipIn');
//             // }

//             const coverSite = document.getElementById('cover-site');
//             if (coverSite) {
//                 // Apenas remove a classe inicial. O CSS cuidará da transição.
//                 coverSite.classList.remove('cover-start-closed');
//             }
//         }

//         setTimeout(() => {
//             if (loader) loader.style.display = 'none';
//         }, 500);
//     }
//     window.shouldShowLoaderUntilCoverLoads = false;
// }


window.hideLoader = function () {
    const loader = document.getElementById('loader');
    const siteWrapper = document.getElementById('site-wrapper');

    if (loader && !loader.classList.contains('fade-out')) {
        loader.classList.add('fade-out');

        if (siteWrapper) {
            siteWrapper.classList.remove('content-hidden');
            siteWrapper.classList.add('content-visible');

            // ANIMAÇÃO DE ENTRADA NA HOME
            const coverSite = document.getElementById('cover-site');
            if (coverSite) {
                // Ao remover a classe, o CSS fará a animação de "abertura"
                coverSite.classList.remove('is-closed');
            }
        }

        setTimeout(() => {
            if (loader) loader.style.display = 'none';
        }, 500);
    }
    window.shouldShowLoaderUntilCoverLoads = false;
}

window.onload = function () {
    const minimumTimePromise = new Promise(resolve => setTimeout(resolve, 1500));
    const coverLoadedPromise = new Promise(resolve => {
        window.onInitialCoverLoaded = resolve;
    });

    getStreamingData();
    setInterval(getStreamingData, 4000);

    Promise.all([minimumTimePromise, coverLoadedPromise]).then(() => {
        window.hideLoader();
    });

    var page = new Page();
    page.changeTitlePage();
    page.setVolume();

    var coverArt = document.getElementsByClassName('cover-album')[0];
    if (coverArt) {
        coverArt.style.height = coverArt.offsetWidth + 'px';
    }
};

function Page() {
    this.changeTitlePage = function (title = RADIO_NAME) {
        document.title = title;
    };

    this.refreshCurrentSong = function (song, artist) {
        const mainPlayerSongEl = document.getElementById('currentSong');
        const mainPlayerArtistEl = document.getElementById('currentArtist');
        const miniPlayerTextEl = document.getElementById('lyricsSong');

        if (mainPlayerSongEl && mainPlayerArtistEl) {
            if (song !== mainPlayerSongEl.innerHTML) {
                mainPlayerSongEl.className = 'animated songs_txt';
                mainPlayerSongEl.innerHTML = song;
                mainPlayerArtistEl.className = 'animated songs_txt';
                mainPlayerArtistEl.innerHTML = artist;

                setTimeout(() => {
                    mainPlayerSongEl.className = 'animated songs_txt';
                    mainPlayerArtistEl.className = 'animated songs_txt';
                }, 4000);
            }
        }

        if (miniPlayerTextEl) {
            const newText = `${artist} - ${song}`;
            if (miniPlayerTextEl.innerHTML !== newText) {
                miniPlayerTextEl.innerHTML = newText;
            }
        }
    };

    this.refreshHistoric = function (info, n) {
        var $historicDiv = document.querySelectorAll('#historicSong');
        if ($historicDiv[n]) {
            var $songName = document.querySelectorAll('#historicSong .music-info .song');
            var $artistName = document.querySelectorAll('#historicSong .music-info .artist');
            var urlCoverArt = '/ritualfm/public/images/images/fallback1.jpg';
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var data = JSON.parse(this.responseText);
                    var artworkUrl = (data.resultCount) ? data.results[0].artworkUrl100 : urlCoverArt;
                    var coverHistoric = document.querySelectorAll('#historicSong .cover-historic');
                    if (coverHistoric[n]) {
                        coverHistoric[n].style.backgroundImage = 'url(' + artworkUrl + ')';
                    }
                }
                if ($songName[n] && $artistName[n]) {
                    $songName[n].innerHTML = info.song.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
                    $artistName[n].innerHTML = info.artist.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
                }
                $historicDiv[n].classList.add('animated', 'songs_txt');
            };
            xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(info.artist)} ${encodeURIComponent(info.song)}&media=music&limit=1`, true);
            xhttp.send();

            setTimeout(() => {
                for (let j = 0; j < $historicDiv.length; j++) {
                    if ($historicDiv[j]) {
                        $historicDiv[j].classList.remove('animated');
                    }
                }
            }, 4000);
        }
    };

    // this.refreshCover = function (song = '', artist, forceUpdate = false) {
    //     const coverBackground = document.getElementById('cover-site');
    //     const coverArt = document.getElementById('currentCoverArt');
    //     const defaultCovers = ["/ritualfm/public/images/fallback1.jpg", "/ritualfm/public/images/fallback2.jpg", "/ritualfm/public/images/fallback3.jpg", "/ritualfm/public/images/fallback4.jpg", "/ritualfm/public/images/fallback5.jpg", "/ritualfm/public/images/fallback6.jpg"];
    //     const searchQueries = [`${artist} ${song}`, `${artist} ${song.split('(')[0].split('-')[0]}`.trim(), `${artist}`];
    //     let attempt = 0;
    //     const specialCovers = [{ keyword: "RITUAL FM", image: "/ritualfm/public/images/aovivo.jpg" }, { keyword: "CLUBLIVE", image: "/ritualfm/public/images/clublive.jpg" }, { keyword: "CLUB SESSION MIX", image: "/ritualfm/public/images/aovivo.jpg" }];

    //     function applyCover(url) {
    //         if (lastCoverUrl === url && !forceUpdate) {
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //             return;
    //         };
    //         lastCoverUrl = url;
    //         const img = new Image();
    //         img.onload = () => {
    //             if (coverBackground) {
    //                 coverBackground.style.backgroundImage = `url(${url})`;
    //             }
    //             if (coverArt) {
    //                 currentCoverArt.style.transition = 'opacity 0.3s ease-in';
    //                 currentCoverArt.style.opacity = 0;
    //                 setTimeout(() => {
    //                     currentCoverArt.style.backgroundImage = `url(${url})`;
    //                     currentCoverArt.style.opacity = 1;
    //                 }, 300);
    //             }
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //         };
    //         img.onerror = () => {
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //             console.warn("Falha ao carregar a imagem da capa:", url);
    //         };
    //         img.src = url;
    //         updateMediaSession(song, artist, url);
    //     }

    //     if (!coverBackground) {
    //          if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //          if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //     }

    //     const match = specialCovers.find(item => `${artist} ${song}`.toLowerCase().includes(item.keyword.toLowerCase()));
    //     if (match) {
    //         applyCover(match.image);
    //         return;
    //     }

    //     function tryFetchCover() {
    //         if (attempt >= searchQueries.length) {
    //             const fallback = defaultCovers[Math.floor(Math.random() * defaultCovers.length)];
    //             applyCover(fallback);
    //             return;
    //         }
    //         const query = searchQueries[attempt++];
    //         const xhttp = new XMLHttpRequest();
    //         xhttp.onreadystatechange = function () {
    //             if (this.readyState === 4) {
    //                 if (this.status === 200) {
    //                     const data = JSON.parse(this.responseText);
    //                     if (data.resultCount > 0) {
    //                         let artworkUrl = data.results[0].artworkUrl100.replace('100x100bb', '1600x1600bb');
    //                         applyCover(artworkUrl);
    //                     } else {
    //                         tryFetchCover();
    //                     }
    //                 } else {
    //                     tryFetchCover();
    //                 }
    //             }
    //         };
    //         xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
    //         xhttp.send();
    //     }
    //     tryFetchCover();
    //     function updateMediaSession(song, artist, imageUrl) {
    //         if ('mediaSession' in navigator) {
    //             navigator.mediaSession.metadata = new MediaMetadata({
    //                 title: `${artist} - ${song}`,
    //                 artist: '',
    //                 artwork: [{ src: imageUrl, sizes: '1600x1600', type: 'image/png' }]
    //             });
    //         }
    //     }
    // };


    // mais uma
    // this.refreshCover = function (song = '', artist, forceUpdate = false) {
    //     const coverBackground = document.getElementById('cover-site');
    //     const coverArtOld = document.getElementById('currentCoverArt');
    //     const coverArtNew = document.getElementById('coverArtTransitionLayer');

    //     const defaultCovers = ["/ritualfm/public/images/fallback1.jpg", "/ritualfm/public/images/fallback2.jpg", "/ritualfm/public/images/fallback3.jpg", "/ritualfm/public/images/fallback4.jpg", "/ritualfm/public/images/fallback5.jpg", "/ritualfm/public/images/fallback6.jpg"];
    //     const searchQueries = [`${artist} ${song}`, `${artist} ${song.split('(')[0].split('-')[0]}`.trim(), `${artist}`];
    //     let attempt = 0;
    //     const specialCovers = [{
    //         keyword: "RITUAL FM",
    //         image: "/ritualfm/public/images/aovivo.jpg"
    //     }, {
    //         keyword: "CLUBLIVE",
    //         image: "/ritualfm/public/images/clublive.jpg"
    //     }, {
    //         keyword: "CLUB SESSION MIX",
    //         image: "/ritualfm/public/images/aovivo.jpg"
    //     }];

    //     function applyCover(url) {
    //         if (lastCoverUrl === url && !forceUpdate) {
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //             return;
    //         }
    //         lastCoverUrl = url;

    //         if (!coverArtOld || !coverArtNew) {
    //             console.error("Elementos da capa do álbum não encontrados.");
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //             return;
    //         }

    //         const img = new Image();
    //         img.onload = () => {
    //             // 1. Define a nova imagem na camada de transição
    //             coverArtNew.style.backgroundImage = `url(${url})`;

    //             // 2. Define a propriedade de transição e inicia a animação
    //             coverArtNew.style.transition = 'clip-path 0.8s cubic-bezier(0.7, 0, 0.3, 1)';
    //             coverArtNew.style.clipPath = 'circle(100% at 50% 50%)';

    //             // 3. Atualiza o fundo da página principal (se existir)
    //             if (coverBackground) {
    //                 coverBackground.style.backgroundImage = `url(${url})`;
    //             }

    //             // 4. Cria um listener para o final da transição para limpar e resetar
    //             const onTransitionEnd = () => {
    //                 // A. A nova capa se torna a capa base (na camada de baixo)
    //                 coverArtOld.style.backgroundImage = `url(${url})`;

    //                 // B. Reseta a camada de transição para a próxima música (sem animar)
    //                 coverArtNew.style.transition = 'none';
    //                 coverArtNew.style.clipPath = 'circle(0% at 50% 50%)';

    //                 // C. Remove o listener para não ser chamado novamente
    //                 coverArtNew.removeEventListener('transitionend', onTransitionEnd);
    //             };

    //             // Adiciona o listener de forma segura, garantindo que ele só rode uma vez
    //             coverArtNew.addEventListener('transitionend', onTransitionEnd, { once: true });

    //             // Sinaliza para o loader que a capa carregou
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //         };
    //         img.onerror = () => {
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //             console.warn("Falha ao carregar a imagem da capa:", url);
    //         };
    //         img.src = url;
    //         updateMediaSession(song, artist, url);
    //     }

    //     if (!coverBackground && !coverArtOld) {
    //         if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //         if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //     }

    //     function updateMediaSession(song, artist, imageUrl) {
    //         if ('mediaSession' in navigator) {
    //             navigator.mediaSession.metadata = new MediaMetadata({
    //                 title: `${artist} - ${song}`,
    //                 artist: '',
    //                 artwork: [{ src: imageUrl, sizes: '1600x1600', type: 'image/png' }]
    //             });
    //         }
    //     }

    //     const match = specialCovers.find(item => `${artist} ${song}`.toLowerCase().includes(item.keyword.toLowerCase()));
    //     if (match) {
    //         applyCover(match.image);
    //         return;
    //     }

    //     function tryFetchCover() {
    //         if (attempt >= searchQueries.length) {
    //             const fallback = defaultCovers[Math.floor(Math.random() * defaultCovers.length)];
    //             applyCover(fallback);
    //             return;
    //         }
    //         const query = searchQueries[attempt++];
    //         const xhttp = new XMLHttpRequest();
    //         xhttp.onreadystatechange = function () {
    //             if (this.readyState === 4) {
    //                 if (this.status === 200) {
    //                     const data = JSON.parse(this.responseText);
    //                     if (data.resultCount > 0) {
    //                         let artworkUrl = data.results[0].artworkUrl100.replace('100x100bb', '1600x1600bb');
    //                         applyCover(artworkUrl);
    //                     } else {
    //                         tryFetchCover();
    //                     }
    //                 } else {
    //                     tryFetchCover();
    //                 }
    //             }
    //         };
    //         xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
    //         xhttp.send();
    //     }
    //     tryFetchCover();
    // };


    // mais outra
    // this.refreshCover = function (song = '', artist, forceUpdate = false) {
    //     const coverBackground = document.getElementById('cover-site');
    //     const coverArtOld = document.getElementById('currentCoverArt');
    //     const coverArtNew = document.getElementById('coverArtTransitionLayer');

    //     const defaultCovers = ["/ritualfm/public/images/fallback1.jpg", "/ritualfm/public/images/fallback2.jpg", "/ritualfm/public/images/fallback3.jpg", "/ritualfm/public/images/fallback4.jpg", "/ritualfm/public/images/fallback5.jpg", "/ritualfm/public/images/fallback6.jpg"];
    //     const searchQueries = [`${artist} ${song}`, `${artist} ${song.split('(')[0].split('-')[0]}`.trim(), `${artist}`];
    //     let attempt = 0;
    //     const specialCovers = [{
    //         keyword: "RITUAL FM",
    //         image: "/ritualfm/public/images/aovivo.jpg"
    //     }, {
    //         keyword: "CLUBLIVE",
    //         image: "/ritualfm/public/images/clublive.jpg"
    //     }, {
    //         keyword: "CLUB SESSION MIX",
    //         image: "/ritualfm/public/images/aovivo.jpg"
    //     }];

    //     function applyCover(url) {
    //         if (lastCoverUrl === url && !forceUpdate) {
    //             if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //             if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //             return;
    //         }
    //         lastCoverUrl = url;

    //         // Animação para o mini player (camadas)
    //         if (coverArtOld && coverArtNew) {
    //             const img = new Image();
    //             img.onload = () => {
    //                 coverArtNew.style.backgroundImage = `url(${url})`;
    //                 coverArtNew.style.transition = 'clip-path 0.8s cubic-bezier(0.7, 0, 0.3, 1)';
    //                 coverArtNew.style.clipPath = 'circle(100% at 50% 50%)';

    //                 const onTransitionEnd = () => {
    //                     coverArtOld.style.backgroundImage = `url(${url})`;
    //                     coverArtNew.style.transition = 'none';
    //                     coverArtNew.style.clipPath = 'circle(0% at 50% 50%)';
    //                     coverArtNew.removeEventListener('transitionend', onTransitionEnd);
    //                 };
    //                 coverArtNew.addEventListener('transitionend', onTransitionEnd, { once: true });
    //             };
    //             img.src = url;
    //         }

    //         // --- NOVO: Animação para a capa de fundo da Home ---
    //         if (coverBackground && coverBackground.style.display !== 'none') {
    //             // Primeiro, encolhe o círculo para o centro
    //             coverBackground.style.clipPath = 'circle(0% at 50% 50%)';

    //             // Aguarda o fim da animação de "fechamento" para trocar a imagem e "abrir"
    //             setTimeout(() => {
    //                 coverBackground.style.backgroundImage = `url(${url})`;
    //                 // Expande o círculo de volta para a sua forma original
    //                 coverBackground.style.clipPath = 'circle(60% at 45% 50%)';
    //             }, 500); // 500ms é o valor de --transition-slow
    //         } else if (coverBackground) {
    //             // Se não estiver visível, apenas atualiza a imagem sem animar
    //             coverBackground.style.backgroundImage = `url(${url})`;
    //         }
    //         // --- FIM DO NOVO BLOCO ---


    //         // Lógica de loader e Media Session (já existente)
    //         if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //         if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }

    //         updateMediaSession(song, artist, url);
    //     }

    //     if (!coverBackground && !coverArtOld) {
    //         if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
    //         if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
    //     }

    //     function updateMediaSession(song, artist, imageUrl) {
    //         if ('mediaSession' in navigator) {
    //             navigator.mediaSession.metadata = new MediaMetadata({
    //                 title: `${artist} - ${song}`,
    //                 artist: '',
    //                 artwork: [{ src: imageUrl, sizes: '1600x1600', type: 'image/png' }]
    //             });
    //         }
    //     }

    //     const match = specialCovers.find(item => `${artist} ${song}`.toLowerCase().includes(item.keyword.toLowerCase()));
    //     if (match) {
    //         applyCover(match.image);
    //         return;
    //     }

    //     function tryFetchCover() {
    //         if (attempt >= searchQueries.length) {
    //             const fallback = defaultCovers[Math.floor(Math.random() * defaultCovers.length)];
    //             applyCover(fallback);
    //             return;
    //         }
    //         const query = searchQueries[attempt++];
    //         const xhttp = new XMLHttpRequest();
    //         xhttp.onreadystatechange = function () {
    //             if (this.readyState === 4) {
    //                 if (this.status === 200) {
    //                     const data = JSON.parse(this.responseText);
    //                     if (data.resultCount > 0) {
    //                         let artworkUrl = data.results[0].artworkUrl100.replace('100x100bb', '1600x1600bb');
    //                         applyCover(artworkUrl);
    //                     } else {
    //                         tryFetchCover();
    //                     }
    //                 } else {
    //                     tryFetchCover();
    //                 }
    //             }
    //         };
    //         xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
    //         xhttp.send();
    //     }
    //     tryFetchCover();
    // };


    this.refreshCover = function(song = '', artist, forceUpdate = false) {
    const coverBackground = document.getElementById('cover-site');
    const coverArtOld = document.getElementById('currentCoverArt');
    const coverArtNew = document.getElementById('coverArtTransitionLayer');

    // Flag para evitar múltiplas animações ao mesmo tempo
    let isTransitioning = false;

    const defaultCovers = ["/ritualfm/public/images/fallback1.jpg", "/ritualfm/public/images/fallback2.jpg", "/ritualfm/public/images/fallback3.jpg", "/ritualfm/public/images/fallback4.jpg", "/ritualfm/public/images/fallback5.jpg", "/ritualfm/public/images/fallback6.jpg"];
    const searchQueries = [`${artist} ${song}`, `${artist} ${song.split('(')[0].split('-')[0]}`.trim(), `${artist}`];
    let attempt = 0;
    const specialCovers = [{ keyword: "RITUAL FM", image: "/ritualfm/public/images/aovivo.jpg" }, { keyword: "CLUBLIVE", image: "/ritualfm/public/images/clublive.jpg" }, { keyword: "CLUB SESSION MIX", image: "/ritualfm/public/images/aovivo.jpg" }];

    function applyCover(url) {
        // --- NOVO: GUARDA DE SEGURANÇA ---
        // Se os elementos do mini player não existirem, não faz nada para evitar erros.
        if (!coverArtOld || !coverArtNew) {
            // Apenas atualiza o fundo da home se ele existir
            if (coverBackground) {
                coverBackground.style.backgroundImage = `url(${url})`;
            }
            // Interrompe a execução para o mini player
            return;
        }

        if (lastCoverUrl === url && !forceUpdate) { return; }
        if (isTransitioning) { return; }
        
        lastCoverUrl = url;
        
        const img = new Image();
        img.src = url;

        img.onload = () => {
            // Atualiza o fundo da home de forma simples
            if (coverBackground) {
                coverBackground.style.backgroundImage = `url(${url})`;
            }

            // Animação de crossfade no mini player
            isTransitioning = true;

            coverArtNew.style.backgroundImage = `url(${url})`;
            coverArtNew.style.opacity = 1;

            setTimeout(() => {
                coverArtOld.style.backgroundImage = `url(${url})`;
                coverArtNew.style.opacity = 0;
                isTransitioning = false;
            }, 1200);
            
            if (window.onHomepageCoverLoaded) { window.onHomepageCoverLoaded(); delete window.onHomepageCoverLoaded; }
            if (window.onInitialCoverLoaded) { window.onInitialCoverLoaded(); delete window.onInitialCoverLoaded; }
        };
        
        updateMediaSession(song, artist, url);
    }
    
    function updateMediaSession(song, artist, imageUrl) {
        if ('mediaSession' in navigator) {
            navigator.mediaSession.metadata = new MediaMetadata({
                title: `${artist} - ${song}`, artist: 'RITUAL FM',
                artwork: [{ src: imageUrl, sizes: '512x512', type: 'image/jpeg' }]
            });
        }
    }

    const match = specialCovers.find(item => `${artist} ${song}`.toLowerCase().includes(item.keyword.toLowerCase()));
    if (match) { applyCover(match.image); return; }

    function tryFetchCover() {
        if (attempt >= searchQueries.length) { applyCover(defaultCovers[Math.floor(Math.random() * defaultCovers.length)]); return; }
        const query = searchQueries[attempt++];
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    const data = JSON.parse(this.responseText);
                    if (data.resultCount > 0) {
                        applyCover(data.results[0].artworkUrl100.replace('100x100bb', '1024x1024bb'));
                    } else { tryFetchCover(); }
                } else { tryFetchCover(); }
            }
        };
        xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
        xhttp.send();
    }
    tryFetchCover();
};



    

    this.changeVolumeIndicator = function (volume) {
        if (typeof Storage !== 'undefined') {
            localStorage.setItem('volume', volume);
        }
    };

    this.setVolume = function () {
        if (typeof Storage !== 'undefined') {
            var volume = parseInt(localStorage.getItem('volume')) || 20;
            var volumeSlider = document.getElementById('volume');
            if (volumeSlider) {
                volumeSlider.value = volume;
            }
            updateVolumeCircles(volume);
        }
    };
}

var audio = new Audio(URL_STREAMING + '/;');
var introAudio = new Audio(INTRO_AUDIO_URL);

function updatePlayerButtonState(playing) {
    const mainButton = document.getElementById('playerButton');
    const miniButton = document.getElementById('playerButton2');
    const mainClass = playing ? 'bt_stop' : 'bt_play';
    const miniClass = playing ? 'mini_stop' : 'mini_play';
    if (mainButton) mainButton.className = mainClass;
    if (miniButton) miniButton.className = miniClass;
}

function checkIntroAudioExists() {
    return new Promise((resolve) => {
        const tempAudio = new Audio(INTRO_AUDIO_URL);
        tempAudio.onloadeddata = () => resolve(true);
        tempAudio.onerror = () => resolve(false);
        setTimeout(() => resolve(false), 3000);
    });
}

function Player() {
    this.play = function () {
        isPlaying = true;
        updatePlayerButtonState(true);
        if (introAudioPlayed) {
            audio.load();
            audio.play().catch(err => {
                console.warn('Erro ao iniciar streaming:', err);
                isPlaying = false;
                updatePlayerButtonState(false);
            });
            fadeVolumeTo(intToDecimal(getSavedVolume()));
            return;
        }
        checkIntroAudioExists().then(exists => {
            if (exists) {
                introAudio.volume = intToDecimal(getSavedVolume());
                introAudio.onended = function () {
                    introAudioPlayed = true;
                    audio.load();
                    audio.play().catch(err => {
                        console.warn('Erro ao iniciar streaming após intro:', err);
                        isPlaying = false;
                        updatePlayerButtonState(false);
                    });
                    fadeVolumeTo(intToDecimal(getSavedVolume()));
                };
                introAudio.play().catch(error => {
                    console.warn('Erro ao reproduzir áudio de introdução:', error);
                    introAudioPlayed = true;
                    audio.load();
                    audio.play().catch(err => {
                        console.warn('Erro ao iniciar streaming após falha no intro:', err);
                        isPlaying = false;
                        updatePlayerButtonState(false);
                    });
                    fadeVolumeTo(intToDecimal(getSavedVolume()));
                });
            } else {
                introAudioPlayed = true;
                audio.load();
                audio.play().catch(err => {
                    console.warn('Erro ao iniciar streaming (sem intro):', err);
                    isPlaying = false;
                    updatePlayerButtonState(false);
                });
                fadeVolumeTo(intToDecimal(getSavedVolume()));
            }
        });
    };
    this.pause = function () {
        introAudio.pause();
        audio.pause();
        fadeVolumeTo(0);
        isPlaying = false;
        updatePlayerButtonState(false);
    };
}

audio.onplay = function () { isPlaying = true; };
audio.onpause = function () { if (introAudio.paused && !isPlaying) updatePlayerButtonState(false); };
introAudio.onplay = function () { isPlaying = true; };
introAudio.onpause = function () { if (audio.paused && !isPlaying) updatePlayerButtonState(false); };
audio.onvolumechange = function () { if (audio.volume > 0) audio.muted = false; };

let streamRetry = 0;
audio.onerror = function () {
    console.warn('Erro no streaming. Tentando reconectar...');
    if (streamRetry < 3) {
        setTimeout(() => {
            streamRetry++;
            audio.load();
            audio.play().catch(err => console.warn('Falha ao reconectar:', err));
        }, 3000);
    } else {
        alert('Estamos em manutenção, Voltamos Já!.');
        window.location.reload();
    }
};

const volumeSlider = document.getElementById('volume');
if (volumeSlider) {
    volumeSlider.oninput = function () {
        let volume = parseInt(this.value);
        fadeVolumeTo(intToDecimal(volume));
        let page = new Page();
        page.changeVolumeIndicator(volume);
        updateVolumeCircles(volume);
        introAudio.volume = intToDecimal(volume);
    };
}

function togglePlay() {
    var player = new Player();
    if (isPlaying) player.pause();
    else player.play();
}

function mute() {
    let sound = document.querySelector('.sound');
    let soundIcon = document.querySelector('.sound-icon');
    if (!audio.muted) {
        previousVolumeBeforeMute = parseInt(document.getElementById('volume').value);
        fadeVolumeTo(0);
        audio.muted = true;
        introAudio.muted = true;
        document.getElementById('volume').value = 0;
        updateVolumeCircles(0);
        if (sound) { sound.style.stroke = 'transparent'; sound.style.fill = 'transparent'; }
        if (soundIcon) soundIcon.style.fill = 'transparent';
    } else {
        let vol = previousVolumeBeforeMute !== null ? previousVolumeBeforeMute : getSavedVolume();
        fadeVolumeTo(intToDecimal(vol));
        audio.muted = false;
        introAudio.muted = false;
        introAudio.volume = intToDecimal(vol);
        document.getElementById('volume').value = vol;
        updateVolumeCircles(vol);
        if (sound) { sound.style.stroke = '#f00'; sound.style.fill = '#f00'; }
        if (soundIcon) soundIcon.style.fill = '#fff';
        previousVolumeBeforeMute = null;
    }
}

function getStreamingData(forceUpdate = false) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const data = JSON.parse(this.responseText);
            const page = new Page();
            const song = data.currentSong.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
            const artist = data.currentArtist.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
            const currentKey = `${artist} - ${song}`;
            document.title = `${RADIO_NAME} | ${artist} - ${song}`.toUpperCase();
            if (lastSongKey !== currentKey || forceUpdate) {
                lastSongKey = currentKey;
                page.refreshCover(song, artist, forceUpdate);
                page.refreshCurrentSong(song, artist);
                if (data.songHistory && Array.isArray(data.songHistory)) {
                    for (let i = 0; i < 2; i++) {
                        if (data.songHistory[i]) {
                            page.refreshHistoric(data.songHistory[i], i);
                        }
                    }
                }
            }
        }
    };
    xhttp.open('GET', `/ritualfm/public/api.php?url=${URL_STREAMING}&historic=${HISTORIC}&t=${new Date().getTime()}`, true);
    xhttp.send();
}

function intToDecimal(vol) { return Math.min(Math.max(parseInt(vol) / 100, 0), 1); }
function getSavedVolume() { return parseInt(localStorage.getItem('volume')) || 20; }

function fadeVolumeTo(targetVolume, duration = 200) {
    let startVolume = audio.volume;
    let startTime = null;
    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        let progress = Math.min((timestamp - startTime) / duration, 1);
        audio.volume = startVolume + (targetVolume - startVolume) * progress;
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

function updateVolumeCircles(volume) {
    const levels = [{ class: 'level-100', min: 76 }, { class: 'level-70', min: 51 }, { class: 'level-50', min: 21 }, { class: 'level-20', min: 1 }];
    const allCircles = document.querySelectorAll('.level-100, .level-70, .level-50, .level-20');
    allCircles.forEach(c => c.style.stroke = '#222222');
    if (volume > 0) {
        levels.forEach(level => {
            if (volume >= level.min) {
                document.querySelectorAll(`.${level.class}`).forEach(c => c.style.stroke = '#f00');
            }
        });
    }
    updateSoundIcons(volume > 0 && !audio.muted);
}

function updateSoundIcons(isActive) {
    const sound = document.querySelector('path.sound');
    const soundIcon = document.querySelector('.sound-icon');
    const strokeColor = isActive ? '#f00' : 'transparent';
    const fillColor = isActive ? '#f00' : 'transparent';
    const iconFill = isActive ? '#fff' : 'transparent';
    if (sound) { sound.style.stroke = strokeColor; sound.style.fill = fillColor; }
    if (soundIcon) soundIcon.style.fill = iconFill;
}

function setVolumeLevel(level) {
    fadeToVolume(level / 100);
    introAudio.volume = level / 100;
    updateVolumeCircles(level);
    const volumeSlider = document.getElementById('volume');
    if (volumeSlider) volumeSlider.value = level;
    localStorage.setItem('volume', level);
    audio.muted = false;
    introAudio.muted = false;
}

function toggleMute() {
    const volumeSlider = document.getElementById('volume');
    const sound = document.querySelector('.sound');
    const soundIcon = document.querySelector('.sound-icon');
    if (audio.muted || audio.volume === 0) {
        let vol = previousVolumeBeforeMute !== null ? previousVolumeBeforeMute : getSavedVolume();
        fadeToVolume(vol / 100);
        introAudio.volume = vol / 100;
        introAudio.muted = false;
        updateVolumeCircles(vol);
        audio.muted = false;
        if (volumeSlider) volumeSlider.value = vol;
        localStorage.setItem('volume', vol);
        if (sound) { sound.style.stroke = '#f00'; sound.style.fill = '#f00'; }
        if (soundIcon) soundIcon.style.fill = '#fff';
        previousVolumeBeforeMute = null;
    } else {
        if (volumeSlider) previousVolumeBeforeMute = parseInt(volumeSlider.value);
        else previousVolumeBeforeMute = getSavedVolume();
        fadeToVolume(0);
        introAudio.volume = 0;
        introAudio.muted = true;
        updateVolumeCircles(0);
        audio.muted = true;
        if (volumeSlider) volumeSlider.value = 0;
        if (sound) { sound.style.stroke = 'transparent'; sound.style.fill = 'transparent'; }
        if (soundIcon) soundIcon.style.fill = 'transparent';
    }
}

function fadeToVolume(targetVolume) {
    const step = 0.02;
    const delay = 20;
    let current = audio.volume;
    if (current === targetVolume) return;
    const direction = current < targetVolume ? 1 : -1;
    const fade = setInterval(() => {
        current += direction * step;
        if ((direction === 1 && current >= targetVolume) || (direction === -1 && current <= targetVolume)) {
            audio.volume = targetVolume;
            clearInterval(fade);
        } else {
            audio.volume = Math.max(0, Math.min(1, current));
        }
    }, delay);
}

function syncPlayerUI() {
    console.log("Sincronizando UI do Player...");
    // Reativa a flag para que o script saiba que deve esperar a capa da home
    window.shouldShowLoaderUntilCoverLoads = true;
    getStreamingData(true);
    updatePlayerButtonState(isPlaying);
}