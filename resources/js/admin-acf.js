import $ from 'jquery';

/**
 * Inicjalizuje logikę dynamicznego filtrowania dla konkretnego bloku Amelia.
 * @param {jQuery} $block - Obiekt jQuery reprezentujący blok ACF.
 */
function initializeAmeliaBlock($block) {
  // Sprawdź, czy blok nie został już zainicjowany
  if ($block.data('amelia-initialized')) {
    return;
  }
  $block.data('amelia-initialized', true);

  const serviceField = $block.find('.acf-field[data-name="amelia_service"] select');
  const employeeField = $block.find('.acf-field[data-name="amelia_employee"] select');
  const locationField = $block.find('.acf-field[data-name="amelia_location"] select');

  // Funkcja do aktualizacji pól select z nowymi opcjami
  const updateSelectField = (field, choices, currentValue, defaultText) => {
    if (!field.length) return;

    field.empty(); // Wyczyść stare opcje
    field.append($('<option>', { value: '' }).text(defaultText));

    choices.forEach(choice => {
      const name = choice.name || `${choice.firstName || ''} ${choice.lastName || ''}`.trim();
      field.append($('<option>', { value: choice.id, text: name }));
    });

    // Ustaw aktualną (lub poprzednią) wartość, jeśli nadal jest dostępna
    field.val(currentValue).trigger('change');
  };

  // Główna funkcja pobierająca dane z serwera
  const fetchData = (sourceField = null) => {
    // Przygotuj dane do wysłania
    const data = {
      action: 'filter_amelia_fields',
      _ajax_nonce: acf_ajax.nonce, // acf_ajax jest zdefiniowane przez wp_localize_script w PHP
      employee_id: employeeField.val(),
      service_id: serviceField.val(),
      location_id: locationField.val(),
    };

    // Jeśli zmiana pochodzi od użytkownika, zresetuj pola podrzędne
    if (sourceField) {
        if (sourceField.is(employeeField)) {
            data.service_id = '';
            serviceField.val('');
        }
        if (sourceField.is(serviceField)) {
            data.employee_id = '';
            employeeField.val('');
        }
    }

    // Wyłącz pola, aby użytkownik nie mógł ich zmienić podczas ładowania
    serviceField.prop('disabled', true);
    employeeField.prop('disabled', true);
    locationField.prop('disabled', true);

    $.post(acf_ajax.url, data, function(response) {
      if (response.success) {
        // Zaktualizuj opcje w polach, zachowując wybrane wartości
        updateSelectField(employeeField, response.data.employees, data.employee_id, 'Dowolny pracownik');
        updateSelectField(serviceField, response.data.services, data.service_id, 'Dowolna usługa');
        updateSelectField(locationField, response.data.locations, data.location_id, 'Dowolna lokalizacja');
      }
    }).always(function() {
      // Włącz pola z powrotem po zakończeniu ładowania
      serviceField.prop('disabled', false);
      employeeField.prop('disabled', false);
      locationField.prop('disabled', false);
    });
  };

  // Nasłuchuj na zmiany w polach
  employeeField.on('change', () => fetchData(employeeField));
  serviceField.on('change', () => fetchData(serviceField));
  locationField.on('change', () => fetchData(locationField));

  // Pobierz dane przy pierwszym załadowaniu
  fetchData();
}

/**
 * Akcja ACF, która uruchamia naszą logikę, gdy blok jest gotowy w edytorze.
 * Używamy 'append_block', ponieważ jest to bardziej niezawodne dla bloków.
 */
if (window.acf) {
  window.acf.addAction('append_block', function(el) {
    // Sprawdź, czy to nasz blok
    const $block = $(el);
    if ($block.find('.acf-field[data-name="amelia_employee"]').length) {
      initializeAmeliaBlock($block);
    }
  });
}