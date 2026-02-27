<footer class="footer bg-white overflow-hidden relative z-10">
	<div class="__wrapper c-main relative z-10">
		<div class="__top flex flex-col md:flex-row justify-between gap-6 mt-20">
			<img src="{{ $logo['url'] }}" alt="{{ $logo['alt'] ?? 'Logo' }}" class="relative w-auto max-w-46">

			@php($places_footer = get_field('g_places_footer', 'option'))
			@if (!empty($places_footer))
			<div class="flex flex-wrap flex-col sm:flex-row gap-6">
				@foreach ($places_footer as $place)
				<div class="__item radius flex flex-col md:flex-row gap-4 p-6">
					<div>
						@if (!empty($place['title']))
						<b class="block text-lg">{{ $place['title'] }}</b>
						@endif
						@if (!empty($place['address']))
						<p class="">{{ $place['address'] }}</p>
						@endif
					</div>

					@if (!empty($place['button']))
					<a
						href="{{ $place['button']['url'] }}"
						target="{{ $place['button']['target'] ?? '_self' }}"
						class="flex items-center justify-center rounded-full px-3 py-2">
						{{ $place['button']['title'] }}
					</a>
					@endif
				</div>
				@endforeach
			</div>
			@endif
		</div>

		<div class="__widgets border-t border-primary-lighter grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 md:gap-6 pt-10 pb-36 mt-12">
			@for ($i = 1; $i <= 4; $i++)
				@if (is_active_sidebar('sidebar-footer-' . $i))
				<div>@php(dynamic_sidebar('sidebar-footer-' . $i))</div>
		@endif
		@endfor
	</div>

	<img class="__bg absolute -right-20 -bottom-60 opacity-50 scale-120 pointer-events-none" src="/wp-content/uploads/2026/01/plant-footer.svg" />
	</div>

	<div class="c-main flex flex-col md:flex-row justify-between gap-6 py-10 footer-bottom border-t border-primary-lighter">
		<p class="">Copyright ©2025 <?php echo get_bloginfo('name'); ?>. All Rights Reserved</p>
		<p class="flex gap-2">Designed &amp; Developed by
			<a target="_blank" href="https://www.ohsofresh.pl" title="OhSoFresh"><img class="oh" src="/wp-content/themes/odt/resources/images/ohsofresh.svg"></a>
		</p>
	</div>

	<img class="__bg absolute top-0 left-0 opacity-5 pointer-events-none" src="/wp-content/uploads/2026/01/shape-footer.svg" />
	</div>
</footer>