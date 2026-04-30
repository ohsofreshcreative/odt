@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';

$bgClass = [
'light' => ' section-light',
'gray' => ' section-gray',
'white' => ' section-white',
'brand' => ' section-brand',
'dark' => ' section-dark',
'' => '',
null => '',
];

$sectionClass .= $bgClass[$bg ?? ''] ?? '';
@endphp

<!--- wysiwyg -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="wysiwyg relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative __txt">
		@if (!empty($g_wysiwyg['header']))
		<h2 data-gsap-element="header" class="text-h4">{{ $g_wysiwyg['header'] }}</h2>
		@endif

		<div data-gsap-element="txt" class="mt-4">
			{!! $g_wysiwyg['txt'] !!}
		</div>

		@if (!empty($g_wysiwyg['button']))
		<x-button
			:href="$g_wysiwyg['button']['url']"
			variant="primary"
			class="mt-6"
			data-gsap-element="btn">
			{{ $g_wysiwyg['button']['title'] }}
		</x-button>
		@endif
	</div>

</section>