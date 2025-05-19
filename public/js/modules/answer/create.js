document.addEventListener('DOMContentLoaded', function() {
    const formSelect = document.getElementById('form_id');
    const sectionSelect = document.getElementById('section_id');
    const questionSelect = document.getElementById('question_id');

    // Evento para cargar secciones cuando se selecciona un formulario
    formSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value === '') {
            sectionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
            sectionSelect.disabled = true;
            questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
            questionSelect.disabled = true;
            return;
        }

        const sections = JSON.parse(selectedOption.dataset.sections);
        
        sectionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
        sections.forEach(section => {
            sectionSelect.innerHTML += `<option value="${section.id}" 
                data-questions='${JSON.stringify(section.questions)}'>
                ${section.name}
            </option>`;
        });
        sectionSelect.disabled = false;
        questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
        questionSelect.disabled = true;
    });

    // Evento para cargar preguntas cuando se selecciona una sección
    sectionSelect.addEventListener('change', function() {
        if (this.value === '') {
            questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
            questionSelect.disabled = true;
            return;
        }

        const selectedOption = this.options[this.selectedIndex];
        const questions = JSON.parse(selectedOption.dataset.questions);
        
        questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
        questions.forEach(question => {
            questionSelect.innerHTML += `<option value="${question.id}">
                ${question.text}
            </option>`;
        });
        questionSelect.disabled = false;
    });

    // Si hay valores antiguos (por ejemplo, después de un error de validación), intentamos restaurar la selección
    if (formSelect.value) {
        const event = new Event('change');
        formSelect.dispatchEvent(event);
        
        // Esperar a que se carguen las secciones antes de intentar seleccionar una
        setTimeout(() => {
            if (sectionSelect.querySelector(`option[value="${sectionSelect.value}"]`)) {
                sectionSelect.dispatchEvent(new Event('change'));
            }
        }, 100);
    }
});