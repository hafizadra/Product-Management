<x-layout title="Admin · Users">
    <nav aria-label="breadcrumb" class="small text-muted mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h5 mb-1">Users</h1>
            <p class="text-muted small mb-0">Manage registered users and their admin roles.</p>
        </div>

        <form class="d-flex gap-2" method="GET">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                class="form-control form-control-sm"
                placeholder="Search name or email"
            >
            <button class="btn btn-sm btn-outline-secondary">Search</button>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->is_admin ? 'text-bg-primary' : 'text-bg-light' }}">
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if (auth()->id() !== $user->id)
                                    <form
                                        action="{{ route('admin.users.toggle-admin', $user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Change role for {{ $user->name }}?');"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-primary">
                                            {{ $user->is_admin ? 'Remove admin' : 'Make admin' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">You</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <div class="mb-2">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 12a5 5 0 100-10 5 5 0 000 10z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                        <path d="M4 21a8 8 0 0116 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <p class="small mb-0">No users found with the current filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layout>
