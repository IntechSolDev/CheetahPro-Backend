@extends('web.includes.layouts')
@section('content')
        <!-- hero start -->
        @include('web.components.hero')
        <!-- hero End -->

        <!-- services start -->
        @include('web.components.about')
        <!-- services end -->

        <!-- stock start -->
        @include('web.components.stock-area')
        <!-- stock end -->

        <!-- how-it-works start -->
        @include('web.components.work')
        <!-- how-it-works end -->

        <!-- destination start -->
        @include('web.components.video')
        <!-- destination end -->

        <!-- manual area start -->
        @include('web.components.features')
        <!-- manual area end -->

        <!-- skills-area start -->
        @include('web.components.skills-area')
        <!-- skills-area end -->

        <!-- screenshot-area start -->
        @include('web.components.screenshot-area')
        <!-- screenshot-area end -->

        <!-- pricing-area start -->
        @include('web.components.pricing')
        <!-- pricing-area end -->

        <!-- blog-area start -->
        <!--@include('web.components.blog')-->
        <!-- blog-area end -->

        <!-- testimonials-area start -->
        @include('web.components.testimonials-area')
        <!-- testimonials-area end -->

        <!-- contact-area start -->
        @include('web.components.contact')
        <!-- contact-area end -->

        <!-- map-area start -->
        @include('web.components.map-area')
        <!-- map-area end -->

        <!-- brand-area start -->
        @include('web.components.brand-area')
        <!-- map-area end -->

@endsection
@section('page-script')
@endsection
