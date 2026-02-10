@php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
@endphp

<section data-gsap-anim="section" class="hero-blog bg-gradient relative overflow-visible">
	<div class="__wrapper c-main relative z-10 -spt">
		<div class="__content w-full sm:w-3/4 mx-auto pb-30">
			<div data-gsap-element="bread" class="__breadcrumb">
				@if (function_exists('woocommerce_breadcrumb'))
				{!! woocommerce_breadcrumb() !!}
				@endif
			</div>

			<div class="__top mt-20 text-center">
				@if ($category)
				<a data-gsap-element="header" href="{{ get_category_link($category->term_id) }}" class="bg-primary-lighter hover:bg-primary-light border border-primary-light rounded-full text-sm px-4 py-3">{{ $category->name }}</a>
				@endif
				<h1 data-gsap-element="header" class="text-h2 text-white mt-6">{{ get_the_title() }}</h1>
				@if(has_excerpt())
				<div data-gsap-element="content" class="text-white mt-4">
					{!! get_the_excerpt() !!}
				</div>
				@endif
			</div>
		</div>
	</div>
	<img src="/wp-content/uploads/2026/01/blog-leaf.svg" alt="" class="absolute -top-20 -right-20 pointer-events-none">
</section>

<section data-gsap-anim="section">
	<div id="tresc" class="__entry relative z-10 -mt-16">
		<div class="c-main">

			@if(has_post_thumbnail())
			<div data-gsap-element="image" class="w-full img-2xl rounded-xl overflow-hidden mb-16">
				{!! get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'w-full object-cover']) !!}
			</div>
			@endif
		</div>

	</div>
</section>

@php
$content = apply_filters('the_content', get_the_content());

preg_match_all('/<h([1-4])[^>]*>(.*?)<\/h[1-4]>/', $content, $matches, PREG_SET_ORDER);

		$toc = '<nav class="toc">
			<ul>';
				$used_ids = [];
				foreach ($matches as $match) {
				$level = $match[1];
				$title = strip_tags($match[2]);
				$id = sanitize_title($title);
				$base_id = $id;
				$i = 2;
				while (in_array($id, $used_ids)) {
				$id = $base_id . '-' . $i;
				$i++;
				}
				$used_ids[] = $id;
				$content = preg_replace(
				'/<h' . $level . '[^>]*>' . preg_quote($match[2], '/' ) . '<\/h' . $level . '>/' , '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>' ,
					$content,
					1
					);
					$toc .='<li class="toc-h' . $level . '"><a href="#' . $id . '">' . $title . '</a></li>' ;
					}
					$toc .='</ul></nav>' ;
					@endphp

					<div class="__content c-main __entry -smt grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-10">

					<div class="relative md:sticky top-0 md:top-30 h-max">
						<h5 class="m-title">Spis treści</h5>
						@if(count($matches))
						{!! $toc !!}
						@endif
					</div>

					<div id="tresc" class="__entry">
						{!! $content !!}
					</div>

					</div>

					@php
					$current_id = get_the_ID();
					$categories = wp_get_post_categories($current_id);
					$related_args = [
					'category__in' => $categories,
					'post__not_in' => [$current_id],
					'posts_per_page' => 3,
					'ignore_sticky_posts' => 1,
					];
					$related_query = new WP_Query($related_args);
					@endphp

				 @if($related_query->have_posts())
                    <section class="related-posts c-main border-t border-primary-light -smt pt-20 pb-26">
                        <h3 class="text-2xl font-bold mb-6">Podobne wpisy</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @while($related_query->have_posts())
                            @php($related_query->the_post())
                            <article @php(post_class('bg-white radius p-6 flex flex-col'))>
                                <header>
                                    @if(has_post_thumbnail())
                                    <a href="{{ get_permalink() }}">
                                        {!! get_the_post_thumbnail(null, 'large', ['class' => 'featured-image radius object-cover img-xs']) !!}
                                    </a>
                                    @endif

                                    @php($post_categories = get_the_category(get_the_ID()))
                                    @if(!empty($post_categories))
                                    <div class="flex flex-wrap gap-2 mt-4">
                                        @foreach($post_categories as $post_category)
                                        <a href="{{ get_category_link($post_category->term_id) }}" class="bg-primary-lighter hover:bg-primary-light border border-primary-light radius text-xs p-2">{{ $post_category->name }}</a>
                                        @endforeach
                                    </div>
                                    @endif

                                    <h2 class="entry-title text-h6 mt-4">
                                        <a href="{{ get_permalink() }}">
                                            {{ get_the_title() }}
                                        </a>
                                    </h2>

                                </header>

                                <a class="underline-btn mt-auto pt-4" href="{{ get_permalink() }}">
                                    Przeczytaj
                                </a>

                            </article>
                            @endwhile
                            @php(wp_reset_postdata())
                        </div>
                    </section>
                    @endif


					<script>
						document.addEventListener('DOMContentLoaded', function() {
							const headings = document.querySelectorAll('h1[id], h2[id], h3[id], h4[id]'); // Select all headings with IDs
							const tocLinks = document.querySelectorAll('.toc ul li a'); // Select all links in the TOC

							function updateActiveLink() {
								headings.forEach((heading) => {
									const headingTop = heading.getBoundingClientRect().top;
									const windowHeight = window.innerHeight;

									if (headingTop < windowHeight - 300) {
										// Remove the 'active' class from all TOC links
										tocLinks.forEach((link) => {
											link.parentNode.classList.remove('active');
										});

										// Add the 'active' class to the corresponding TOC link
										const id = heading.id;
										const activeLink = document.querySelector(`.toc ul li a[href="#${id}"]`);
										if (activeLink) {
											activeLink.parentNode.classList.add('active');
										}
									}
								});
							}
							updateActiveLink();

							window.addEventListener('scroll', updateActiveLink);
						});
					</script>