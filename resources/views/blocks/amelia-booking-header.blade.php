@php
  // Logika dla klas sekcji - dokładnie jak w Twoim przykładzie
  $sectionClass = '';
  if (!empty($background) && $background !== 'none') {
      $sectionClass .= ' ' . $background;
  }
  if (!empty($section_class)) {
      $sectionClass .= ' ' . $section_class;
  }

  // Przygotowanie danych do wyświetlenia w nagłówku
  $title = !empty($amelia_data['service_name']) ? $amelia_data['service_name'] : $amelia_data['location_name'];
  
  $meta_parts = [];
  if (!empty($amelia_data['employee_name'])) {
      $meta_parts[] = '<span class="amelia-booking-header__employee">' . esc_html($amelia_data['employee_name']) . '</span>';
  }
  if (!empty($amelia_data['service_name']) && !empty($amelia_data['location_name'])) {
      $meta_parts[] = '<span class="amelia-booking-header__location">' . esc_html($amelia_data['location_name']) . '</span>';
  }

  // Budowanie atrybutów dla shortcode'u [ameliabooking]
  $booking_attrs = '';
  if ($service_id) $booking_attrs .= ' service="' . $service_id . '"';
  if ($employee_id) $booking_attrs .= ' employee="' . $employee_id . '"';
  if ($location_id) $booking_attrs .= ' location="' . $location_id . '"';
@endphp

{{-- Główny kontener bloku, używa Twojej struktury --}}
<section 
  @if(!empty($section_id)) id="{{ $section_id }}" @endif 
  class="b-amelia-booking relative {{ $sectionClass }}"
>
  <div class="__wrapper c-main relative">
    
    {{-- Nagłówek rezerwacji --}}
    @if(!empty($title) || !empty($amelia_data['employee_name']))
      <div class="amelia-booking-header mb-8">
        <div class="flex items-center gap-4">
          @if ($show_photo && !empty($amelia_data['employee_photo']))
            <img 
              class="amelia-booking-header__photo rounded-full object-cover" 
              src="{{ esc_url($amelia_data['employee_photo']) }}" 
              alt="{{ esc_attr($amelia_data['employee_name']) }}" 
              width="56" height="56"
              loading="lazy"
            />
          @endif

          <div class="amelia-booking-header__text">
            @if (!empty($title))
              <h3 class="amelia-booking-header__title text-2xl font-bold">{{ esc_html($title) }}</h3>
            @endif
            @if (!empty($meta_parts))
              <div class="amelia-booking-header__meta text-gray-600 mt-1">
                {!! implode('<span class="mx-2">•</span>', $meta_parts) !!}
              </div>
            @endif
          </div>
        </div>
      </div>
    @endif

    {{-- Formularz rezerwacji Amelia --}}
    <div class="amelia-booking-container mt-6">
      {!! do_shortcode('[ameliabooking ' . $booking_attrs . ']') !!}
    </div>

  </div>
</section>