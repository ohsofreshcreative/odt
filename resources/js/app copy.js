/**
 * Logika dynamicznego filtrowania pól ACF dla bloku Amelia Booking Header.
 */
function initializeAmeliaBlock($block) {
  const serviceField = $block.find('.acf-field[data-name="amelia_service"] select');
  const employeeField = $block.find('.acf-field[data-name="amelia_employee"] select');
  const locationField = $block.find('.acf-field[data-name="amelia_location"] select');

  // Funkcja do aktualizacji pól select
  const updateSelectField = (field, choices, currentValue, defaultText) => {
    if (!field.length) return;

    const originalValue = field.val();
    field.empty(); // Wyczyść opcje

    field.append($('<option>', { value: '' }).text(defaultText));

    choices.forEach(choice => {
      const name = choice.name || `${choice.firstName || ''} ${choice.lastName || ''}`.trim();
      field.append($('<option>', {
        value: choice.id,
        text: name
      }));
    });

    // Spróbuj ustawić poprzednio wybraną wartość, jeśli nadal istnieje
    if (choices.some(c => c.id == originalValue)) {
      field.val(originalValue);
    } else {
      field.val(''); // Zresetuj, jeśli poprzednia wartość jest już nieprawidłowa
    }
    field.trigger('change'); // Wymuś odświeżenie w ACF
  };

  // Funkcja do wysyłania zapytania AJAX
  const fetchData = () => {
    const data = {
      action: 'filter_amelia_fields',
      _ajax_nonce: acf_ajax.nonce,
      employee_id: employeeField.val(),
      service_id: serviceField.val(),
      location_id: locationField.val(),
    };

    $.post(acf_ajax.url, data, function(response) {
      if (response.success) {
        updateSelectField(employeeField, response.data.employees, data.employee_id, 'Dowolny pracownik');
        updateSelectField(serviceField, response.data.services, data.service_id, 'Dowolna usługa');
        updateSelectField(locationField, response.data.locations, data.location_id, 'Dowolna lokalizacja');
      }
    });
  };

  // Nasłuchuj na zmiany w polach
  employeeField.on('change', fetchData);
  serviceField.on('change', fetchData);
  locationField.on('change', fetchData);

  // Pobierz dane przy pierwszym załadowaniu bloku
  fetchData();
}

// Uruchom logikę dla każdego bloku Amelia na stronie edycji
acf.addAction('ready_field/name=amelia_service', function($field) {
    const $block = $field.closest('.acf-block-fields');
    if ($block.data('amelia-initialized')) return;
    $block.data('amelia-initialized', true);
    initializeAmeliaBlock($block);
});