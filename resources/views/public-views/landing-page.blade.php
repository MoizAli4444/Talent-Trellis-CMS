@extends('layouts-public.app')

@section('title', 'Talent Trellis')

@section('content')

    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="hero-bg">
            <img src="{{ asset('landing-page-theme/assets/img/hero-bg-light.webp') }}" alt="">
        </div>
        <div class="container text-center">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up">Welcome to <span>InspireWrite</span></h1>
                <p data-aos="fade-up" data-aos-delay="100">Share your thoughts, write compelling articles, and manage your content effortlessly with our simple yet powerful CMS.</p>
                
                <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('login') }}" class="btn-get-started">Get Started</a>
                </div>
                <img src="{{ asset('landing-page-theme/assets/img/hero-services-img.webp') }}" class="img-fluid hero-img" alt="" data-aos="zoom-out" data-aos-delay="300">
            </div>
        </div>
    </section>

@endsection
