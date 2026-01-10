<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Perbarui password akun secara berkala untuk keamanan.">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="h6 fw-semibold mb-1">Ganti Password</h2>
                <p class="text-muted small mb-0">
                    Gunakan password minimal 8 karakter dengan kombinasi huruf dan angka.
                </p>
            </div>

            <form action="{{ route('profile.security.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <label class="form-label">Password Saat Ini</label>
                    <input
                        type="password"
                        name="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        required
                    >
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary px-4">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-profile-layout>
