document.addEventListener('DOMContentLoaded', () => {
    // Validación de tamaño de imagen en formularios
    const inputImagen = document.getElementById('imagen');
    if (inputImagen) {
        inputImagen.addEventListener('change', () => {
            const file = inputImagen.files[0];
            if (file && file.size > 5 * 1024 * 1024) { // 5 MB
                mostrarNotificacion('La imagen excede el tamaño máximo permitido (5 MB)', 'error');
                inputImagen.value = ''; // limpia el input
            }
        });
    }

    // Modal de imagen
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.querySelector('.image-modal .close-btn');

    document.querySelectorAll('.clickable-image').forEach(img => {
        img.addEventListener('click', () => {
            modal.style.display = 'flex';
            modalImg.src = img.src;
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape") {
            modal.style.display = 'none';
        }
    });
});

function mostrarNotificacion(mensaje, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${tipo}`;
    toast.textContent = mensaje;
    document.body.prepend(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 1000);
    }, 4000);
}
