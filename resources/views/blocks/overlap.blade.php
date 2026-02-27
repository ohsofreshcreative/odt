<!--- overlap --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-overlap relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative z-10">
		<div class="__content order2">
			<div class="__txt w-full md:w-1/2 mx-auto">
				<h2 data-gsap-element="header" class="text-center m-header">{{ $g_overlap['title'] }}</h2>

				<div data-gsap-element="header" class="text-center">
					{!! $g_overlap['content'] !!}
				</div>
			</div>

			<div class="grid grid-cols-1 gap-8 mt-14">
				@foreach ($r_overlap as $item)
				<div class="gsap__cards __cards sticky top-20 mt-4">
					<div data-gsap-element="card" class="gsap__card __card b-border p-8 rounded-4xl" style="background-image:url({{ $item['r_image']['url'] }}); background-size: cover; background-position: center;">
						<div class="__box bg-white rounded-3xl w-full md:w-1/2 p-6 md:p-10 mt-80 mb-0 md:mb-10 mx-0 md:mx-20">
							<h5 class="secondary !text-[20px] md:text-h5">{{ $item['r_header'] }}</h5>
							<div class="">{!! $item['r_txt'] !!}</div>
							@if (!empty($item['button']))
							<x-button
								:href="$item['button']['url']"
								variant="secondary-small"
								class="mt-6"
								data-gsap-element="btn">
								{{ $item['button']['title'] }}
							</x-button>
							@endif
						</div>
					</div>
				</div>
				@endforeach
			</div>


		</div>
	</div>

	<img data-gsap-element="bg" class="__bg absolute w-[400px] -left-60 top-32 pointer-events-none" src="/wp-content/uploads/2025/12/sign_small.svg" />
</section>