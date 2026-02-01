const RADIO_NAME = "RITUAL FM";
const URL_STREAMING = "https://radio.saopaulo01.com.br:10996";
const API_KEY = "a1c008912307d1d2200a4b72fd1ce4b0";
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
    
    // Removida a inicialização automática do player
    // var player = new Player();
    // player.play();
    
    getStreamingData();
    setInterval(getStreamingData, 4000);

    var coverArt = document.getElementsByClassName('cover-album')[0];
    coverArt.style.height = coverArt.offsetWidth + 'px';
};

function Page() {
    this.changeTitlePage = function (title = RADIO_NAME) {
        document.title = title;
    };
    this.refreshCurrentSong = function (song, artist) {
        var currentSong = document.getElementById('currentSong');
        var currentArtist = document.getElementById('currentArtist');
        if (song !== currentSong.innerHTML) {
            currentSong.className = 'animated songs_txt';
            currentSong.innerHTML = song;
            currentArtist.className = 'animated songs_txt';
            currentArtist.innerHTML = artist;
            document.getElementById('lyricsSong').innerHTML = artist + ' - ' + song;
            setTimeout(() => {
                currentSong.className = 'animated songs_txt';
                currentArtist.className = 'animated songs_txt';
            }, 4000);
        }
    };
    this.refreshHistoric = function (info, n) {
        var $historicDiv = document.querySelectorAll('#historicSong');
        var $songName = document.querySelectorAll('#historicSong .music-info .song');
        var $artistName = document.querySelectorAll('#historicSong .music-info .artist');
        var urlCoverArt = '/images/fallback1.jpg';
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var data = JSON.parse(this.responseText);
                var artworkUrl = (data.resultCount) ? data.results[0].artworkUrl100 : urlCoverArt;
                document.querySelectorAll('#historicSong .cover-historic')[n].style.backgroundImage = 'url(' + artworkUrl + ')';
            }
            $songName[n].innerHTML = info.song.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
            $artistName[n].innerHTML = info.artist.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
            $historicDiv[n].classList.add('animated', 'songs_txt');
        };
        xhttp.open('GET', `https://itunes.apple.com/search?term=${info.artist} ${info.song}&media=music&limit=1`, true);
        xhttp.send();
        setTimeout(() => {
            for (let j = 0; j < 2; j++) {
                $historicDiv[j].classList.remove('animated');
            }
        }, 4000);
    };
this.refreshCover = function (song = '', artist) {
    const coverBackground = document.getElementById('cover-site');
    const coverArt = document.getElementById('currentCoverArt');
    const defaultCovers = [
        "/images/fallback1.jpg",
        "/images/fallback2.jpg",
        "/images/fallback3.jpg",
        "/images/fallback4.jpg",
		"/images/fallback5.jpg",
		"/images/fallback6.jpg"
    ];
    const searchQueries = [
        `${artist} ${song}`,
        `${artist} ${song.split('(')[0].split('-')[0]}`.trim(),
        `${artist}`
    ];

    let currentBg = coverBackground.style.backgroundImage || '';
    let attempt = 0;

    const specialCovers = [
        { keyword: "RITUAL FM", image: "/images/aovivo.jpg" },
        { keyword: "CLUBLIVE", image: "/images/clublive.jpg" },
        { keyword: "CLUB SESSION MIX", image: "/images/aovivo.jpg" },
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
                        tryFetchCover(); // Próxima tentativa
                    }
                } else {
                    tryFetchCover(); // Próxima tentativa em erro
                }
            }
        };
        xhttp.open('GET', `https://itunes.apple.com/search?term=${encodeURIComponent(query)}&media=music&limit=1`, true);
        xhttp.send();
    }

    tryFetchCover();

   function applyCover(url) {
  if (lastCoverUrl === url) return;

  lastCoverUrl = url;

  const currentBg = coverBackground.style.backgroundImage;
  if (currentBg.includes(url)) return;

  const img = new Image();
  img.onload = () => {
    coverBackground.classList.remove('clipIn', 'clipOut');
    coverBackground.classList.add('clipOut');

    coverBackground.addEventListener('animationend', function onOutEnd(e) {
      if (e.animationName === 'clipOut') {
        coverBackground.removeEventListener('animationend', onOutEnd);
        coverBackground.style.backgroundImage = `url(${url})`;
        coverBackground.classList.remove('clipOut');
        coverBackground.classList.add('clipIn');
      }
    });

    // Mini player fade
    currentCoverArt.style.transition = 'opacity 0.3s ease-in';
    currentCoverArt.style.opacity = 0;
    setTimeout(() => {
      currentCoverArt.style.backgroundImage = `url(${url})`;
      currentCoverArt.style.opacity = 1;
    }, 300);
  };

  img.src = url;
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
    this.setVolume = function () {
        if (typeof Storage !== 'undefined') {
            var volume = parseInt(localStorage.getItem('volume')) || 20;
            document.getElementById('volume').value = volume;
            updateVolumeCircles(volume);
        }
    };
    this.refreshLyric = function (song, artist) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            var openLyric = document.getElementsByClassName('lyrics')[0];
            if (this.readyState === 4 && this.status === 200) {
                var data = JSON.parse(this.responseText);
                if (data.type === 'exact' || data.type === 'aprox') {
                    document.getElementById('lyric').innerHTML = data.mus[0].text.replace(/\n/g, '<br />');
                    openLyric.style.opacity = "1";
                    openLyric.setAttribute('data-toggle', 'modal');
                } else {
                    openLyric.style.opacity = "0.3";
                    openLyric.removeAttribute('data-toggle');
                    document.getElementById('modalLyrics').style.display = "none";
                }
            } else {
                openLyric.style.opacity = "0.3";
                openLyric.removeAttribute('data-toggle');
            }
        };
        xhttp.open('GET', `https://api.vagalume.com.br/search.php?apikey=${API_KEY}&art=${artist}&mus=${song.toLowerCase()}`, true);
        xhttp.send();
    };
}

var audio = new Audio(URL_STREAMING + '/;');
// Criando o elemento de áudio para a introdução
var introAudio = new Audio(INTRO_AUDIO_URL);

// Função para atualizar o estado visual dos botões de play/stop
function updatePlayerButtonState(playing) {
    if (playing) {
        document.getElementById('playerButton').className = 'bt_stop';
        document.getElementById('playerButton2').className = 'mini_stop';
    } else {
        document.getElementById('playerButton').className = 'bt_play';
        document.getElementById('playerButton2').className = 'mini_play';
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
                introAudio.onended = function() {
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
introAudio.onended = function() {
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


document.getElementById('volume').oninput = function () {
    let volume = parseInt(this.value);
    fadeVolumeTo(intToDecimal(volume));
    let page = new Page();
    page.changeVolumeIndicator(volume);
    updateVolumeCircles(volume);
    
    // Atualiza também o volume do áudio de introdução
    introAudio.volume = intToDecimal(volume);
};

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

function getStreamingData() {
  const xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const data = JSON.parse(this.responseText);
      const page = new Page();

      const song = data.currentSong.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
      const artist = data.currentArtist.replace(/&(?:apos|amp);/g, m => m === "&apos;" ? "'" : "&");
      const currentKey = `${artist} - ${song}`;

      document.title = `${RADIO_NAME} | ${artist} - ${song}`.toUpperCase();

      if (lastSongKey !== currentKey) {
        lastSongKey = currentKey;
        page.refreshCover(song, artist);
        page.refreshCurrentSong(song, artist);
        page.refreshLyric(song, artist);

        for (let i = 0; i < 2; i++) {
          page.refreshHistoric(data.songHistory[i], i);
        }
      }
    }
  };
  xhttp.open('GET', `/api.php?url=${URL_STREAMING}&historic=${HISTORIC}&t=${new Date().getTime()}`, true);
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

document.querySelectorAll('.level-100, .level-70, .level-50, .level-20').forEach(el => {
    el.addEventListener('click', () => {
        const level = parseInt(el.className.match(/\d+/)[0]); // extrai 20, 50, 70, 100
        setVolumeLevel(level);
    });
});

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

function setVolumeLevel(level) {
    fadeToVolume(level / 100);
    // Também atualiza o volume do áudio de introdução
    introAudio.volume = level / 100;
    updateVolumeCircles(level);
    document.getElementById('volume').value = level;
    localStorage.setItem('volume', level);
    audio.muted = false;
    introAudio.muted = false;
}

function toggleMute() {
    let sound = document.querySelector('.sound');
    let soundIcon = document.querySelector('.sound-icon');

    if (audio.muted || audio.volume === 0) {
        let vol = previousVolumeBeforeMute !== null ? previousVolumeBeforeMute : getSavedVolume();

        fadeToVolume(vol / 100);
        introAudio.volume = vol / 100;
        introAudio.muted = false;
        updateVolumeCircles(vol);
        audio.muted = false;
        document.getElementById('volume').value = vol;
        localStorage.setItem('volume', vol);

        if (sound) {
            sound.style.stroke = '#f00';
            sound.style.fill = '#f00';
        }
        if (soundIcon) {
            soundIcon.style.fill = '#fff';
        }

        previousVolumeBeforeMute = null; // limpa o backup
    } else {
        // Salva volume atual antes de silenciar
        previousVolumeBeforeMute = parseInt(document.getElementById('volume').value);

        fadeToVolume(0);
        introAudio.volume = 0;
        introAudio.muted = true;
        updateVolumeCircles(0);
        audio.muted = true;
        document.getElementById('volume').value = 0;

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