@php
    $isEdit = isset($category) && $category->exists;
@endphp

<x-layout :title="$isEdit ? 'Edit Category' : 'Add Category'">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h5 mb-0">
                    {{ $isEdit ? 'Edit Category' : 'Add Category' }}
                </h1>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    ← Back
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form
                        action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                        method="POST"
                        class="vstack gap-3"
                    >
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div>
                            <label class="form-label fw-semibold">Name</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $isEdit ? $category->name : '') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary">
                                {{ $isEdit ? 'Save Changes' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
