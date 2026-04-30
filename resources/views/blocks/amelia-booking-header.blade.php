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
$title = !empty($amelia_data['service_name']) ? $amelia_data['service_name'] : (!empty($amelia_data['employee_name']) ? $amelia_data['employee_name'] : $amelia_data['location_name']);

$meta_parts = [];
if ($title !== $amelia_data['employee_name'] && !empty($amelia_data['employee_name'])) {
    $meta_parts[] = esc_html($amelia_data['employee_name']);
}
if ($title !== $amelia_data['location_name'] && !empty($amelia_data['location_name'])) {
    $meta_parts[] = esc_html($amelia_data['location_name']);
}


// Budowanie atrybutów dla shortcode'u [ameliabooking]
$booking_attrs = '';
if ($service_id) $booking_attrs .= ' service="' . $service_id . '"';
if ($employee_id) $booking_attrs .= ' employee="' . $employee_id . '"';
if ($location_id) $booking_attrs .= ' location="' . $location_id . '"';
@endphp

<!--- amelia-booking-header --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-amelia-booking relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">

		@if(!empty($title) || !empty($amelia_data['employee_name']))
		<div class="amelia-booking-header mb-8">
			<div class="flex items-center gap-4">
				@if ($show_photo && !empty($amelia_data['employee_photo']))
				<img
					class="amelia-booking-header__photo rounded-full object-cover h-20"
					src="{{ esc_url($amelia_data['employee_photo']) }}"
					alt="{{ esc_attr($amelia_data['employee_name']) }}"
					width="80" height="80"
					loading="lazy" />

				@endif
				<div class="amelia-booking-header__text">
				<h1 class="text-h6 text-primary mb-4">Umów wizytę</h1>
					@if (!empty($title))
					<h3 class="amelia-booking-header__title text-2xl font-bold">{{ esc_html($title) }}</h3>
					@endif
					@if (!empty($meta_parts))
					<h5 class="text-primary/70">
						{!! implode('<span class="mx-2">•</span>', $meta_parts) !!}
					</h5>
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