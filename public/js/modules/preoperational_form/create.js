document.addEventListener('DOMContentLoaded', function () {
    const vehicleTypeSelect = document.getElementById('vehicle_type_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    const sectionsContainer = document.getElementById('sections-container');
    const submitBtn = document.getElementById('submitBtn');
    const newMileageInput = document.getElementById('new_mileage');
    
    // Deshabilitar el select de vehículos inicialmente si no está ya deshabilitado
    if (!vehicleSelect.disabled) {
        vehicleSelect.disabled = true;
    }
    
    // Filtrar vehículos por tipo
    function filterVehiclesByType(typeId) {
        // Habilitar el select de vehículos solo si no está deshabilitado por ser conductor
        if (!vehicleSelect.hasAttribute('disabled')) {
            vehicleSelect.disabled = false;
            
            // Resetear la selección solo si no es conductor
            vehicleSelect.value = '';
        }
        
        // Filtrar opciones
        Array.from(vehicleSelect.options).forEach(option => {
            if (!option.value) return; // Omitir "Seleccione..."
            const show = option.dataset.type === typeId;
            option.hidden = !show;
        });
        
        // Resetear el kilometraje solo si no hay un vehículo seleccionado
        if (!vehicleSelect.value) {
            document.getElementById('last_mileage_display').textContent = 'Seleccione un vehículo';
            document.getElementById('new_mileage').value = '';
        }
    }
    
    // Función para manejar la visibilidad de las secciones y sus campos required
    function toggleSectionVisibility(sectionCard, show) {
        // Mostrar u ocultar la sección
        sectionCard.style.display = show ? 'block' : 'none';
        
        // Obtener todos los inputs de radio en la sección
        const radioButtons = sectionCard.querySelectorAll('input[type="radio"]');
        
        // Establecer o quitar el atributo required según la visibilidad
        radioButtons.forEach(radio => {
            if (show) {
                radio.setAttribute('required', 'required');
            } else {
                radio.removeAttribute('required');
            }
        });
    }
    
    // Evento al cambiar el tipo de vehículo
    vehicleTypeSelect.addEventListener('change', function() {
        const typeId = this.value;
        
        if (typeId) {
            // Filtrar vehículos por tipo
            filterVehiclesByType(typeId);
            
            // Mostrar las secciones correspondientes
            const sectionCards = document.querySelectorAll('.section-card');
            sectionCards.forEach(card => {
                const vehicleTypes = JSON.parse(card.dataset.vehicleTypes || '[]');
                const shouldShow = vehicleTypes.includes(parseInt(typeId));
                
                toggleSectionVisibility(card, shouldShow);
                
                if (shouldShow) {
                    // Asegurarse de que no haya respuestas preseleccionadas
                    const radioButtons = card.querySelectorAll('input[type="radio"]');
                    radioButtons.forEach(radio => {
                        // Solo desmarcar si no hay un valor antiguo (old) seleccionado
                        if (!radio.hasAttribute('checked')) {
                            radio.checked = false;
                        }
                    });
                }
            });
            
            document.getElementById('sections-info').style.display = 'none';
        } else {
            // Deshabilitar el select de vehículos si no está ya deshabilitado por ser conductor
            if (!vehicleSelect.hasAttribute('disabled')) {
                vehicleSelect.disabled = true;
                vehicleSelect.value = '';
            }
            
            // Ocultar todas las secciones
            const sectionCards = document.querySelectorAll('.section-card');
            sectionCards.forEach(card => {
                toggleSectionVisibility(card, false);
            });
            
            document.getElementById('sections-info').style.display = 'block';
            document.getElementById('last_mileage_display').textContent = 'Seleccione un vehículo';
            document.getElementById('new_mileage').value = '';
            submitBtn.disabled = true;
        }
    });
    
    // Evento al cambiar el vehículo
    vehicleSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const lastMileage = selectedOption.dataset.mileage;
            
            // Mostrar el último kilometraje
            document.getElementById('last_mileage_display').textContent = lastMileage + ' km';
            
            // Validar el kilometraje si ya hay un valor ingresado
            if (newMileageInput.value) {
                validateMileage();
            }
            
            // Verificar si todas las preguntas tienen respuesta
            checkAllQuestionsAnswered();
        } else {
            document.getElementById('last_mileage_display').textContent = 'Seleccione un vehículo';
            submitBtn.disabled = true;
        }
    });
    
    // Evento al cambiar el valor del kilometraje
    newMileageInput.addEventListener('input', function() {
        validateMileage();
        checkAllQuestionsAnswered();
    });
    
    // Función para validar el kilometraje
    function validateMileage() {
        const newMileage = parseInt(newMileageInput.value) || 0;
        const lastMileage = getLastMileage();
        
        if (newMileage < lastMileage) {
            newMileageInput.classList.add('is-invalid');
            submitBtn.disabled = true;
            return false;
        } else {
            newMileageInput.classList.remove('is-invalid');
            return true;
        }
    }
    
    // Función para verificar si todas las preguntas visibles tienen respuesta
    function checkAllQuestionsAnswered() {
        const visibleSections = document.querySelectorAll('.section-card[style="display: block;"]');
        let allAnswered = true;
        
        visibleSections.forEach(section => {
            const questions = section.querySelectorAll('tbody tr');
            
            questions.forEach(question => {
                const radioButtons = question.querySelectorAll('input[type="radio"]');
                const answered = Array.from(radioButtons).some(radio => radio.checked);
                
                if (!answered) {
                    allAnswered = false;
                }
            });
        });
        
        // Habilitar el botón solo si todas las preguntas tienen respuesta y el kilometraje es válido
        const mileageValid = validateMileage();
        
        if (allAnswered && mileageValid) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    // Función para obtener el último kilometraje
    function getLastMileage() {
        const mileageText = document.getElementById('last_mileage_display').textContent;
        if (mileageText === 'Seleccione un vehículo') return 0;
        return parseInt(mileageText.replace(/[^\d]/g, '')) || 0;
    }
    
    // Agregar evento para verificar respuestas cuando se selecciona una opción
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', checkAllQuestionsAnswered);
    });
    
    // Agregar evento para verificar cuando cambia el kilometraje
    document.getElementById('new_mileage').addEventListener('input', checkAllQuestionsAnswered);
    
    // Agregar evento de envío al formulario
    document.getElementById('preoperationalForm').addEventListener('submit', function(event) {
        // Verificar si el botón está habilitado
        if (submitBtn.disabled) {
            event.preventDefault();
            alert('Por favor, complete todos los campos requeridos antes de enviar el formulario.');
            return false;
        }
        
        // Verificar si todas las preguntas tienen respuesta
        const visibleSections = document.querySelectorAll('.section-card[style="display: block;"]');
        let allAnswered = true;
        let unansweredQuestions = [];
        
        visibleSections.forEach(section => {
            const questions = section.querySelectorAll('tbody tr');
            
            questions.forEach((question, index) => {
                const radioButtons = question.querySelectorAll('input[type="radio"]');
                const answered = Array.from(radioButtons).some(radio => radio.checked);
                
                if (!answered) {
                    allAnswered = false;
                    unansweredQuestions.push(`${section.querySelector('.card-header h5').textContent} - Pregunta ${index + 1}`);
                }
            });
        });
        
        if (!allAnswered) {
            event.preventDefault();
            alert(`Por favor, responda todas las preguntas antes de enviar el formulario.\n\nPreguntas sin responder:\n${unansweredQuestions.join('\n')}`);
            return false;
        }
        
        // Verificar el kilometraje
        const newMileage = parseInt(document.getElementById('new_mileage').value) || 0;
        const lastMileage = getLastMileage();
        
        if (newMileage < lastMileage) {
            event.preventDefault();
            alert('El nuevo kilometraje debe ser mayor o igual que el último registrado.');
            return false;
        }
        
        if (newMileage === 0) {
            event.preventDefault();
            alert('Por favor, ingrese un kilometraje válido.');
            return false;
        }
        
        // Si todo está bien, permitir el envío
        return true;
    });
    
    // Inicializar si hay valores preseleccionados
    function initializeForm() {
        // Si hay un vehículo preseleccionado y está deshabilitado (usuario conductor)
        if (vehicleSelect.disabled && vehicleSelect.value === '') {
            // Buscar la opción seleccionada por el hidden input
            const hiddenVehicleInput = document.querySelector('input[type="hidden"][name="vehicle_id"]');
            if (hiddenVehicleInput) {
                const vehicleId = hiddenVehicleInput.value;
                // Seleccionar la opción correspondiente
                const option = Array.from(vehicleSelect.options).find(opt => opt.value === vehicleId);
                if (option) {
                    option.selected = true;
                    
                    // Mostrar el último kilometraje
                    const lastMileage = option.dataset.mileage;
                    document.getElementById('last_mileage_display').textContent = lastMileage + ' km';
                }
            }
        }
        
        // Si hay un tipo de vehículo seleccionado
        if (vehicleTypeSelect.value) {
            // Si el select de vehículos no está deshabilitado, habilitarlo
            if (!vehicleSelect.disabled) {
                vehicleSelect.disabled = false;
            }
            
            // Filtrar vehículos por tipo (solo si no es conductor)
            if (!vehicleSelect.disabled) {
                filterVehiclesByType(vehicleTypeSelect.value);
            }
            
            // Mostrar las secciones correspondientes
            const sectionCards = document.querySelectorAll('.section-card');
            sectionCards.forEach(card => {
                const vehicleTypes = JSON.parse(card.dataset.vehicleTypes || '[]');
                const shouldShow = vehicleTypes.includes(parseInt(vehicleTypeSelect.value));
                toggleSectionVisibility(card, shouldShow);
            });
            
            document.getElementById('sections-info').style.display = 'none';
            
            // Si también hay un vehículo seleccionado, mostrar su kilometraje
            if (vehicleSelect.value) {
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                if (selectedOption) {
                    const lastMileage = selectedOption.dataset.mileage;
                    document.getElementById('last_mileage_display').textContent = lastMileage + ' km';
                }
            }
        } else {
            // Ocultar todas las secciones inicialmente
            const sectionCards = document.querySelectorAll('.section-card');
            sectionCards.forEach(card => {
                toggleSectionVisibility(card, false);
            });
            
            document.getElementById('sections-info').style.display = 'block';
        }
        
        // Verificar si todas las preguntas tienen respuesta para habilitar el botón
        checkAllQuestionsAnswered();
    }
    
    // Inicializar el formulario
    initializeForm();
});