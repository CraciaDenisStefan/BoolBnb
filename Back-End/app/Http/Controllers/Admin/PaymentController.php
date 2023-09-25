<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Sponsorship;
use Braintree\Gateway;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $paymentMethod = "creditCard";
        $sponsorshipId = $request->input('sponsorship_id');
        $sponsorship = Sponsorship::find($sponsorshipId);
        $apartmentId = $request->input('apartment_id');
        $apartment = Apartment::find($apartmentId);
        // Estrae il nonce (un token di pagamento) dalla richiesta HTTP
        $nonce = $request->payment_method_nonce;
        $price = $sponsorship->price;

        // Effettua il pagamento utilizzando Braintree
        $gateway = new Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchant_id'),
            'publicKey' => config('services.braintree.public_key'),
            'privateKey' => config('services.braintree.private_key'),
        ]);

        // Gestisci il pagamento in base al metodo selezionato
        if($paymentMethod === 'creditCard'){
            // Elabora la transazione con carta di credito
            $result = $gateway->transaction()->sale([
                'amount' => $price,
                //Prende il token nonce
                'paymentMethodNonce' => $nonce,
                // transazione verrà inviata per l'elaborazione immediata
                'options' => [ 'submitForSettlement' => true],
            ]);
        }elseif($paymentMethod === 'paypal'){
            // Elabora la transazione con PayPal
            // Implementa la logica per il pagamento PayPal
        }

        // Gestisci la risposta di Braintree e restituisci una vista appropriata
        if($result->success){
            if($apartment->sponsorships()->count() > 0){
                return view('admin.payment.already');
            }else{
                $apartment->sponsorships()->attach($sponsorship->id, [
                    'start_date' => now()->addHours(2), // Imposta la data corrente
                    'end_date' => (now()->addHours(2 + $sponsorship->duration)),
                ]);
            };
            return view('admin.payment.success');
        }else{
            // Pagamento fallito, mostra una vista di errore
            return view('admin.payment.error', ['errorMessage' => $result->message]);
        }
    }
}