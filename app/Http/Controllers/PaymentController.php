<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class PaymentController extends Controller
{

public function initiate(Request $request)
{
    $user = auth()->user();

    $response = Http::post(config('services.wompi.url_base') . 'transactions', [
        'amount_in_cents' => 500000,
        'currency' => 'COP',
        'customer_email' => $user->email,
        'reference' => Str::uuid(),
        'public_key' => config('services.wompi.public_key'),
        'redirect_url' => 'https://tuapp.com/confirmacion-pago',
    ]);

    return response()->json([
        'checkout_url' => $response['data']['payment_link']
    ]);
}

public function webhook(Request $request)
{
    $data = $request->input('data');
    $signature = $request->header('X-Webhook-Signature');

    $expectedSignature = hash_hmac(
        'sha256',
        json_encode($data),
        config('services.wompi.private_key')
    );

    if ($signature !== $expectedSignature) {
        return response()->json(['error' => 'Firma inválida'], 403);
    }

    // Aquí actualizas la transacción en tu base de datos
    $transaction = Transaction::where('reference', $data['transaction']['reference'])->first();

    if ($transaction) {
        $transaction->status = $data['transaction']['status'];
        $transaction->save();
    }

    return response()->json(['status' => 'ok']);
}


}
