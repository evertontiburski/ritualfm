const RADIO_NAME = "RITUAL FM";
const URL_STREAMING = "https://radio.saopaulo01.com.br:10996";
const HISTORIC = true;
let lastSongKey = "";
let lastCoverUrl = "";
let previousVolumeBeforeMute = null;
// Variável para controlar se o áudio de introdução já foi tocado nesta sessão
let introAudioPlayed = false;
// URL do áudio de introdução que será tocado antes do streaming
const INTRO_AUDIO_URL = ""; // Substitua pelo caminho real do seu áudio
// Variável para controlar o estado de reprodução
let isPlaying = false;

window.onload = function () {
    var page = new Page();
    page.changeTitlePage();
    page.setVolume();

    getStreamingData();
    setInterval(getStreamingData, 4000);

    var coverArt = document.getElementsByClassName('cover-album')[0];
    coverArt.style.height = coverArt.offsetWidth + 'px';
};

function Page() {
    this.changeTitlePage = function (title = RADIO_NAME) {
        document.title = title;
    };

    // this.refreshCurrentSong = function (song, artist) {
    //     const currentSongEl = document.getElementById('currentSong');
    //     const currentArtistEl = document.getElementById('currentArtist');

    //     // Só executa se os elementos do player da home existirem.
    //     if (currentSongEl && currentArtistEl) {
    //         if (song !== currentSongEl.innerHTML) {
    //             currentSongEl.className = 'animated songs_txt';
    //             currentSongEl.innerHTML = song;
    //             currentArtistEl.className = 'animated songs_txt';
    //             currentArtistEl.innerHTML = artist;

    //             setTimeout(() => {
    //                 currentSongEl.className = 'animated songs_txt';
    //                 currentArtistEl.className = 'animated songs_txt';
    //             }, 4000);
    //         }
    //     }
    // };

    this.refreshCurrentSong = function (song, artist) {
        // --- Alvos do Player Principal (na Home) ---
        const mainPlayerSongEl = document.getElementById('currentSong');
        const mainPlayerArtistEl = document.getElementById('currentArtist');

        // --- Alvo do Mini Player (em todas as páginas) ---
        const miniPlayerTextEl = document.getElementById('lyricsSong');

        // Só executa se os elementos do player da home existirem
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

        // Só executa se o elemento de texto do mini-player existir.
        if (miniPlayerTextEl) {
            const newText = `${artist} - ${song}`;
            // Otimização: só atualiza o HTML se o texto realmente mudou.
            if (miniPlayerTextEl.innerHTML !== newText) {
                miniPlayerTextEl.innerHTML = newText;
            }
        }
    };

    this.refreshHistoric = function (info, n) {
        var $historicDiv = document.querySelectorAll('#historicSong');
        // Só executa o código se o elemento de histórico [n] existir na página
        if ($historicDiv[n]) {
            var $songName = document.querySelectorAll('#historicSong .music-info .song');
            var $artistName = document.querySelectorAll('#historicSong .music-info .artist');
            var urlCoverArt = '/ritualfm/public/images/images/fallback1.jpg'; // Verifique se este caminho está correto
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var data = JSON.parse(this.responseText);
                    var artworkUrl = (data.resultCount) ? data.results[0].artworkUrl100 : urlCoverArt;
                    var coverHistoric = document.querySelectorAll('#historicSong .cover-historic');
                    if (coverHistoric[n]) { // Checagem extra para a imagem
                        coverHistoric[n].style.backgroundImage = 'url(' + artworkUrl + ')';
                    }
                }
                // Garante que os elementos de texto existem antes de modificar
                if ($songName[n] && $artistName[n]) {
                    $songName[n].innerHTML = info.song.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
                    $artistName[n].innerHTML = info.artist.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
                }
                $historicDiv[n].classList.add('animated', 'songs_txt');
            };
            xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(info.artist)} ${encodeURIComponent(info.song)}&media=music&limit=1`, true);
            xhttp.send();

            setTimeout(() => {
                // Loop seguro, verifica se cada elemento existe antes de remover a classe
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
    //     const defaultCovers = [
    //         "/ritualfm/public/images/fallback1.jpg",
    //         "/ritualfm/public/images/fallback2.jpg",
    //         "/ritualfm/public/images/fallback3.jpg",
    //         "/ritualfm/public/images/fallback4.jpg",
    //         "/ritualfm/public/images/fallback5.jpg",
    //         "/ritualfm/public/images/fallback6.jpg"
    //     ];
    //     const searchQueries = [
    //         `${artist} ${song}`,
    //         `${artist} ${song.split('(')[0].split('-')[0]}`.trim(),
    //         `${artist}`
    //     ];

    //     let currentBg = coverBackground.style.backgroundImage || '';
    //     let attempt = 0;

    //     const specialCovers = [
    //         { keyword: "RITUAL FM", image: "/ritualfm/public/images/aovivo.jpg" },
    //         { keyword: "CLUBLIVE", image: "/ritualfm/public/images/clublive.jpg" },
    //         { keyword: "CLUB SESSION MIX", image: "/ritualfm/public/images/aovivo.jpg" },
    //     ];

    //     const match = specialCovers.find(item => `${artist} ${song}`.toLowerCase().includes(item.keyword.toLowerCase()));
    //     if (match) {
    //         applyCover(match.image);
    //         updateMediaSession(song, artist, match.image);
    //         return;
    //     }

    //     function tryFetchCover() {
    //         if (attempt >= searchQueries.length) {
    //             const fallback = defaultCovers[Math.floor(Math.random() * defaultCovers.length)];
    //             applyCover(fallback);
    //             updateMediaSession(song, artist, fallback);
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
    //                         updateMediaSession(song, artist, artworkUrl);
    //                     } else {
    //                         tryFetchCover(); // Próxima tentativa
    //                     }
    //                 } else {
    //                     tryFetchCover(); // Próxima tentativa em erro
    //                 }
    //             }
    //         };
    //         xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
    //         xhttp.send();
    //     }

    //     tryFetchCover();

    //     function applyCover(url) {
    //         // A condição só para a execução se a URL for a mesma E NÃO for uma atualização forçada
    //         if (lastCoverUrl === url && !forceUpdate) return;

    //         lastCoverUrl = url;

    //         // Garante que o elemento do background principal exista
    //         if (coverBackground) {
    //             const currentBg = coverBackground.style.backgroundImage;
    //             // A mesma lógica de 'forceUpdate' aqui
    //             if (currentBg.includes(url) && !forceUpdate) return;

    //             const img = new Image();
    //             img.onload = () => {
    //                 coverBackground.classList.remove('clipIn', 'clipOut');
    //                 coverBackground.classList.add('clipOut');

    //                 coverBackground.addEventListener('animationend', function onOutEnd(e) {
    //                     if (e.animationName === 'clipOut') {
    //                         coverBackground.removeEventListener('animationend', onOutEnd);
    //                         coverBackground.style.backgroundImage = `url(${url})`;
    //                         coverBackground.classList.remove('clipOut');
    //                         coverBackground.classList.add('clipIn');
    //                     }
    //                 });
    //             };
    //             img.src = url;
    //         }

    //         // Garante que o elemento do mini player exista
    //         if (coverArt) {
    //             // A capa do miniplayer deve sempre atualizar, então não precisa de verificação
    //             currentCoverArt.style.transition = 'opacity 0.3s ease-in';
    //             currentCoverArt.style.opacity = 0;
    //             setTimeout(() => {
    //                 currentCoverArt.style.backgroundImage = `url(${url})`;
    //                 currentCoverArt.style.opacity = 1;
    //             }, 300);
    //         }
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
    // };



    this.refreshCover = function (song = '', artist, forceUpdate = false) {

        // Busca os elementos. Eles podem ser nulos se não estiverem na página
        const coverBackground = document.getElementById('cover-site');
        const coverArt = document.getElementById('currentCoverArt');

        const defaultCovers = [
            "/ritualfm/public/images/fallback1.jpg",
            "/ritualfm/public/images/fallback2.jpg",
            "/ritualfm/public/images/fallback3.jpg",
            "/ritualfm/public/images/fallback4.jpg",
            "/ritualfm/public/images/fallback5.jpg",
            "/ritualfm/public/images/fallback6.jpg"
        ];
        const searchQueries = [
            `${artist} ${song}`,
            `${artist} ${song.split('(')[0].split('-')[0]}`.trim(),
            `${artist}`
        ];

        let attempt = 0;

        const specialCovers = [
            { keyword: "RITUAL FM", image: "/ritualfm/public/images/aovivo.jpg" },
            { keyword: "CLUBLIVE", image: "/ritualfm/public/images/clublive.jpg" },
            { keyword: "CLUB SESSION MIX", image: "/ritualfm/public/images/aovivo.jpg" },
        ];

        const match = specialCovers.find(item => `${artist} ${song}`.toLowerCase().includes(item.keyword.toLowerCase()));
        if (match) {
            applyCover(match.image);
            updateMediaSession(song, artist, match.image);
            return;
        }

        function tryFetchCover() {
            if (attempt >= searchQueries.length) {
                const fallback = defaultCovers[Math.floor(Math.random() * defaultCovers.length)];
                applyCover(fallback);
                updateMediaSession(song, artist, fallback);
                return;
            }

            const query = searchQueries[attempt++];
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        const data = JSON.parse(this.responseText);
                        if (data.resultCount > 0) {
                            let artworkUrl = data.results[0].artworkUrl100.replace('100x100bb', '1600x1600bb');
                            applyCover(artworkUrl);
                            updateMediaSession(song, artist, artworkUrl);
                        } else {
                            tryFetchCover();
                        }
                    } else {
                        tryFetchCover();
                    }
                }
            };
            xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
            xhttp.send();
        }

        tryFetchCover();

        function applyCover(url) {
            if (lastCoverUrl === url && !forceUpdate) return;
            lastCoverUrl = url;

            // --- Bloco de Segurança para o Player Principal ---
            // Só executa este código se o cover de fundo da home existir
            if (coverBackground) {
                const currentBg = coverBackground.style.backgroundImage;
                if (currentBg.includes(url) && !forceUpdate) return; // Retorna aqui para não afetar o mini-player

                const img = new Image();
                img.onload = () => {
                    coverBackground.style.backgroundImage = `url(${url})`;
                };
                img.src = url;
            }

            // --- Bloco de Segurança para o Mini Player ---
            // Só executa este código se a capa do mini-player existir
            if (coverArt) {
                currentCoverArt.style.transition = 'opacity 0.3s ease-in';
                currentCoverArt.style.opacity = 0;
                setTimeout(() => {
                    currentCoverArt.style.backgroundImage = `url(${url})`;
                    currentCoverArt.style.opacity = 1;
                }, 300);
            }
        }

        function updateMediaSession(song, artist, imageUrl) {
            if ('mediaSession' in navigator) {
                navigator.mediaSession.metadata = new MediaMetadata({
                    title: `${artist} - ${song}`,
                    artist: '',
                    artwork: [{ src: imageUrl, sizes: '1600x1600', type: 'image/png' }]
                });
            }
        }
    };






    this.changeVolumeIndicator = function (volume) {
        if (typeof Storage !== 'undefined') {
            localStorage.setItem('volume', volume);
        }
    };
    // this.setVolume = function () {
    //     if (typeof Storage !== 'undefined') {
    //         var volume = parseInt(localStorage.getItem('volume')) || 20;
    //         document.getElementById('volume').value = volume;
    //         updateVolumeCircles(volume);
    //     }
    // };
    this.setVolume = function () {
        if (typeof Storage !== 'undefined') {
            var volume = parseInt(localStorage.getItem('volume')) || 20;

            // Procura pelo slider de volume principal
            var volumeSlider = document.getElementById('volume');
            // Só tenta definir o valor SE o slider existir na página
            if (volumeSlider) {
                volumeSlider.value = volume;
            }

            // Esta função atualiza os círculos e pode rodar sempre
            updateVolumeCircles(volume);
        }
    };
}

var audio = new Audio(URL_STREAMING + '/;');
// Criando o elemento de áudio para a introdução
var introAudio = new Audio(INTRO_AUDIO_URL);

// Função de teste
function updatePlayerButtonState(playing) {
    const mainButton = document.getElementById('playerButton');
    const miniButton = document.getElementById('playerButton2');

    // Define qual classe deve ser aplicada com base no estado 'playing'
    const mainClass = playing ? 'bt_stop' : 'bt_play';
    const miniClass = playing ? 'mini_stop' : 'mini_play';

    // Só altera a classe do botão principal SE ele for encontrado na página
    if (mainButton) {
        mainButton.className = mainClass;
    }

    // Faz o mesmo para o mini player por segurança
    if (miniButton) {
        miniButton.className = miniClass;
    }
}

// Função para verificar se o áudio de introdução existe
function checkIntroAudioExists() {
    return new Promise((resolve) => {
        const tempAudio = new Audio(INTRO_AUDIO_URL);
        tempAudio.onloadeddata = () => resolve(true);
        tempAudio.onerror = () => resolve(false);
        // Definir um timeout para evitar espera infinita
        setTimeout(() => resolve(false), 3000);
    });
}

function Player() {
    // Função original modificada para incluir o áudio de introdução
    this.play = function () {
        // Atualiza o estado de reprodução e o botão imediatamente
        isPlaying = true;
        updatePlayerButtonState(true);

        // Se o áudio de introdução já foi tocado nesta sessão, inicia o streaming diretamente
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

        // Verifica se o áudio de introdução existe
        checkIntroAudioExists().then(exists => {
            if (exists) {
                // Configura o volume do áudio de introdução
                introAudio.volume = intToDecimal(getSavedVolume());

                // Quando o áudio de introdução terminar, inicia o streaming
                introAudio.onended = function () {
                    // Marca que o áudio já foi tocado nesta sessão
                    introAudioPlayed = true;

                    // Mantém o estado de reprodução durante a transição
                    // Não é necessário atualizar o botão aqui, pois isPlaying já é true

                    // Inicia o streaming
                    audio.load();
                    audio.play().catch(err => {
                        console.warn('Erro ao iniciar streaming após intro:', err);
                        isPlaying = false;
                        updatePlayerButtonState(false);
                    });
                    fadeVolumeTo(intToDecimal(getSavedVolume()));
                };

                // Inicia a reprodução do áudio de introdução
                introAudio.play().catch(error => {
                    console.warn('Erro ao reproduzir áudio de introdução:', error);
                    // Em caso de erro, inicia o streaming diretamente
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
                // Se o áudio de introdução não existe, inicia o streaming diretamente
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
        // Pausa tanto o áudio de introdução quanto o streaming
        introAudio.pause();
        audio.pause();
        fadeVolumeTo(0);

        // Atualiza o estado de reprodução e o botão
        isPlaying = false;
        updatePlayerButtonState(false);
    };
}

// Eventos para o áudio de streaming
audio.onplay = function () {
    // Não é necessário atualizar o botão aqui, pois isPlaying já controla isso
    // Apenas para garantir a consistência do estado
    isPlaying = true;
};

audio.onpause = function () {
    // Só atualiza o estado se o áudio de introdução também estiver pausado
    // e não estivermos em transição (isPlaying é false apenas quando pausado explicitamente)
    if (introAudio.paused && !isPlaying) {
        updatePlayerButtonState(false);
    }
};

// Eventos para o áudio de introdução
introAudio.onplay = function () {
    // Não é necessário atualizar o botão aqui, pois isPlaying já controla isso
    // Apenas para garantir a consistência do estado
    isPlaying = true;
};

introAudio.onpause = function () {
    // Só atualiza o estado se o streaming também estiver pausado
    // e não estivermos em transição (isPlaying é false apenas quando pausado explicitamente)
    if (audio.paused && !isPlaying) {
        updatePlayerButtonState(false);
    }
};

// Evento para quando o áudio de introdução termina
introAudio.onended = function () {
    // Não fazemos nada aqui com o estado do botão
    // A lógica de transição está na função Player.play()
    // isPlaying permanece true durante a transição
};

audio.onvolumechange = function () {
    if (audio.volume > 0) audio.muted = false;
};

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


// document.getElementById('volume').oninput = function () {
//     let volume = parseInt(this.value);
//     fadeVolumeTo(intToDecimal(volume));
//     let page = new Page();
//     page.changeVolumeIndicator(volume);
//     updateVolumeCircles(volume);

//     // Atualiza também o volume do áudio de introdução
//     introAudio.volume = intToDecimal(volume);
// };

// Primeiro, tentamos encontrar o elemento slider de volume da home
const volumeSlider = document.getElementById('volume');

// SÓ adicionamos a funcionalidade 'oninput' SE o elemento existir
if (volumeSlider) {
    volumeSlider.oninput = function () {
        let volume = parseInt(this.value);
        fadeVolumeTo(intToDecimal(volume));
        let page = new Page();
        page.changeVolumeIndicator(volume);
        updateVolumeCircles(volume);

        // Atualiza também o volume do áudio de introdução
        introAudio.volume = intToDecimal(volume);
    };
}

function togglePlay() {
    if (isPlaying) {
        // Se estiver reproduzindo, pausa ambos os áudios
        var player = new Player();
        player.pause();
    } else {
        // Se estiver pausado, inicia a reprodução
        var player = new Player();
        player.play();
    }
}

function mute() {
    let sound = document.querySelector('.sound');
    let soundIcon = document.querySelector('.sound-icon');

    if (!audio.muted) {
        // Salva o volume atual antes de mutar
        previousVolumeBeforeMute = parseInt(document.getElementById('volume').value);

        fadeVolumeTo(0);
        audio.muted = true;
        introAudio.muted = true;
        document.getElementById('volume').value = 0;
        updateVolumeCircles(0);

        if (sound) {
            sound.style.stroke = 'transparent';
            sound.style.fill = 'transparent';
        }
        if (soundIcon) {
            soundIcon.style.fill = 'transparent';
        }
    } else {
        // Restaura o volume anterior ao mute
        let vol = previousVolumeBeforeMute !== null ? previousVolumeBeforeMute : getSavedVolume();

        fadeVolumeTo(intToDecimal(vol));
        audio.muted = false;
        introAudio.muted = false;
        introAudio.volume = intToDecimal(vol);
        document.getElementById('volume').value = vol;
        updateVolumeCircles(vol);

        if (sound) {
            sound.style.stroke = '#f00';
            sound.style.fill = '#f00';
        }
        if (soundIcon) {
            soundIcon.style.fill = '#fff';
        }

        // Reseta a variável
        previousVolumeBeforeMute = null;
    }
}

function level20() {
    fadeVolumeTo(0.2);
    // Também atualiza o volume do áudio de introdução
    introAudio.volume = 0.2;
    document.getElementById('volume').value = 20;
    updateVolumeCircles(20);
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

            // if (lastSongKey !== currentKey || forceUpdate) {
            //     lastSongKey = currentKey;
            //     page.refreshCover(song, artist, forceUpdate);
            //     page.refreshCurrentSong(song, artist);

            //     for (let i = 0; i < 2; i++) {
            //         page.refreshHistoric(data.songHistory[i], i);
            //     }
            // }

            if (lastSongKey !== currentKey || forceUpdate) {
                lastSongKey = currentKey;
                page.refreshCover(song, artist, forceUpdate);
                page.refreshCurrentSong(song, artist);

                // --- Bloco de Segurança para o Histórico ---
                // Verifica se a API realmente enviou um histórico de músicas e se é um array
                if (data.songHistory && Array.isArray(data.songHistory)) {
                    for (let i = 0; i < 2; i++) {
                        // Garante também que o item específico no histórico exista antes de usá-lo
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

function intToDecimal(vol) {
    return Math.min(Math.max(parseInt(vol) / 100, 0), 1);
}

function getSavedVolume() {
    return parseInt(localStorage.getItem('volume')) || 20;
}

function fadeVolumeTo(targetVolume, duration = 200) {
    let startVolume = audio.volume;
    let startTime = null;

    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        let progress = Math.min((timestamp - startTime) / duration, 1);
        audio.volume = startVolume + (targetVolume - startVolume) * progress;
        if (progress < 1) {
            requestAnimationFrame(step);
        }
    }

    requestAnimationFrame(step);
}

// document.querySelectorAll('.level-100, .level-70, .level-50, .level-20').forEach(el => {
//     el.addEventListener('click', () => {
//         const level = parseInt(el.className.match(/\d+/)[0]); // extrai 20, 50, 70, 100
//         setVolumeLevel(level);
//     });
// });

function updateVolumeCircles(volume) {
    const levels = [
        { class: 'level-100', min: 76 },
        { class: 'level-70', min: 51 },
        { class: 'level-50', min: 21 },
        { class: 'level-20', min: 1 }
    ];

    // Reseta todos os círculos
    const allCircles = document.querySelectorAll('.level-100, .level-70, .level-50, .level-20');
    allCircles.forEach(c => c.style.stroke = '#222222');

    // Se volume > 0, aplica os níveis ativos
    if (volume > 0) {
        levels.forEach(level => {
            if (volume >= level.min) {
                document.querySelectorAll(`.${level.class}`).forEach(c => {
                    c.style.stroke = '#f00';
                });
            }
        });
    }

    // Atualiza ícones de som
    updateSoundIcons(volume > 0 && !audio.muted);
}

function updateSoundIcons(isActive) {
    const sound = document.querySelector('path.sound');
    const soundIcon = document.querySelector('.sound-icon');

    const strokeColor = isActive ? '#f00' : 'transparent';
    const fillColor = isActive ? '#f00' : 'transparent';
    const iconFill = isActive ? '#fff' : 'transparent';

    if (sound) {
        sound.style.stroke = strokeColor;
        sound.style.fill = fillColor;
    }

    if (soundIcon) {
        soundIcon.style.fill = iconFill;
    }
}

// function setVolumeLevel(level) {
//     fadeToVolume(level / 100);
//     // Também atualiza o volume do áudio de introdução
//     introAudio.volume = level / 100;
//     updateVolumeCircles(level);
//     document.getElementById('volume').value = level;
//     localStorage.setItem('volume', level);
//     audio.muted = false;
//     introAudio.muted = false;
// }
function setVolumeLevel(level) {
    fadeToVolume(level / 100);
    introAudio.volume = level / 100;
    updateVolumeCircles(level);

    // VERIFICAÇÃO DE SEGURANÇA ADICIONADA AQUI
    const volumeSlider = document.getElementById('volume');
    if (volumeSlider) {
        volumeSlider.value = level;
    }

    localStorage.setItem('volume', level);
    audio.muted = false;
    introAudio.muted = false;
}

function toggleMute() {
    // Primeiro, vamos pegar os elementos e verificar se existem
    const volumeSlider = document.getElementById('volume');
    const sound = document.querySelector('.sound');
    const soundIcon = document.querySelector('.sound-icon');

    // Se o áudio está mutado ou com volume zero, vamos reativar o som.
    if (audio.muted || audio.volume === 0) {
        let vol = previousVolumeBeforeMute !== null ? previousVolumeBeforeMute : getSavedVolume();

        fadeToVolume(vol / 100);
        introAudio.volume = vol / 100;
        introAudio.muted = false;
        updateVolumeCircles(vol);
        audio.muted = false;

        // **CORREÇÃO:** Só altera o valor do slider SE ele existir
        if (volumeSlider) {
            volumeSlider.value = vol;
        }
        localStorage.setItem('volume', vol);

        if (sound) {
            sound.style.stroke = '#f00';
            sound.style.fill = '#f00';
        }
        if (soundIcon) {
            soundIcon.style.fill = '#fff';
        }

        previousVolumeBeforeMute = null; // Limpa o backup

    } else { // Se o áudio está tocando, vamos mutar.

        // **CORREÇÃO:** Só lê o valor do slider SE ele existir
        if (volumeSlider) {
            previousVolumeBeforeMute = parseInt(volumeSlider.value);
        } else {
            // Se o slider não existe, pega o valor do localStorage como fallback
            previousVolumeBeforeMute = getSavedVolume();
        }

        fadeToVolume(0);
        introAudio.volume = 0;
        introAudio.muted = true;
        updateVolumeCircles(0);
        audio.muted = true;

        // **CORREÇÃO:** Só altera o valor do slider SE ele existir
        if (volumeSlider) {
            volumeSlider.value = 0;
        }

        if (sound) {
            sound.style.stroke = 'transparent';
            sound.style.fill = 'transparent';
        }
        if (soundIcon) {
            soundIcon.style.fill = 'transparent';
        }
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

// FUNÇÃO DE SINCRONIZAÇÃO DA INTERFACE DO PLAYER
function syncPlayerUI() {
    console.log("Sincronizando UI do Player...");

    // Força a atualização dos dados da música e da capa do álbum
    // Esta função já existe e faz todo o trabalho pesado
    getStreamingData(true);

    // Atualiza o estado do botão de play/pause com base no estado atual
    // A variável 'isPlaying' já nos diz se a música está tocando ou não
    updatePlayerButtonState(isPlaying);
}