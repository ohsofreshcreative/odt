/**
 * Logika dla dynamicznego filtrowania pól w bloku ACF Amelia.
 */
(function($) {
  if (typeof acf === 'undefined') {
    return;
  }

  /**
   * Funkcja do aktualizacji opcji w polu select (wybierz).
   * @param {Object} $field - Obiekt jQuery pola select.
   * @param {Object} choices - Nowe opcje w formacie { 'wartość': 'etykieta' }.
   * @param {string} currentValue - Aktualnie wybrana wartość, aby spróbować ją zachować.
   */
  const updateSelectField = ($field, choices, currentValue) => {
    const select2 = $field.data('select2');
    const wasInitialized = !!select2;

    $field.empty();

    if (wasInitialized) {
      // Jeśli pole używało Select2, musimy je tymczasowo "zniszczyć"
      select2.destroy();
    }
    
    if (!choices || Object.keys(choices).length === 0) {
      // Brak opcji, można dodać jakąś informację
      $field.append($('<option>', { value: '' }).text('Brak dostępnych opcji'));
    } else {
      $.each(choices, (value, label) => {
        $field.append($('<option>', { value: value }).text(label));
      });
    }

    // Ustaw wybraną wartość, jeśli nadal istnieje w nowych opcjach
    $field.val(currentValue).trigger('change.select2');
    
    if (wasInitialized) {
        // Ponowna inicjalizacja Select2
        $field.select2();
    }
  };


  /**
   * Główna funkcja wywoływana, gdy którekolwiek z pól Amelii jest gotowe.
   * Używamy acf.on() aby obsłużyć bloki dodawane dynamicznie.
   */
  acf.on('ready', '.acf-block-fields[data-block="amelia-booking-header"]', function() {
    const $block = $(this);
    
    // Znajdź nasze pola w obrębie bieżącego bloku
    const $employeeField = $block.find('.acf-field[data-name="amelia_employee"] select');
    const $serviceField = $block.find('.acf-field[data-name="amelia_service"] select');
    const $locationField = $block.find('.acf-field[data-name="amelia_location"] select');
    
    const fieldsToWatch = {
      'amelia_employee': $employeeField,
      'amelia_service': $serviceField,
      'amelia_location': $locationField,
    };

    // Nasłuchuj na zmiany w każdym z tych pól
    $.each(fieldsToWatch, (sourceName, $sourceField) => {
      $sourceField.on('change', function() {
        // Nie rób nic, jeśli pole nie ma wartości (wybrano 'Dowolny...')
        if (!$(this).val()) {
          // W przyszłości można tu dodać logikę resetowania pól do stanu początkowego
          return;
        }

        const data = {
          action: 'get_amelia_acf_options', // Nasza akcja AJAX z PHP
          nonce: acf.get('nonce'),
          employee_id: $employeeField.val(),
          service_id: $serviceField.val(),
          location_id: $locationField.val(),
          source: sourceName, // Które pole wywoła��o zmianę
        };

        // Wykonaj zapytanie AJAX
        $.post(ameliaBlockAjax.ajax_url, data, function(response) {
          if (response.success) {
            const currentValues = {
                employee: $employeeField.val(),
                service: $serviceField.val(),
                location: $locationField.val(),
            };

            // Aktualizuj opcje w polach, które nie były źródłem zmiany
            if (response.data.employees && sourceName !== 'amelia_employee') {
              updateSelectField($employeeField, response.data.employees, currentValues.employee);
            }
            if (response.data.services && sourceName !== 'amelia_service') {
              updateSelectField($serviceField, response.data.services, currentValues.service);
            }
            if (response.data.locations && sourceName !== 'amelia_location') {
              updateSelectField($locationField, response.data.locations, currentValues.location);
            }
          }
        });
      });
    });
  });

})(jQuery);