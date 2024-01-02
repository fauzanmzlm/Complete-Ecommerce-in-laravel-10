@extends('frontend.layouts.master')

@section('title','Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
            
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
                <form class="form" method="POST" action="{{route('cart.order')}}">
                    @csrf
                    <div class="row"> 

                        <div class="col-lg-8 col-12">
                            <div class="checkout-form">
                                <h2>Booking Summary</h2>
                                <p>Review the details below to confirm your equipment booking. Ensure accuracy in your selections before proceeding to the checkout.</p>
                                <!-- Form -->
                    {{-- <hr> --}}
                                {{-- <div style="border-top: 1px solid rgb(235, 235, 235);"></div> --}}
                                <div class="my-3">
                                    <h6>Borrower Detail</h6>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" placeholder="" value="{{ auth()->user()->name ?? '' }}" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" placeholder="" value="{{ auth()->user()->email ?? '' }}" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6>Selected Equipments</h6>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <!-- Shopping Summery -->
                                        <table class="table shopping-summery table-bordered">
                                            <thead>
                                                <tr class="main-hading">
                                                    <th>EQUIPMENT</th>
                                                    <th>NAME</th>
                                                    <th>FROM DATE</th>
                                                    <th>TO DATE</th>
                                                    <th class="text-center">QUANTITY</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cart_item_list">
                                                    @if(Helper::getAllProductFromCart())
                                                        @foreach(Helper::getAllProductFromCart() as $key=>$cart)
                                                            <tr>
                                                                @php
                                                                $photo=explode(',',$cart->product['photo']);
                                                                @endphp
                                                                <td class="image text-center" data-title="No"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></td>
                                                                <td class="product-des" data-title="Description">
                                                                    <p class="product-name"><a href="{{route('product-detail',$cart->product['slug'])}}" target="_blank">{{$cart->product['title']}}</a></p>
                                                                    <p class="product-des">{!!($cart['summary']) !!}</p>
                                                                </td>
                                                                <td class="text-center" data-title="FromDate">
                                                                    17/01/2024
                                                                </td>
                                                                <td class="text-center" data-title="ToDate">
                                                                    20/01/2024
                                                                </td>
                                                                <td class="qty text-center" data-title="Qty">
                                                                    {{$cart->quantity}}
                                                                </td>
                    
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                            <tr>
                                                                <td class="text-center">
                                                                    There are no any carts available. <a href="{{route('product-grids')}}" style="color:blue;">Continue browsing</a>
                    
                                                                </td>
                                                            </tr>
                                                    @endif
                    
                                            </tbody>
                                        </table>
                                        <!--/ End Shopping Summery -->
                                    </div>
                                </div>

                                
                                <div class="mb-3 mt-5">
                                    <h6>Terms and Conditions</h6>
                                    <p>Please review and agree to the <a href="#" style="color: rgb(0, 4, 250)">terms and conditions</a> before finalizing your booking.</p>
                                    <div class="form-check ml-4" style="margin-top: -22px;">
                                        <input type="checkbox" class="form-check-input" id="tnc" name="tnc" required="required">
                                        <label class="form-check-label" for="termsCheckbox">I agree to the terms and conditions</label>
                                    </div>
                                </div>


                                <!--/ End Form -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="order-details">
                                
                                <!-- Button Widget -->
                                <div class="single-widget get-button">
                                    <div class="content">
                                        <div class="button mt-3">
                                            <button type="submit" class="btn">proceed to checkout</button>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Button Widget -->
                            </div>
                        </div>
                    </div>
                </form>
        </div>
    </section>
    <!--/ End Checkout -->

    @include('frontend.layouts.system-service')

    <!-- Start Shop Newsletter  -->
	@include('frontend.layouts.newsletter')
	<!-- End Shop Newsletter -->

@endsection

@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		function showMe(box){
			var checkbox=document.getElementById('shipping').style.display;
			// alert(checkbox);
			var vis= 'none';
			if(checkbox=="none"){
				vis='block';
			}
			if(checkbox=="block"){
				vis="none";
			}
			document.getElementById(box).style.display=vis;
		}
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') ); 
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0; 
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush