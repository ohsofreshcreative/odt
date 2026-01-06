@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $nolist ? ' no-list' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';

if (!empty($background) && $background !== 'none') {
$sectionClass .= ' ' . $background;
}
@endphp

<!--- member -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-member relative -spt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative z-20">
		<div class="__col grid grid-cols-1 lg:grid-cols-[1.5fr_2fr] gap-20 mt-16">
			<div class="__col">
				@if (!empty($g_member['image']))
				<div data-gsap-element="img" class="__img h-max order1">
					<img class="object-cover w-full img-3xl __img radius-img" src="{{ $g_member['image']['url'] }}" alt="{{ $g_member['image']['alt'] ?? '' }}">
				</div>
				@endif

				<div class="flex flex-wrap gap-6 mt-8">
					@php
					$terms = get_the_terms( get_the_ID(), 'category' ); // Pobieramy kategorie dla bieżącego posta
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
					// Pomiń kategorię o slugu 'specjalisci'
					if ( $term->slug === 'specjalisci' ) {
					continue;
					}
					$term_link = get_term_link( $term ); // Pobierz link do kategorii
					echo '<a href="' . esc_url( $term_link ) . '" class="font-header !text-secondary text-h7 !underline">' . esc_html( $term->name ) . '</a>';
					}
					}
					@endphp
				</div>

				@if (!empty($g_member['button']))
				<a data-gsap-element="btn" class="second-btn mt-6" href="{{ $g_member['button']['url'] }}">{{ $g_member['button']['title'] }}</a>
				@endif
			</div>

			<div class="__member order2">
				<h2 data-gsap-element="header" class="text-secondary">{{ $g_member['title'] }}</h2>

				<div data-gsap-element="txt" class="__txt text-white mt-10">
					{!! $g_member['txt'] !!}
				</div>

				<div data-gsap-element="accordion" class="accordion-wrapper grid mt-10">
					@foreach ($r_member as $item)
					<div class="accordion rounded-2xl bg-white border border-secondary">
						<input class="acc-check" type="checkbox" name="radio-a" id="check{{ $loop->index }}">
						<label class="accordion-label flex items-center justify-between" for="check{{ $loop->index }}">
							<div class="flex items-center gap-4">
								<img src="{{ $item['image']['url'] ?? '' }}" alt="{{ $item['image']['alt'] ?? '' }}" />
								<h6 class="text-h7">{{ $item['title'] }}</h6>
							</div>
							<x-icon.arrow-up class="__arrow text-secondary w-4 h-5" />
						</label>
						<div class="accordion-content">
							<p>{!! $item['txt'] !!}</p>
						</div>
					</div>
					@endforeach
				</div>

			</div>

		</div>
	</div>

</section>