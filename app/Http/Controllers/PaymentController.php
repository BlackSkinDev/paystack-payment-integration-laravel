<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Unicodeveloper\Paystack\Facades\Paystack;


class PaymentController extends Controller
{

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {


        $paymentDetails = Paystack::getPaymentData();

        $transaction_id=Auth::user()->id.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 7));

        $transaction= Auth::user()->transactions()->create([
            'transaction_id'=>$transaction_id
        ]);

        if($transaction){

             Session::flash('msg','Payment successful');
                return redirect()->route('home');
        }
        else{
            dd("error");
        }
    }
}
