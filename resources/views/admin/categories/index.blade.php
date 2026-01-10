<x-layout title="Admin · Categories">
    <nav aria-label="breadcrumb" class="small text-muted mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h5 mb-1">Categories</h1>
            <p class="text-muted small mb-0">Organize product categories for the catalog.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            + Category
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Products</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products()->count() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <form
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this category?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <div class="mb-2">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 5h16v14H4z" stroke="currentColor" stroke-width="1.4"/>
                                        <path d="M9 2v4M15 2v4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <p class="small mb-2">No categories yet.</p>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                                    + Category
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="card-footer">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-layout>
