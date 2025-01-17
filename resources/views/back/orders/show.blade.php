@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('back/assets/css/pages/order.css') }}">
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-7 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت سفارشات
                                    </li>
                                    <li class="breadcrumb-item active">اطلاعات سفارش شماره {{ $order->id }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- page users view start -->
                <section class="page-users-view">
                    <div class="row">

                        <div class="col-12 d-print-none">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">عملیات</div>

                                </div>
                                <div class="card-body" id="main-card">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <select class="custom-select" id="shipping-status" data-action="{{ route('admin.orders.shipping-status', ['order' => $order]) }}" {{ (!$order->hasPhysicalItem() || (!in_array($order->status , ['pending' , 'paid']) || $order->shipping_status == 'canceled')) ? 'disabled' : '' }}>
                                                    <option {{ ($order->shipping_status == 'pending') ? 'selected' : '' }} value="pending">در حال بررسی</option>
                                                    <option {{ ($order->shipping_status == 'wating') ? 'selected' : '' }} value="wating">منتظر ارسال</option>
                                                    <option {{ ($order->shipping_status == 'sent') ? 'selected' : '' }} value="sent">ارسال شد</option>
                                                    <option {{ ($order->shipping_status == 'canceled') ? 'selected' : '' }} value="canceled">ارسال لغو شد</option>
                                                </select>
                                            </fieldset>
                                            <span>{{ !$order->hasPhysicalItem() ? 'سفارش محصول فیزیکی ندارد' : '' }}</span>
                                            <span>{{ ($order->status != 'paid') ? 'سفارش پرداخت نشده است' : '' }}</span>
                                            <span>{{ ($order->shipment_tracking_code != null) ? 'کدرهگیری: '.$order->shipment_tracking_code : '' }}</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="float-right">
                                                <a href="{{ route('admin.orders.shipping-form', ['order' => $order]) }}" target="_blank" class="btn btn-outline-info waves-effect waves-light"><i class="feather icon-printer"></i>چاپ برچسب پستی</a>
                                                <a href="{{ route('admin.orders.print', ['order' => $order]) }}" target="_blank" class="btn btn-outline-primary waves-effect waves-light"><i class="feather icon-printer"></i> چاپ</a>
                                                <button type="button" data-toggle="modal" data-target="#delete-modal" class="btn btn-outline-danger waves-effect waves-light"><i class="feather icon-trash-2"></i> حذف سفارش</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">مشخصات کاربر</div>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href="{{ route('admin.users.show', ['user' => $order->user]) }}" target="_blank"><i class="feather icon-external-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-md-3">نام :</dt>
                                                        <dd class="col-md-6">{{ $order->user->first_name }}</dd>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-md-3">نام خانوادگی :</dt>
                                                        <dd class="col-md-6">{{ $order->user->last_name }}
                                                        </dd>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-md-3">نام کاربری :</dt>
                                                        <dd class="col-md-6">{{ $order->user->username }}</dd>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-md-3">ایمیل :</dt>
                                                        <dd class="col-md-6">{{ $order->user->email ? $order->user->email : '-' }}</dd>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- account end -->
                        <!-- information start -->
                        <div class="col-md-12 col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">اطلاعات سفارش</div>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-6 col-12 ">
                                        <table class="details">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">نام و نام خانوادگی :</td>
                                                    <td>{{ $order->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">شماره همراه :</td>
                                                    <td>{{ $order->mobile }}</td>
                                                </tr>

                                                @if ($order->hasPhysicalProduct())
                                                    <tr>
                                                        <td class="font-weight-bold">استان :</td>
                                                        <td>{{ $order->province ? $order->province->name : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">شهر :</td>
                                                        <td>{{ $order->city ? $order->city->name : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">کد پستی :</td>
                                                        <td>{{ $order->postal_code }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">آدرس کامل :</td>
                                                        <td>{{ $order->address }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6 col-12 ">
                                        <table class="details">
                                            <tbody>
                                                @if ($order->hasPhysicalProduct())
                                                    <tr>
                                                        <td class="font-weight-bold">شیوه تحویل :</td>
                                                        <td>{{ $order->carrier ? $order->carrier->title : 'نامشخص' }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="font-weight-bold">تاریخ ثبت :</td>
                                                    <td>{{ jdate($order->created_at) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">نحوه پرداخت :</td>
                                                    @if($order->gateway == 'wallet')
                                                        <td>پرداخت از کیف پول</td>
                                                    @else
                                                    @if(in_array($order->gatewayRelation->key , ['check1' , 'check2' , 'check3' , 'check4' , 'check5' , 'check6']))

                                                        <td>{{$order->gatewayRelation->name .'(' . $order->gatewayRelation->config('percent') .'% )'}}</td>
                                                    @else
                                                        <td>پرداخت آنلاین</td>

                                                    @endif
                                                    @endif

                                                </tr>
                                                @if(in_array($order->gateway , ['check1' , 'check2' , 'check3' , 'check4' , 'check5' , 'check6']))
                                                    <tr>
                                                        <td class="font-weight-bold">تصویر چک :</td>
                                                        <td>

                                                            @switch($order->gateway)
                                                                @case('check1')
                                                                    <a href="{{asset($order->fileCheckOne)}}" download="true" > دانلود چک 1</a>
                                                                    @break
                                                                @case('check2')
                                                                    <a href="{{asset($order->fileCheckOne)}}" download="true" > دانلود چک 1</a>,
                                                                    <a href="{{asset($order->fileCheckTwo)}}" download="true" > دانلود چک 2</a>
                                                                    @break
                                                                @case('check3')
                                                                    <a href="{{asset($order->fileCheckOne)}}" download="true" > دانلود چک 1</a>,
                                                                    <a href="{{asset($order->fileCheckTwo)}}" download="true" > دانلود چک 2</a>,
                                                                    <a href="{{asset($order->fileCheckThree)}}" download="true" > دانلود چک 3</a>
                                                                    @break
                                                                @case('check4')
                                                                    <a href="{{asset($order->fileCheckOne)}}" download="true" > دانلود چک 1</a>,
                                                                    <a href="{{asset($order->fileCheckTwo)}}" download="true" > دانلود چک 2</a>,
                                                                    <a href="{{asset($order->fileCheckThree)}}" download="true" > دانلود چک 3</a>,
                                                                    <a href="{{asset($order->fileCheckFour)}}" download="true" > دانلود چک 4</a>
                                                                    @break
                                                                @case('check5')
                                                                    <a href="{{asset($order->fileCheckOne)}}" download="true" > دانلود چک 1</a>,
                                                                    <a href="{{asset($order->fileCheckTwo)}}" download="true" > دانلود چک 2</a>,
                                                                    <a href="{{asset($order->fileCheckThree)}}" download="true" > دانلود چک 3</a>,
                                                                    <a href="{{asset($order->fileCheckFour)}}" download="true" > دانلود چک 4</a>,
                                                                    <a href="{{asset($order->fileCheckFive)}}" download="true" > دانلود چک 5</a>
                                                                    @break
                                                                @case('check6')
                                                                    <a href="{{asset($order->fileCheckOne)}}" download="true" > دانلود چک 1</a>,
                                                                    <a href="{{asset($order->fileCheckTwo)}}" download="true" > دانلود چک 2</a>,
                                                                    <a href="{{asset($order->fileCheckThree)}}" download="true" > دانلود چک 3</a>,
                                                                    <a href="{{asset($order->fileCheckFour)}}" download="true" > دانلود چک 4</a>,
                                                                    <a href="{{asset($order->fileCheckFive)}}" download="true" > دانلود چک 5</a>,
                                                                    <a href="{{asset($order->fileCheckSix)}}" download="true" > دانلودچک 6</a>

                                                                    @break
                                                            @endswitch


                                                        </td>


                                                    </tr>
                                                @endif

                                                    @if ($order->hasPhysicalProduct())
                                                    <tr>
                                                        <td class="font-weight-bold">هزینه ارسال:</td>
                                                        <td>{{ number_format($order->shipping_cost).' '.currencyTitle() }} </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="font-weight-bold">تخفیف:</td>
                                                    <td>{{ number_format($order->totalDiscount()).' '.currencyTitle() }} </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">جمع قیمت</td>
                                                    <td>{{ number_format($order->price).' '.currencyTitle() }} </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">توضیحات سفارش :</td>
                                                    <td>
                                                        {{ $order->description }}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <div class="table-responsive">
                                        <table class="table withdraw__table">
                                            <thead>
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>تصویر</th>
                                                    <th style="width: 300px;">نام محصول</th>
                                                    <th>تعداد</th>
                                                    <th>قیمت واحد</th>
                                                    @if(in_array($order->gateway , ['check1' , 'check2' , 'check3' , 'check4' , 'check5' , 'check6']))
                                                        <th>مبلغ واحد با درصد چک</th>
                                                    @endif
                                                    <th>قیمت کل</th>
                                                    <th>تخفیف</th>
                                                    <th>قیمت نهایی</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($order->items as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            @if($item->product)
                                                                <a href="{{ Route::has('front.products.show') ? route('front.products.show', ['product' => $item->product]) : '' }}" target="_blank"><img class="table-img" src="{{ $item->product->image ? asset($item->product->image) : asset('/empty.jpg') }}"></a>
                                                            @else
                                                                <img class="table-img" src="{{ asset('empty.jpg') }}">
                                                            @endif
                                                        </td>
                                                        <td class="order-product-name">
                                                            {{ $item->title }}

                                                            @if ($item->get_price)
                                                                @foreach ($item->get_price->get_attributes as $attribute)

                                                                    @if ($attribute->group->type == 'color')
                                                                        <span class="order-product-color d-print-none" style="background-color: {{ $attribute->value }};"></span>
                                                                        <span>{{ $attribute->group->name }}: {{ $attribute->name }}</span>

                                                                    @else
                                                                        <span>{{ $attribute->group->name }}: {{ $attribute->name }}</span>
                                                                    @endif

                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->realPrice()).' '.currencyTitle() }} </td>
                                                        @if(in_array($order->gateway , ['check1' , 'check2' , 'check3' , 'check4' , 'check5' , 'check6']))
                                                                <?php
                                                                $checkPercent = $order->gatewayRelation->config('percent') ;
                                                                $price = $item->realPrice();
                                                                $priceCheck = $price + ( $price * $checkPercent/100) ;
                                                                $priceAllCheck = $item->price + ( $item->price * $checkPercent/100) ;
                                                                ?>
                                                            <td>{{ number_format($priceCheck) }}</td>
                                                        @endif
                                                        <td>{{ number_format($item->quantity * ($priceCheck ?? $item->realPrice())).' '.currencyTitle() }} </td>
                                                        <td>{{ $item->discount ? $item->discount . '%' : 0 }}</td>
                                                        <td>{{ number_format(($priceAllCheck ?? $item->price) * $item->quantity).' '.currencyTitle() }} </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- information start -->
                        <!-- social links end -->
                    </div>
                </section>
                <!-- page users view end -->

            </div>
        </div>
    </div>

    {{-- delete order modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف سفارش دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('admin.orders.destroy', ['order' => $order]) }}" id="product-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/orders/show.js') }}"></script>
@endpush
