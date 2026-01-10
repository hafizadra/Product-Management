@php
    $isEdit = isset($product) && $product->exists;
@endphp

<x-layout :title="$isEdit ? 'Edit Produk - ' . $product->name : 'Produk Baru'">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h5 mb-0">
                        {{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}
                    </h1>
                    <p class="text-muted small mb-0">
                        Pastikan data, harga, dan stok sesuai kondisi terbaru.
                    </p>
                </div>

                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                    ← Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger small">
                    <strong>Periksa kembali data yang diisi:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form
                        action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
                        method="POST"
                        class="vstack gap-3"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div>
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $isEdit ? $product->name : '') }}"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Author</label>
                                <input
                                    type="text"
                                    name="author"
                                    value="{{ old('author', $isEdit ? $product->author : '') }}"
                                    class="form-control"
                                >
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Publisher</label>
                                <input
                                    type="text"
                                    name="publisher"
                                    value="{{ old('publisher', $isEdit ? $product->publisher : '') }}"
                                    class="form-control"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">ISBN</label>
                            <input
                                type="text"
                                name="isbn"
                                value="{{ old('isbn', $isEdit ? $product->isbn : '') }}"
                                class="form-control"
                            >
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea
                                name="description"
                                rows="3"
                                class="form-control"
                                required
                            >{{ old('description', $isEdit ? $product->description : '') }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Harga (Rp)</label>
                                <input
                                    type="number"
                                    name="price"
                                    min="0"
                                    value="{{ old('price', $isEdit ? $product->price : '') }}"
                                    class="form-control"
                                    required
                                >
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stok</label>
                                <input
                                    type="number"
                                    name="stock"
                                    min="0"
                                    value="{{ old('stock', $isEdit ? $product->stock : '') }}"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $isEdit ? $product->category_id : '') == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Foto Produk</label>
                            <input
                                type="file"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/*"
                            >
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($isEdit && $product->image_url)
                                <div class="mt-2">
                                    <img
                                        src="{{ $product->image_url }}"
                                        alt="{{ $product->name }}"
                                        class="img-fluid rounded border"
                                        style="max-height: 200px; object-fit: cover;"
                                    >
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Simpan perubahan' : 'Simpan produk' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
