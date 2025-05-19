document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('.observation-textarea');
    
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            const charCounter = this.parentElement.querySelector('.char-counter .current-chars');
            const currentLength = this.value.length;
            charCounter.textContent = currentLength;
            
            // Cambiar color según la cantidad de caracteres
            const minChars = parseInt(this.dataset.minChars || 50);
            if (currentLength < minChars) {
                charCounter.classList.remove('text-success');
                charCounter.classList.add('text-danger');
            } else {
                charCounter.classList.remove('text-danger');
                charCounter.classList.add('text-success');
            }
        });
        
        // Disparar el evento input para inicializar el contador
        textarea.dispatchEvent(new Event('input'));
    });
});