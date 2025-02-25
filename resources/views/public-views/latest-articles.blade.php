@extends('layouts-public.app')

@section('title', 'Talent Trellis')

@section('content')

    <main class="main mt-5">
        <!-- Featured Services Section -->
        <section id="featured-services" class="featured-services section light-background">

            <div class="container">

                <div class="row gy-4">

                    @foreach ($articles as $article)
                        <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="service-item d-flex">
                                <div class="icon flex-shrink-0"><i class="bi bi-briefcase"></i></div>
                                <div>
                                    <h4 class="title">
                                        <a href="{{ route('articles.show', $article->slug) }}"
                                            class="stretched-link">{{ $article->title }}</a>
                                    </h4>
                                    <p class="description">{{ Str::limit($article->content, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Featured Services Section -->
    </main>
@endsection
