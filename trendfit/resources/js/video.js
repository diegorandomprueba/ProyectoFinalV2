document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('storyVideo');
    const playButton = document.getElementById('playButton');
    
    if (video && playButton) {
        playButton.addEventListener('click', function() {
            toggleVideo();
        });
        
        // Función para reproducir/pausar el video
        function toggleVideo() {
            if (video.paused) {
                video.play();
                playButton.innerHTML = '<i class="fas fa-pause text-white text-2xl"></i>';
                playButton.classList.remove('w-16', 'h-16');
                playButton.classList.add('w-12', 'h-12');
            } else {
                video.pause();
                playButton.innerHTML = '<i class="fas fa-play text-white text-2xl"></i>';
                playButton.classList.remove('w-12', 'h-12');
                playButton.classList.add('w-16', 'h-16');
            }
        }
        
        // Reiniciar el botón cuando el video termina
        video.addEventListener('ended', () => {
            playButton.innerHTML = '<i class="fas fa-play text-white text-2xl"></i>';
            playButton.classList.remove('w-12', 'h-12');
            playButton.classList.add('w-16', 'h-16');
        });
    }
});