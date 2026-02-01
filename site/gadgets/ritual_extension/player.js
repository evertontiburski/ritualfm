
let audio = new Audio("https://radio.saopaulo01.com.br:10996/;");
let isPlaying = false;

chrome.action.onClicked.addListener(() => {
  if (isPlaying) {
    audio.pause();
    chrome.action.setIcon({ path: "images/off.png" });
  } else {
    audio.play().then(() => {
      chrome.action.setIcon({ path: "images/on.png" });
    }).catch((err) => {
      console.warn("Erro ao iniciar áudio:", err);
    });
  }
  isPlaying = !isPlaying;
});
