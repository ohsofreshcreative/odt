<!--- content -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-content relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">
		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			@if (!empty($g_content['image']))
			<div data-gsap-element="img" class="__img h-full order1">
				<img class="object-cover w-full h-full aspect-square __img radius-img" src="{{ $g_content['image']['url'] }}" alt="{{ $g_content['image']['alt'] ?? '' }}">
			</div>
			@endif

			<div class="__content order2 lg:py-10">
				<h4 data-gsap-element="header" class="">{{ $g_content['header'] }}</h4>

				<div data-gsap-element="txt" class="__txt mt-4">
					{!! $g_content['txt'] !!}
				</div>

				@if (!empty($g_content['hint']))
				<div data-gsap-element="box" class="__hint flex items-center radius bg-primary-lighter border border-dashed border-primary p-6 gap-4 mt-6">
					@if (!empty($g_content['image_hint']['url']))
					<img
						class="max-w-10 aspect-square"
						src="{{ $g_content['image_hint']['url'] }}"
						alt="{{ $g_content['image_hint']['alt'] ?? '' }}">
					@endif

					@if (!empty($g_content['header_hint']))
					<div class="">
						{{ $g_content['header_hint'] }}
					</div>
					@endif
				</div>
				@endif

				@if (!empty($g_content['button']))
				<x-button
					:href="$g_content['button']['url']"
					variant="primary"
					class="mt-6"
					data-gsap-element="btn">
					{{ $g_content['button']['title'] }}
				</x-button>
				@endif

			</div>

		</div>
	</div>

</section>