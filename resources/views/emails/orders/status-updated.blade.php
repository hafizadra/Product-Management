@component('mail::message')
# Status pesanan Anda berubah

Halo {{ $order->user->name }},

Status pesanan **#{{ $order->id }}** kini menjadi **{{ ucfirst($order->status) }}**.

@component('mail::panel')
Total: **Rp {{ number_format($order->total, 0, ',', '.') }}**  
Status terbaru: **{{ ucfirst($order->status) }}**
@endcomponent

@component('mail::button', ['url' => route('orders.show', $order), 'color' => 'primary'])
Lihat Detail Pesanan
@endcomponent

Jika Anda merasa tidak melakukan perubahan ini, hubungi tim kami.

Terima kasih,  
Tim Aleef
@endcomponent
