const audioPlayer = document.getElementById('audio-player');
const seekSlider = document.getElementById('seek-slider');
const currentTimeDisplay = document.getElementById('current-time');
const totalTimeDisplay = document.getElementById('total-time');

// Update slider and current time display as audio plays
audioPlayer.addEventListener('timeupdate', () => {
    const currentTime = audioPlayer.currentTime;
    const duration = audioPlayer.duration;
    
    // Update slider position
    seekSlider.value = (currentTime / duration) * 100;
    
    // Update current time display
    currentTimeDisplay.textContent = formatTime(currentTime);
});

// Set audio's duration when metadata is loaded
audioPlayer.addEventListener('loadedmetadata', () => {
    totalTimeDisplay.textContent = formatTime(audioPlayer.duration);
});

// Seek functionality
seekSlider.addEventListener('input', () => {
    const seekTo = (seekSlider.value / 100) * audioPlayer.duration;
    audioPlayer.currentTime = seekTo;
});

// Helper function to format time as MM:SS
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}
