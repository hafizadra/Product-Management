

@php
    $isEdit = isset($product) && $product->exists;
@endphp

<x-layout :title="$isEdit ? 'Edit Product - ' . $product->name : 'Add New Product'">

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h5 mb-0">
                    {{ $isEdit ? 'Edit Product' : 'Add New Product' }}
                </h1>

                <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary">
                    ← Back
                </a>
            </div>

            {{-- TAMPILKAN ERROR VALIDASI (kalau ada) --}}
            @if ($errors->any())
                <div class="alert alert-danger small">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- CARD FORM --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    
                    <form 
                        action="{{ $isEdit ? route('products.update', $product->id) : route('products.store') }}" 
                        method="POST"
                    >
                        @csrf

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                value="{{ old('name', $isEdit ? $product->name : '') }}"
                                required
                            >
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea 
                                name="description" 
                                class="form-control" 
                                rows="3"
                                required
                            >{{ old('description', $isEdit ? $product->description : '') }}</textarea>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Price (Rp)</label>
                            <input 
                                type="number" 
                                name="price" 
                                class="form-control"
                                min="0"
                                value="{{ old('price', $isEdit ? $product->price : '') }}"
                                required
                            >
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option 
                                        value="{{ $category->id }}"
                                        {{ old('category_id', $isEdit ? $product->category_id : '') == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">
                                {{ $isEdit ? 'Save Changes' : 'Create Product' }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-layout>
