@php
    $isEdit = $coupon->exists;
@endphp

<x-layout :title="$isEdit ? 'Edit Coupon' : 'New Coupon'">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h5 mb-1">{{ $isEdit ? 'Edit Coupon' : 'New Coupon' }}</h1>
            <p class="text-muted small mb-0">Configure discount rules and limits.</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">← Back</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form method="POST" action="{{ $isEdit ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="vstack gap-3">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
                        @error('code')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title) }}" required>
                        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $coupon->description) }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" class="form-select" required>
                            <option value="percent" @selected(old('discount_type', $coupon->discount_type) === 'percent')>Percent</option>
                            <option value="fixed" @selected(old('discount_type', $coupon->discount_type) === 'fixed')>Fixed amount</option>
                        </select>
                        @error('discount_type')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount Value</label>
                        <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" min="1" required>
                        @error('discount_value')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Max Discount</label>
                        <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}" min="1">
                        @error('max_discount')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Min Subtotal</label>
                        <input type="number" name="min_subtotal" class="form-control" value="{{ old('min_subtotal', $coupon->min_subtotal) }}" min="0">
                        @error('min_subtotal')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Starts at</label>
                        <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ends at</label>
                        <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', optional($coupon->ends_at)->format('Y-m-d\TH:i')) }}">
                        @error('ends_at')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Usage limit (total)</label>
                        <input type="number" name="usage_limit_total" class="form-control" value="{{ old('usage_limit_total', $coupon->usage_limit_total) }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Limit per user</label>
                        <input type="number" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" min="1">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Scope</label>
                        <select name="scope_type" class="form-select" id="scope-select">
                            <option value="all" @selected(old('scope_type', $coupon->scope_type) === 'all')>All products</option>
                            <option value="categories" @selected(old('scope_type', $coupon->scope_type) === 'categories')>Specific categories</option>
                            <option value="products" @selected(old('scope_type', $coupon->scope_type) === 'products')>Specific products</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Categories</label>
                        <select name="category_ids[]" class="form-select" multiple size="5">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(in_array($category->id, old('category_ids', $coupon->categories->pluck('id')->all())))>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_ids')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Products</label>
                        <select name="product_ids[]" class="form-select" multiple size="5">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected(in_array($product->id, old('product_ids', $coupon->products->pluck('id')->all())))>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_ids')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="free_shipping" value="1" id="freeShipping" @checked(old('free_shipping', $coupon->free_shipping))>
                    <label class="form-check-label" for="freeShipping">Free shipping</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="isActive" @checked(old('is_active', $coupon->is_active ?? true))>
                    <label class="form-check-label" for="isActive">Active</label>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary rounded-pill px-4">Save Coupon</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
