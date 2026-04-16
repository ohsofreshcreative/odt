@extends('layouts.app')

@section('content')

@php
$term = get_queried_object();
$categories = get_categories();

$category_header = get_field('category_header', $term);
$category_description = get_field('category_description', $term);
$category_image = get_field('category_image', $term);

$bottom = get_field('bottom', 'option');

// Pobranie pól ACF dla sekcji 'bottom'
$section_id = $bottom['section_id'] ?? '';
$section_class = $bottom['section_class'] ?? '';
$flip = $bottom['flip'] ?? false;

// Przygotowanie klas CSS
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';

// Wygenerowanie unikalnego ID dla SVG
$unique_id = 'clip_'.uniqid();
@endphp

<div class="hero category-header relative" @if(!empty($category_image['url'])) style="background-image: url('{{ $category_image['url'] }}'); background-position: center; background-size: cover;" @endif>
	<div class="absolute inset-0 bg-primary"></div>
<div data-gsap-element="bread" class="__breadcrumb mb-4">
			@if (function_exists('yoast_breadcrumb'))
			{!! yoast_breadcrumb('<p id="breadcrumbs">','</p>') !!}
			@endif
		</div>
	<div class="__wrapper c-main relative z-10 pt-60 pb-26">
		<div class="__content w-full md:w-2/3">
			<h2 class="text-white m-header">
				{!! $category_header ?: get_the_archive_title() !!}
			</h2>
			@if ($category_description)
			<div class="text-white text-xl md:text-2xl">
				{!! $category_description !!}
			</div>
			@endif
		</div>
		<div id="category-tabs" class="category-tabs z-20 relative rounded-full">
			<!-- Swiper -->
			
		 <div id="category-tabs" class="category-tabs z-20 relative rounded-full">
            <!-- Swiper -->
            <div class="swiper category-swiper lg:flex lg:justify-center">
                <div class="swiper-wrapper lg:w-fit">
                    <!-- Slides -->
                    <div class="swiper-slide !w-auto">
                        <a href="/category/blog" class="__tab block bg-white rounded-full px-4 py-2 {{ is_category('blog') ? 'active' : '' }}">Blog</a>
                    </div>
                    @foreach($categories as $category)
                    @if($category->name !== 'Blog')
                    <div class="swiper-slide !w-auto">
                        <a href="{{ get_category_link($category->term_id) }}" class="__tab block bg-white rounded-full px-4 py-2 {{ $term && $term->term_id === $category->term_id ? 'active' : 'bg-primary-400' }}">{{ $category->name }}</a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            {{-- NOWY ELEMENT Z GRADIENTEM --}}
            <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-r from-transparent to-primary pointer-events-none z-30"></div>
        </div>
    </div>
		</div>
	</div>

</div>



@if (have_posts())
<div class="__posts c-main !mt-10 posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
	@while (have_posts()) @php(the_post())

	@includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
	@endwhile
</div>

{{-- {!! get_the_posts_navigation() !!} --}}
{!! the_posts_pagination() !!}
@else
<div class="mt-20 mb-20">
	<div class="c-main">
		<h3 class="">Brak wpisów w tej kategorii.</h3>
		<a class="main-btn m-btn" href="/wszystkie-wpisy/">Sprawdź wszystkie wpisy</a>
	</div>
</div>
@endif

<!-- bottom-block -->

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

@endsection