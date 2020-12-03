@extends('emails.layout')

@section('preheader', 'طلب شراء جديد.')

@section('content')
    <h1 style="box-sizing: border-box; color: #2F3133; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;" align="center" dir="rtl">طلب شراء جديد</h1>
    <div style="text-align: center">
        <p>{{ $data['first_name'].' '.$data['last_name'] }} | {{ $data['email'] }} | {{ $data['phone_number'] }}</p>
        <p> مكان الإستلام : {{ $data['place'] }}</p>
        <p> التريخ : {{ $data['date'] }}</p>
        <p> الساعة : {{ $data['hour'] }}</p>
    </div>

    <table class="body-action" align="center" width="100%"
           cellpadding="0" cellspacing="0"
           style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 30px auto; padding: 0; text-align: center; width: 100%;"
    >
        <tr>
            <td align="center" style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                    <tr>
                        <td align="center" style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                            <table cellspacing="0" cellpadding="10" style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;" dir="rtl">
                                <tr>
                                    <th style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">اسم المنتج</th>
                                    <th style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">سعر المنتج</th>
                                    <th style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">عدد الوحدات</th>
                                    
                                </tr>
                                @foreach($cart->content() as $item)
                                    <tr>
                                        <td style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                                            {{ $item->name }}
                                        </td>
                                        <td style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                                            {{ $item->price }}
                                        </td>
                                        <td style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
                                            {{ $item->qty }}
                                        </td>
                                        

                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection