@extends('frontend.layouts.master')

@section('title','USEBS || Booking Track Page')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Track Booking</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
<section class="tracking_box_area section_gap py-5">
    <div class="container">
        <div class="tracking_box_inner">
            <h4 class="mb-3">Track Booking</h4>
            <p>To track your booking please enter your Booking ID in the box below and press the "Track" button. This was given
                to you on your receipt and in the confirmation email you should have received.</p>
            <form class="row tracking_form my-4" action="{{route('product.track.order')}}" method="post" >
                @csrf
                <div class="col-md-8 form-group">
                    <input type="text" class="form-control p-2"  name="order_number" placeholder="Enter your booking number" required="required">
                </div>
                <div class="col-md-8 form-group">
                    <button type="submit" class="btn">Track Booking</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection