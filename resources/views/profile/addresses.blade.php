<x-profile-layout :sidebar="$sidebar" :page-title="$pageTitle" subtitle="Kelola alamat pengiriman untuk checkout lebih cepat.">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h6 fw-semibold mb-1">Tambah Alamat Baru</h2>
                    <p class="text-muted small mb-0">Simpan alamat untuk digunakan kapan saja.</p>
                </div>
            </div>
            <form action="{{ route('profile.addresses.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Label</label>
                    <input type="text" name="label" value="{{ old('label') }}" class="form-control @error('label') is-invalid @enderror" placeholder="Rumah / Kantor">
                    @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Penerima</label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="form-control @error('recipient_name') is-invalid @enderror" required>
                    @error('recipient_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="+62...">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control @error('postal_code') is-invalid @enderror">
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address_line" rows="3" class="form-control @error('address_line') is-invalid @enderror" required>{{ old('address_line') }}</textarea>
                    @error('address_line')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="new-default" name="is_default" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="new-default">
                            Jadikan alamat utama
                        </label>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary px-4">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h6 fw-semibold mb-1">Daftar Alamat</h2>
                    <p class="text-muted small mb-0">Edit, hapus, atau jadikan default.</p>
                </div>
            </div>

            @forelse ($addresses as $address)
                <div class="border rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-semibold">{{ $address->label ?? 'Alamat' }}</span>
                                @if ($address->is_default)
                                    <span class="badge bg-primary-subtle text-primary">Default</span>
                                @endif
                            </div>
                            <div class="small text-muted mb-1">
                                {{ $address->recipient_name }} @if($address->phone) • {{ $address->phone }} @endif
                            </div>
                            <div class="small">
                                {!! nl2br(e($address->address_line)) !!}
                                @if ($address->city)
                                    <br>{{ $address->city }} {{ $address->postal_code }}
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button
                                class="btn btn-sm btn-outline-secondary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#address-edit-{{ $address->id }}"
                                aria-expanded="false"
                            >
                                Ubah
                            </button>
                            <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                            @unless ($address->is_default)
                                <form action="{{ route('profile.addresses.default', $address) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-primary">Jadikan Default</button>
                                </form>
                            @endunless
                        </div>
                    </div>

                    <div class="collapse mt-3" id="address-edit-{{ $address->id }}">
                        <form action="{{ route('profile.addresses.update', $address) }}" method="POST" class="row g-2">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6">
                                <label class="form-label small">Label</label>
                                <input type="text" name="label" value="{{ $address->label }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Nama Penerima</label>
                                <input type="text" name="recipient_name" value="{{ $address->recipient_name }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Nomor HP</label>
                                <input type="text" name="phone" value="{{ $address->phone }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Kota</label>
                                <input type="text" name="city" value="{{ $address->city }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Kode Pos</label>
                                <input type="text" name="postal_code" value="{{ $address->postal_code }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Alamat Lengkap</label>
                                <textarea name="address_line" rows="3" class="form-control form-control-sm" required>{{ $address->address_line }}</textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-sm btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border">
                    Belum ada alamat tersimpan.
                </div>
            @endforelse
        </div>
    </div>
</x-profile-layout>
