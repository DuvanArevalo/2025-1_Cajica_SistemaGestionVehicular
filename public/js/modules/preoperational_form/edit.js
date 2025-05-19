document.addEventListener('DOMContentLoaded', function() {
    // Obtener referencias a los elementos del DOM
    const vehicleTypeSelect = document.getElementById('vehicle_type_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    const lastMileageDisplay = document.getElementById('last_mileage_display');
    const newMileageInput = document.getElementById('new_mileage');
    const submitBtn = document.getElementById('submitBtn');
    
    // Almacenar todas las opciones de vehículos originales para filtrar después
    const originalVehicleOptions = Array.from(vehicleSelect.options);
    
    // Filtrar vehículos según el tipo seleccionado (que ahora está fijo)
    const selectedTypeId = vehicleTypeSelect.value;
    filterVehiclesByType(selectedTypeId);
    
    // Función para actualizar el kilometraje mostrado cuando cambia el vehículo
    function updateMileageDisplay() {
        const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.mileage) {
            lastMileageDisplay.textContent = selectedOption.dataset.mileage + ' km';
        } else {
            lastMileageDisplay.textContent = '0 km';
        }
        
        // Validar el kilometraje si ya hay un valor ingresado
        if (newMileageInput.value) {
            validateMileage();
        }
    }
    
    // Función para filtrar vehículos según el tipo seleccionado
    function filterVehiclesByType(selectedTypeId) {
        // Limpiar el select de vehículos
        vehicleSelect.innerHTML = '';
        
        // Agregar la opción por defecto
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccione un vehículo';
        vehicleSelect.appendChild(defaultOption);
        
        // Filtrar y agregar solo los vehículos del tipo seleccionado
        if (selectedTypeId) {
            originalVehicleOptions.forEach(option => {
                if (option.value !== '' && option.dataset.type === selectedTypeId) {
                    vehicleSelect.appendChild(option.cloneNode(true));
                }
            });
            
            // Habilitar el select de vehículos
            vehicleSelect.disabled = false;
        } else {
            // Si no hay tipo seleccionado, deshabilitar el select de vehículos
            vehicleSelect.disabled = true;
        }
    }
    
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
            submitBtn.disabled = false;
            return true;
        }
    }
    
    // Función para obtener el último kilometraje
    function getLastMileage() {
        const mileageText = lastMileageDisplay.textContent;
        if (mileageText === 'Seleccione un vehículo' || mileageText === '0 km') return 0;
        return parseInt(mileageText.replace(/[^\d]/g, '')) || 0;
    }
    
    // Función para actualizar el display de kilometraje cuando cambia el vehículo
    vehicleSelect.addEventListener('change', function() {
        if (this.value) {
            updateMileageDisplay();
        }
    });
    
    // Evento al cambiar el valor del kilometraje
    newMileageInput.addEventListener('input', validateMileage);
    
    // Validar antes de enviar el formulario
    document.getElementById('preoperationalForm').addEventListener('submit', function(event) {
        if (!validateMileage()) {
            event.preventDefault();
            alert('El nuevo kilometraje debe ser mayor o igual que el último registrado.');
            return false;
        }
        
        if (newMileageInput.value === '' || parseInt(newMileageInput.value) === 0) {
            event.preventDefault();
            alert('Por favor, ingrese un kilometraje válido.');
            return false;
        }
        
        return true;
    });
    
    // Inicializar el display de kilometraje al cargar la página
    updateMileageDisplay();
    
    // Validar el kilometraje inicial
    validateMileage();
});