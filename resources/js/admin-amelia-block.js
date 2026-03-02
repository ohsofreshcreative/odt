(function($) {
  if (typeof acf === 'undefined') {
    return;
  }

  /**
   * Funkcja inicjująca logikę dla pojedynczego bloku Amelia.
   * @param {jQuery} $block - Obiekt jQuery reprezentujący blok.
   */
  function initializeAmeliaBlock($block) {
    // Sprawdź, czy blok nie został już zainicjowany
    if ($block.data('amelia-initialized')) {
      return;
    }
    $block.data('amelia-initialized', true);
    
    const $employeeField = $block.find('.acf-field[data-name="amelia_employee"] select');
    const $serviceField = $block.find('.acf-field[data-name="amelia_service"] select');
    const $locationField = $block.find('.acf-field[data-name="amelia_location"] select');

    const updateSelect = ($select, choices, currentValue) => {
      const isSelect2 = $select.hasClass('select2-hidden-accessible');

      // Zapisz wartość, wyczyść, wypełnij, przywróć wartość
      $select.empty();
      $.each(choices, (value, label) => {
        $select.append($('<option>', { value: value }).text(label));
      });
      $select.val(currentValue);

      // Jeśli to było pole Select2, odśwież je
      if (isSelect2) {
        $select.trigger('change');
      }
    };
    
    const updateFields = () => {
      const data = {
        action: 'get_amelia_acf_options',
        nonce: ameliaBlockAjax.nonce, // Użyj naszego nonce
        employee_id: $employeeField.val(),
        service_id: $serviceField.val(),
        location_id: $locationField.val(),
      };

      $.post(ameliaBlockAjax.ajax_url, data, function(response) {
        if (response.success) {
          // Zapisz aktualne wartości, zanim je nadpiszemy
          const currentEmployee = $employeeField.val();
          const currentService = $serviceField.val();
          const currentLocation = $locationField.val();

          // Zaktualizuj wszystkie pola nowymi opcjami
          updateSelect($employeeField, response.data.employees, currentEmployee);
          updateSelect($serviceField, response.data.services, currentService);
          updateSelect($locationField, response.data.locations, currentLocation);
        }
      });
    };

    // Nasłuchuj na zmiany w polach
    $employeeField.on('change', updateFields);
    $serviceField.on('change', updateFields);
    $locationField.on('change', updateFields);

    // Wywołaj raz na starcie, aby wypełnić pola
    updateFields();
  }

  // Użyj akcji 'append' od ACF, która działa dla nowo dodanych bloków i tych już istniejących
  acf.addAction('append_field_object', function(field) {
    const $block = field.$el.closest('.acf-block-fields[data-block*="amelia-booking-header"]');
    if ($block.length) {
      initializeAmeliaBlock($block);
    }
  });

})(jQuery);