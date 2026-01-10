@component('mail::message')
# Terima kasih atas order Anda!

Halo {{ $order->user->name }},

Pesanan dengan nomor **#{{ $order->id }}** telah kami terima.

@component('mail::panel')
Total: **Rp {{ number_format($order->total, 0, ',', '.') }}**  
Status awal: **{{ ucfirst($order->status) }}**
@endcomponent

@component('mail::button', ['url' => route('orders.show', $order), 'color' => 'primary'])
Lihat Detail Pesanan
@endcomponent

Terima kasih telah berbelanja di Aleef Bookstore!

Salam,  
Tim Aleef
@endcomponent
