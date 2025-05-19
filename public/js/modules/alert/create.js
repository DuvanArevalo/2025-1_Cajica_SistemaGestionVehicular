document.addEventListener('DOMContentLoaded', function() {
    const formSelect = document.getElementById('form_id');
    const sectionSelect = document.getElementById('section_id');
    const questionSelect = document.getElementById('question_id');
    const alertTypeRadios = document.querySelectorAll('input[name="alert_type"]');
    const questionFields = document.getElementById('question_fields');
    const observationFields = document.getElementById('observation_fields');

    // Mostrar/ocultar campos según tipo de alerta
    alertTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'question') {
                questionFields.classList.remove('d-none');
                observationFields.classList.add('d-none');
            } else {
                questionFields.classList.add('d-none');
                observationFields.classList.remove('d-none');
            }
        });
    });

    formSelect.addEventListener('change', function() {
        const sections = JSON.parse(this.options[this.selectedIndex].dataset.sections);
        
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

    sectionSelect.addEventListener('change', function() {
        const questions = JSON.parse(this.options[this.selectedIndex].dataset.questions);
        
        questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
        questions.forEach(question => {
            questionSelect.innerHTML += `<option value="${question.id}">
                ${question.text}
            </option>`;
        });
        questionSelect.disabled = false;
    });
});