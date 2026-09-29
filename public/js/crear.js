document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-ticket');
    const titulo = document.getElementById('titulo');
    const descripcion = document.getElementById('descripcion');
    const contador = document.getElementById('contador-titulo');
    const error = document.getElementById('error-cliente');

    const maximo = titulo.maxLength;

    const actualizarContador = () => {
        contador.textContent = `${titulo.value.length} / ${maximo}`;
    };

    titulo.addEventListener('input', actualizarContador);
    actualizarContador();

    form.addEventListener('submit', (evento) => {
        let mensaje = '';
        if (titulo.value.trim() === '') {
            mensaje = 'El título es obligatorio.';
        } else if (descripcion.value.trim() === '') {
            mensaje = 'La descripción es obligatoria.';
        }

        if (mensaje !== '') {
            evento.preventDefault();
            error.textContent = mensaje;
            error.classList.remove('oculto');
        }
    });
});
