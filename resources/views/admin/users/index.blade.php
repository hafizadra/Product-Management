<x-layout title="Admin · Users">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h5 mb-1">Users</h1>
            <p class="text-muted small mb-0">Kelola daftar pengguna dan role admin.</p>
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
                    @foreach ($users as $user)
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
                    @endforeach
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
