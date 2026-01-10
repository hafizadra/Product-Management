<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Kelola detail rekening atau kartu yang digunakan saat pembayaran.">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h6 fw-semibold mb-1">Detail Pembayaran</h2>
                    <p class="text-muted small mb-0">
                        Informasi ini akan tampil otomatis saat checkout.
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                    ← Kembali ke profil
                </a>
            </div>

            <form action="{{ route('profile.payment.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Nama Pemegang Rekening</label>
                    <input
                        type="text"
                        name="payment_account_name"
                        value="{{ old('payment_account_name', $user->payment_account_name) }}"
                        class="form-control @error('payment_account_name') is-invalid @enderror"
                        placeholder="Nama sesuai rekening"
                    >
                    @error('payment_account_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor Rekening / Kartu</label>
                    <input
                        type="text"
                        name="payment_card_number"
                        value="{{ old('payment_card_number', $user->payment_card_number) }}"
                        class="form-control @error('payment_card_number') is-invalid @enderror"
                        placeholder="16 digit / nomor rekening"
                    >
                    @error('payment_card_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Kadaluarsa</label>
                    <input
                        type="text"
                        name="payment_card_expiry"
                        value="{{ old('payment_card_expiry', $user->payment_card_expiry) }}"
                        class="form-control @error('payment_card_expiry') is-invalid @enderror"
                        placeholder="MM/YY"
                    >
                    @error('payment_card_expiry')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">CVC</label>
                    <input
                        type="text"
                        name="payment_card_cvc"
                        value="{{ old('payment_card_cvc', $user->payment_card_cvc) }}"
                        class="form-control @error('payment_card_cvc') is-invalid @enderror"
                        placeholder="3 digit"
                    >
                    @error('payment_card_cvc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="alert alert-warning small">
                        Demi keamanan, kami hanya menampilkan empat digit terakhir nomor kartu pada ringkasan.
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary px-4">
                        Simpan Informasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-profile-layout>
