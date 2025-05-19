document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section_id');
    const vehicleTypeSections = document.getElementById('vehicle_type_sections');
    
    // Obtener el ID de la sección actualmente seleccionada
    const currentSectionId = sectionSelect.getAttribute('data-current') || '';
    
    // Cargar las secciones del tipo de vehículo
    if (vehicleTypeSections && vehicleTypeSections.value) {
        const sections = JSON.parse(vehicleTypeSections.value);
        
        // Limpiar y cargar las opciones
        sectionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
        
        sections.forEach(section => {
            const selected = section.id == currentSectionId ? 'selected' : '';
            sectionSelect.innerHTML += `<option value="${section.id}" ${selected}>${section.name}</option>`;
        });
    }
});