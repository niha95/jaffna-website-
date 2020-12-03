<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Controllers\Site\SiteController;

class CartController extends SiteController
{
    public function add($product_id, Cart $cart)
    {
         $product = Product::findOrFail($product_id);

        $thumb = null;
      
            $thumb = $product->image->first()->thumbFullLink();
        

        $cart->add($product->id, $product->name_ar, 1, $product->price, [
            'thumb' => $thumb,
        ]);

        return redirect()->route('site.store');
       /* $product = Product::findOrFail($product_id);
        $cart->add($product->id,$product->name_en, $product->price);

        return view('site.layouts.partials._cart')->render();*/
    }

    public function remove($rowId, Cart $cart)
    {
        $cart->remove($rowId);

        return view('site.layouts.partials._cart')->render();
    }

    public function clear(Cart $cart)
    {
        $cart->destroy();
    }

    public function getCheckout()
    {
       /* if(!auth()->check()) {
            flashMessage(trans('messages.login_first'));
            return redirect()->route('site.home');
        }*/

        return view('site.checkout')->with('cart', \Cart::content());
    }

    public function request(Request $request,Cart $cart, Guard $guard)
    {
        
        if(!empty($request->get('qty')) && is_array($request->get('qty'))){
            foreach ($request->get('qty') as $key => $q) {
                $cart->update($key,$q);
            }
        }

        $data = $request->only('first_name','last_name','email','phone_number','place','date','hour');
       
        \Mail::send(
            'emails.purchase_request',
            ['data' => $data, 'cart' => $cart],
            function ($m) use ($data) {
                $m->subject(trans('labels.new_purchase_request'));
                $m->to('Info@Sherifticateti.Com', 'Sherifticateti');
                $m->to($data['email'], $data['first_name'].' '.$data['last_name']);
            }
           
        );

        $cart->destroy();

        
        return redirect()->route('site.home');
    }
}