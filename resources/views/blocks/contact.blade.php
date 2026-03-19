<!--- contact --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-contact  relative pt-30 pb-30' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative z-2 py-16">

		<div class="relative grid grid-cols-1 lg:grid-cols-2 items-center gap-10 z-10">
			<div class="__content w-full lg:w-11/12 flex flex-col justify-between">
				<h2 data-gsap-element="header" class="">{!! $g_contact_1['header'] !!}</h2>
				<a data-gsap-element="txt" class="__phone flex items-center text-white w-max mt-4" href="tel:{{ $g_contact_1['phone'] }}">{{ $g_contact_1['phone'] }}</a>
				<div class="border-t border-dashed border-action pt-4 mt-4">
					<a data-gsap-element="txt" class="__mail flex items-center text-white w-max" href="mailto:{{ $g_contact_1['mail'] }}">{{ $g_contact_1['mail'] }}</a>
				</div>
				<div class="text-white flex flex-col md:flex-row border-t border-dashed border-action gap-6 pt-4 mt-4">
					<div data-gsap-element="txt" class="__address flex">
						{!! ($g_contact_1['address1']) !!}
					</div>
					<div data-gsap-element="txt" class="__address flex">
						{!! ($g_contact_1['address2']) !!}
					</div>
				</div>
				<div data-gsap-element="txt" class="__hours text-white flex border-t border-dashed border-action pt-4 mt-4">
					{!! ($g_contact_1['hours']) !!}
				</div>
			</div>

			<div data-gsap-element="form" class="bg-white radius p-10">
			<h4 class="!text-primary mb-4">{!! $g_contact_2['title'] !!}</h4>
				{!! do_shortcode($g_contact_2['shortcode']) !!}
			</div>
		</div>
	</div>

	<div class="c-main">
		<div class="grid grid-cols-1 lg:grid-cols-2 items-start gap-10 z-10">
			<ul>
				<li>Wizytę można umówić mailowo, telefonicznie, przez stronę internetową lub portal Znany Lekarz</li>
				<li>Nie umawiamy wizyt przez portale społecznościowe</li>
				<li>Rejestracja nie pracuje w dni wolne od pracy oraz weekendy i nie odwołuje i nie przekłada wtedy wizyt</li>
				<li>Rejestracja oraz specjalista ma prawo zakończyć rozmowę jeśli klient komunikuje się w sposób agresywny, obraźliwie lub wykraczający w inny sposób poza zdroworozsądkowo przyjęte zasady kultury.</li>
			</ul>
			<div class="text-white">
				<b>Dane do przelewu</b>
				<br><br>
				Osrodek Dobrej Terapii<br>
				ul. Kazimierza Jagiellonczyka 8<br>
				50-240 Wroclaw
				<br><br>
				ING 61 1050 1575 1000 0092 9742 3718
				<br><br>
				NIP: 6112767709<br>
				REGON: 365020557
			</div>
		</div>
	</div>

	<img class="absolute top-1/2 -translate-x-1/2 left-1/2 -translate-y-1/2" src="{{ $g_contact_1['image']['url'] }}" alt="{{ $g_contact_1['image']['alt'] ?? '' }}">
</section>