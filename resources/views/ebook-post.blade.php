{{--
  Template Name: Ebook Post
  Template Post Type: post
--}}

@extends('layouts.app')

@section('content')
@while(have_posts()) @php(the_post())
<div class="-spt">
	<article @php(post_class('h-entry'))>
		<div data-gsap-element="bread" class="__breadcrumb c-main pt-6">
			@if (function_exists('woocommerce_breadcrumb'))
			{!! woocommerce_breadcrumb() !!}
			@endif
		</div>
		@php(the_content())
	</article>
</div>
@endwhile
@endsection