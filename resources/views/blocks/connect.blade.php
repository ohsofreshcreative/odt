@php
$sectionClass = '';
$sectionClass .= $nomt ? ' !mt-0' : '';
@endphp

<!-- connect -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-connect relative overflow-hidden -smt bg-primary-700 {{ $sectionClass }} {{ $section_class }}">
	<div class="grid grid-cols-1 md:grid-cols-2 items-center">

		<div class="__content relative z-10 w-11/12 md:w-3/4 lg:w-2/3 py-20 m-auto">
			<div data-gsap-element="txt" class="text-secondary">
				{!! $bottom['txt'] !!}
			</div>
			<h4 data-gsap-element="header" class="text-white mt-2">{{ $bottom['header'] }}</h4>

			@if (!empty($bottom['button']))
			<div class="inline-buttons m-btn">
				<a data-gsap-element="button" class="second-btn left-btn"
					href="{{ $bottom['button']['url'] }}"
					target="{{ $bottom['button']['target'] }}">
					{{ $bottom['button']['title'] }}
				</a>
				@if (!empty($bottom['button2']))
				<a data-gsap-element="button" class="white-btn"
					href="{{ $bottom['button2']['url'] }}"
					target="{{ $bottom['button2']['target'] }}">
					{{ $bottom['button2']['title'] }}
				</a>
				@endif
			</div>
			@endif

		</div>

		<div data-gsap-element="img" class="__img inset-y-0 h-full">

			<img class="__bg absolute w-full lg:hidden top-0 left-0 pointer-events-none" src="/wp-content/uploads/2026/01/connect-bg-top.svg" />
			<img class="__bg absolute max-lg:hidden top-1/2 -translate-y-1/2 left-0 pointer-events-none" src="/wp-content/uploads/2026/01/connect-bg.svg" />
			<img src="{{ $bottom['image']['url'] }}" alt="{{ $bottom['image']['alt'] }}" class="w-full h-full object-cover object-center" />
		</div>

		<img class="__bg absolute left-1/2 -translate-x-1/2 -bottom-40 w-[400px] pointer-events-none" src="/wp-content/uploads/2026/01/leaf.svg" />

	</div>
</section>