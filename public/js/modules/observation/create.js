document.addEventListener('DOMContentLoaded', function() {
    const formSelect = document.getElementById('form_id');
    const sectionSelect = document.getElementById('section_id');
    
    // Función para cargar las secciones según el formulario seleccionado
    function loadSections() {
        // Deshabilitar el selector de secciones por defecto
        sectionSelect.disabled = true;
        sectionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
        
        // Si no hay formulario seleccionado, no hacer nada más
        if (!formSelect.value) {
            return;
        }
        
        // Obtener el elemento option seleccionado
        const selectedOption = formSelect.options[formSelect.selectedIndex];
        
        // Obtener las secciones del atributo data-sections
        if (selectedOption && selectedOption.dataset.sections) {
            try {
                const sections = JSON.parse(selectedOption.dataset.sections);
                
                // Verificar si hay secciones disponibles
                if (sections && sections.length > 0) {
                    // Cargar las opciones de secciones
                    sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.name;
                        
                        // Si hay un valor antiguo, seleccionarlo
                        if (section.id == "{{ old('section_id') }}") {
                            option.selected = true;
                        }
                        
                        sectionSelect.appendChild(option);
                    });
                    
                    // Habilitar el selector de secciones
                    sectionSelect.disabled = false;
                } else {
                    // No hay secciones disponibles
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No hay secciones disponibles para este tipo de vehículo";
                    sectionSelect.appendChild(option);
                }
            } catch (error) {
                console.error("Error al parsear las secciones:", error);
            }
        }
    }
    
    // Cargar las secciones al cambiar el formulario
    formSelect.addEventListener('change', loadSections);
    
    // Cargar las secciones al iniciar la página (por si hay un valor preseleccionado)
    if (formSelect.value) {
        loadSections();
    }
});