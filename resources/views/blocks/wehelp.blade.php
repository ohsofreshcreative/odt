<!-- wehelp -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-wehelp bg-gradient overflow-hidden relative' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid grid-cols-1 lg:grid-cols-[2.5fr_1fr] gap-8 items-center relative z-20 pt-16">
		<div class="__content relative z-20 pt-20 pb-30">

			<h1 data-gsap-element="header" class="text-white bg-bg-brand">
				{{ $g_wehelp['header'] }}
			</h1>
			<div data-gsap-element="txt" class="text-white mt-4 w-full md:w-2/3">
				{!! $g_wehelp['txt'] !!}
			</div>
			@if (!empty($g_wehelp['button1']))
			<div class="inline-buttons m-btn">
				<a data-gsap-element="button" class="second-btn left-btn"
					href="{{ $g_wehelp['button1']['url'] }}"
					target="{{ $g_wehelp['button1']['target'] }}">
					{{ $g_wehelp['button1']['title'] }}
				</a>
				@if (!empty($g_wehelp['button2']))
				<a data-gsap-element="button" class="white-btn"
					href="{{ $g_wehelp['button2']['url'] }}"
					target="{{ $g_wehelp['button2']['target'] }}">
					{{ $g_wehelp['button2']['title'] }}
				</a>
				@endif
			</div>
			@endif
		</div>

		@if ($g_wehelp['image'])
		<div class="">
			<img data-gsap-element="image" class="absolute right-20 -bottom-20 max-w-[440px]" src="{{ $g_wehelp['image']['url'] }}" />
		</div>
		@endif
	</div>

	<img data-gsap-element="image" class="absolute top-0 -right-20 h-full" src="/wp-content/uploads/2026/01/help-hero-bg.svg" />
</section>

<section data-gsap-anim="section" class="mt-0 md:-mt-16">
	<div class="__list c-main grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 section-gap">
		@if($support)
		@foreach ($support as $sector)
		<a href="{{ get_permalink($sector->ID) }}" class="">
			<div data-gsap-element="item" class="__card bg-white radius p-6">
				@if (has_post_thumbnail($sector->ID))
				<img class="w-20 aspect-square radius" src="{{ get_the_post_thumbnail_url($sector->ID, 'large') }}" alt="{{ $sector->post_title }}">
				@endif
				<h6 class="!text-primary text-h7 mt-4">{{ $sector->post_title }}</h6>
				<p class="!text-body text-sm mt-2">{{ $sector->post_excerpt }}</p>

				<p class="underline-btn mt-4">
					Dowiedz się więcej
				</p>
			</div>
		</a>
		@endforeach
		@endif
	</div>
</section>