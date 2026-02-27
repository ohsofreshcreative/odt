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

<!--- slider --->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-slider relative -smt {{ $sectionClass }} {{ $section_class }}">

    @if(!empty($g_slider['title']))
    <div class="__wrapper c-main block relative z-20">
        <h2 class="text-center">{{ $g_slider['title']}}</h2>
    </div>
    @endif

     <div class="swiper slider-coverflow relative z-20 mt-6">
        <div class="swiper-wrapper">
            @foreach($slider as $slide)
            <div class="swiper-slide" @if(!empty($slide['image'])) style="background-image:url({{ $slide['image']['url'] }})" @endif>
                <div class="info">
                    @if(!empty($slide['icon']))
                        <div class="icon">
                            {!! wp_get_attachment_image($slide['icon']['ID'], 'thumbnail') !!}
                        </div>
                    @endif
                     @if(!empty($slide['header']))
                        <h6 class="__header text-white">{{ $slide['header'] }}</h6>
                    @endif
                     @if(!empty($slide['opis']))
                        <div class="__txt text-white text-[15px] mt-2">{{ $slide['opis'] }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
	<img class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 opacity-10 h-[110%] hue-rotate-220" src="/wp-content/uploads/2026/02/logo-bg.svg" />
</section>