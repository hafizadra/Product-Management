<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Perbarui data pribadi dan alamat default.">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="h6 fw-semibold mb-1">Informasi Pribadi</h2>
                    <p class="text-muted small mb-0">Perbarui data akun anda secara langsung.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    ← Dashboard
                </a>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor HP</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="+62..."
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Alamat Utama</span>
                        <a href="{{ route('profile.addresses.index') }}" class="small text-decoration-none">
                            Kelola di Daftar Alamat →
                        </a>
                    </label>
                    <div class="border rounded-3 p-3 bg-light">
                        @if ($user->default_shipping_address)
                            <p class="mb-2 small">
                                {!! nl2br(e($user->default_shipping_address)) !!}
                            </p>
                            <span class="badge bg-primary-subtle text-primary">Alamat default</span>
                        @else
                            <div class="alert alert-warning small mb-0">
                                Belum ada alamat default. Tambahkan melalui menu Daftar Alamat.
                            </div>
                        @endif
                    </div>
                    <div class="form-text small">
                        Alamat default hanya bisa diubah dari Daftar Alamat agar tetap konsisten.
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-profile-layout>
