<div id="checkout-sidebar" class="col-xl-3 col-lg-4 col-12 w-res-sidebar sticky-sidebar">
    <div class="dt-sn mb-2 details">
        <ul class="checkout-summary-summary">
            <li>
                <span>{{ trans('front::messages.partials.total-amount') }}</span><span>{{ trans('front::messages.currency.prefix') }}{{ number_format($cart->price) }} {{ currencyTitle() }}</span>
            </li>

            @if($cart->totalDiscount())
                <li class="checkout-summary-discount">
                    <span>{{ trans('front::messages.partials.discount') }}</span><span> {{ trans('front::messages.currency.prefix') }}{{ number_format($cart->totalDiscount()) }} {{ currencyTitle() }}</span>
                </li>
            @endif

            @if(isset($gatewaySelect) && in_array($gatewaySelect->key , ['check1' , 'check2' , 'check3' , 'check4' , 'check5' , 'check6']))
                <li class="checkout-summary-discount">
                    <span>درصد چک </span><span> {{ $gatewaySelect->config('percent') .'%'}}</span>
                </li>
            @endif


            @if ($cart->carrier_id && $cart->hasPhysicalProduct())
                <li>
                    <span>{{ trans('front::messages.partials.shipping-cost') }}</span>
                    <span>
                        {{ $cart->shippingCost() }}
                    </span>
                </li>
            @endif

        </ul>
        <div class="checkout-summary-devider">
            <div></div>
        </div>
        @php

              $finalPrice = $cart->finalPrice();


        @endphp
        <div class="checkout-summary-content">
            <div class="checkout-summary-price-title">{{ trans('front::messages.partials.the-amount-payable') }}</div>
            <div class="checkout-summary-price-value">
                <span id="final-price" data-value="{{  $finalPrice  }}" class="checkout-summary-price-value-amount">
                    {{ trans('front::messages.currency.prefix') }}{{ number_format($finalPrice) }}
                </span>
                {{ currencyTitle() }}
            </div>

            <button data-action="{{ route('front.cart') }}" data-redirect="{{ route('front.checkout') }}" id="checkout-link" type="button" class="btn-primary-cm btn-with-icon w-100 text-center pr-0 checkout_link">
                <i class="mdi mdi-arrow-left"></i>
                {{ trans('front::messages.partials.continue-order-registration') }}
            </button>

            <div>
                <span>
                    {{ trans('front::messages.partials.unregistered-goods') }}
                </span>
                <span class="help-sn" data-toggle="tooltip" data-html="true" data-placement="bottom"  title="<div class='help-container is-right'><div class='help-arrow'></div><p class='help-text'>{{ trans('front::messages.partials.products-in-cart') }}</p></div>">
                    <span class="mdi mdi-information-outline"></span>
                </span>
            </div>
        </div>
    </div>

</div>
