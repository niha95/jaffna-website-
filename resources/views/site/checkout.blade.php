@extends('site.layouts.default')

@section('content')

        <section class="page-content">
            <div class="container shopping">
                <div class="row">
                    <div class="col-sm-4">
                        @foreach($cart as $item)
                        <div class="cart">
                           
                            <div>
                           
                                <h3> {{$item->name}}</h3>
                                <ul class="list-unstyled">
                                    <li>
                                        {{$item->name}}
                                        
                                    </li>
                                    <li>
                                        @if ($item->offer == 1)
                                        <span class="price"> السعر : {{ $item->offer_price }} ريال</span>
                                        @else
                                        <span class="price"> السعر : {{ $item->price }} ريال</span>
                                        @endif
                                    </li>
                                    <li>
                                        <input class="form-control" type="number" name="qty[{{$item->rowId}}]" value="{{ $item->qty }}" class="form-control item_quntity" data-price="{{ $item->price }}" min="1">
                                    </li>
                                     <li>
                                        <span class="t-price"> الاجمالى : {{ $item->price*$item->qty }} ريال</span>
                                    </li>
                                </ul>
                                
                                <button class="btn btn-xs btn-danger pull-right btn-delete-item delete-checkout" data-row-id="{{ $item->rowId }}">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                                
                            </div>
                        </div>
                        @endforeach
                    </div>
                   {{ Form::model(auth()->user(),['route' => 'site.flight_reservation']) }}
                <div class="col-sm-6">
                    <div class="form-wrapper" style="padding:50px">
                        <h1 class="section-title">عنوان الشحن </h1>


                        @include('site.common._errors')
                            @include('site.common._flash_message')
                            {!! csrf_field() !!}


                        
                        <div class="form-group">
                            <label for="firstName">{{ trans('labels.name') }}</label>
                            
                                
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('labels.name'), 'required']) }}
                            
                              
                            
                        </div>


                        <div class="form-group">
                            <label for="email">{{ trans('labels.email_address') }}</label>
                            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('labels.email_address'), 'required']) }}
                        </div>

                        <div class="form-group">
                            <label for="phoneNumber">{{ trans('labels.phone_number') }}</label>
                            {{ Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => trans('labels.phone_number')]) }}
                        </div>


                        <div class="form-group">
                            <label for="phoneNumber">مدينة الإستلام</label>
                            {{ Form::text('Place', null, ['class' => 'form-control', 'placeholder' => 'مكان الأستلام', 'required']) }}
                        </div>

                        <div class="form-group">
                            <label for="firstName">ميعاد الإستلام</label>
                            <div class="row">
                                <div class="col-xs-6">
                                    {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => 
                                    'التريخ', 'required']) }}
                                </div>
                                <div class="col-xs-6">
                                    {{ Form::text('hours', null, ['class' => 'form-control', 'placeholder' => 'الساعة', 'required']) }}
                                </div>
                            </div>
                            <p>عميلنا الكريم يتم تسليم طلبيتكم في موعد اقصاه من 3 - 6 ايام من تاريخ التحويل البنكي</p>
                        

                        <hr>
                         @foreach($cart as $item)
                         <div class="form-group">
<input type="hidden" id="Product_name" name="Product_name" value="{{$item->name}}">
<input type="hidden" id="Price" name="Price" value="{{$item->price*$item->qty}}">
<input type="hidden" id="Quantity" name="Quantity" value="{{ $item->qty }}">
</div>
@endforeach
                        <div class="form-group">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Send </button>
                            </div>
                        </div>

                        
                    </div>
                </div>
                
                {{ Form::close() }}
                </div>
            </div>
        </section>
        




            </div>
        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    $(function(){
        getTotalPrice();
    });
    $(document).on('click','.delete-checkout',function(){
        $(this).parents('.cart_item').remove();
        getTotalPrice();
    });
    $(document).on('change','.item_quntity',function(){
        var qty = $(this).val();
        if(qty <1){
            $(this).val(1);
            qty=1;
        }
        var price = $(this).data('price');
        $(this).parents('.item_div').next('.price').html(qty*price);
       getTotalPrice();
    });

    function getTotalPrice()
    {
        var total = 0;
        $(".item_quntity").each(function(){
            total += $(this).val()*$(this).data('price')
        });
        $("#total_price").html(total);
    }

</script>
@append