<!doctype html>
<html @php(language_attributes())>

<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M5TV295L');</script>
<!-- End Google Tag Manager --> 

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@php(do_action('get_header'))
	@php(wp_head())

	{{-- Fonts --}}
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

	@vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- MailerLite Universal -->
<script>
    (function(w,d,e,u,f,l,n){w[f]=w[f]||function(){(w[f].q=w[f].q||[])
    .push(arguments);},l=d.createElement(e),l.async=1,l.src=u,
    n=d.getElementsByTagName(e)[0],n.parentNode.insertBefore(l,n);})
    (window,document,'script','https://assets.mailerlite.com/js/universal.js','ml');
    ml('account', '855371');
</script>
<!-- End MailerLite Universal -->
</head>

<body @php(body_class())>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5TV295L"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	@php(wp_body_open())

	<div id="app">

		@include('sections.header')

		@if (function_exists('is_woocommerce') && (is_shop() || is_product_category() || is_product_tag()))

		@yield('content')

		@elseif (function_exists('is_product') && is_product())

		<main id="main" class="main">
			@yield('content')
		</main>

		@else

		<main id="main" class="main">
			@yield('content')
		</main>

		@endif

		@include('sections.footer')
	</div>

	@php(do_action('get_footer'))
	@php(wp_footer())

</body>

</html>