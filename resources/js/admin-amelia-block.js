(function($) {
  if (typeof acf === 'undefined') {
    console.error('ACF object not found. Amelia block script will not run.');
    return;
  }

  /**
   * Funkcja inicjująca logikę dla pojedynczego bloku Amelia.
   * @param {jQuery} $block - Obiekt jQuery reprezentujący blok.
   */
  const initializeAmeliaBlock = ($block) => {
    // Sprawdź, czy blok nie został już zainicjowany
    if ($block.data('amelia-initialized')) {
      return;
    }
    $block.data('amelia-initialized', true);
    console.log('Initializing Amelia Block:', $block);

    const $employeeField = $block.find('.acf-field[data-name="amelia_employee"] select');
    const $serviceField = $block.find('.acf-field[data-name="amelia_service"] select');
    const $locationField = $block.find('.acf-field[data-name="amelia_location"] select');

    const updateSelect = ($select, choices, currentValue) => {
      const isSelect2 = $select.hasClass('select2-hidden-accessible');
      if (isSelect2) $select.select2('destroy');
      
      $select.empty();
      $.each(choices, (value, label) => {
        $select.append($('<option>', { value: value }).text(label));
      });
      $select.val(currentValue);

      if (isSelect2) $select.select2();
    };
    
    const updateFields = () => {
      // Zablokuj pola na czas ładowania
      $employeeField.prop('disabled', true);
      $serviceField.prop('disabled', true);
      $locationField.prop('disabled', true);

      const data = {
        action: 'get_amelia_acf_options',
        nonce: ameliaBlockAjax.nonce,
        employee_id: $employeeField.val() || 0,
        service_id: $serviceField.val() || 0,
        location_id: $locationField.val() || 0,
      };
      
      console.log('Sending AJAX data:', data);

      $.post(ameliaBlockAjax.ajax_url, data)
        .done(function(response) {
          if (response.success) {
            console.log('Received AJAX data:', response.data);
            const currentVals = {
                employee: $employeeField.val(),
                service: $serviceField.val(),
                location: $locationField.val(),
            };
            updateSelect($employeeField, response.data.employees, currentVals.employee);
            updateSelect($serviceField, response.data.services, currentVals.service);
            updateSelect($locationField, response.data.locations, currentVals.location);
          } else {
            console.error('AJAX request failed:', response);
          }
        })
        .fail(function(xhr) {
            console.error('AJAX call failed completely. Status:', xhr.status, 'Response:', xhr.responseText);
        })
        .always(function() {
            // Odblokuj pola po zakończeniu
            $employeeField.prop('disabled', false);
            $serviceField.prop('disabled', false);
            $locationField.prop('disabled', false);
        });
    };

    // Nasłuchuj na zmiany
    $employeeField.on('change', updateFields);
    $serviceField.on('change', updateFields);
    $locationField.on('change', updateFields);

    // Wywołaj raz na starcie
    updateFields();
  }

  // Użyj akcji 'ready', która jest bardziej globalna.
  // Sprawdzamy, czy nasz blok istnieje na stronie.
  acf.on('ready', function() {
    $('.acf-block-fields[data-block*="amelia-booking-header"]').each(function() {
      initializeAmeliaBlock($(this));
    });
  });

})(jQuery);