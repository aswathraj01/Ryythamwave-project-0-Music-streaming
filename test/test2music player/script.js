// script.js
const musicList = [
    { title: "Song 1", url: "../../public/assets/resource/Sunflower.mp3" },
    { title: "Song 2", url: "path/to/song2.mp3" },
    { title: "Song 3", url: "path/to/song3.mp3" },
];

const musicListElement = document.getElementById("music-list");
const audioElement = document.getElementById("audio");
const currentTrackElement = document.getElementById("current-track");

// Populate the music list
musicList.forEach((track, index) => {
    const li = document.createElement("li");
    li.textContent = track.title;
    li.onclick = () => playTrack(index);
    musicListElement.appendChild(li);
});

// Play selected track
function playTrack(index) {
    const selectedTrack = musicList[index];
    audioElement.src = selectedTrack.url;
    audioElement.play();
    currentTrackElement.textContent = selectedTrack.title;
}

// Event listener for when a track ends
audioElement.addEventListener("ended", () => {
    currentTrackElement.textContent = "None";
});
