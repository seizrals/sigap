<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAccess($request);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', Rule::in(User::manageableRoles())],
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatan,id'],
        ]);

        $users = User::query()
            ->with('kecamatan:id,nama_kecamatan')
            ->when($filters['q'] ?? null, function ($query, string $search) {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('name', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['role'] ?? null, fn ($query, string $role) => $query->where('role', $role))
            ->when($filters['kecamatan_id'] ?? null, fn ($query, int $kecamatanId) => $query->where('kecamatan_id', $kecamatanId))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan')
            ->orderBy('nama_kecamatan')
            ->get();

        return view('users.index', [
            'users' => $users,
            'kecamatan' => $kecamatan,
            'filters' => $filters,
            'roleOptions' => User::roleOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAccess($request);

        $validated = $this->validatePayload($request);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?: null,
            'password' => $validated['password'],
            'role' => $validated['role'],
            'kecamatan_id' => $validated['role'] === User::ROLE_KECAMATAN ? $validated['kecamatan_id'] : null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorizeAccess($request);

        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan')
            ->orderBy('nama_kecamatan')
            ->get();

        return view('users.edit', [
            'user' => $user->load('kecamatan:id,nama_kecamatan'),
            'kecamatan' => $kecamatan,
            'roleOptions' => User::roleOptions(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAccess($request);

        $validated = $this->validatePayload($request, $user);

        $payload = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?: null,
            'role' => $validated['role'],
            'kecamatan_id' => $validated['role'] === User::ROLE_KECAMATAN ? $validated['kecamatan_id'] : null,
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAccess($request);

        abort_if($request->user()->is($user), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    private function authorizeAccess(Request $request): void
    {
        abort_unless($request->user()?->canManageUsers(), 403);
    }

    private function validatePayload(Request $request, ?User $user = null): array
    {
        $passwordRules = $user === null
            ? ['required', 'string', 'min:8', 'confirmed']
            : ['nullable', 'string', 'min:8', 'confirmed'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => ['required', 'string', Rule::in(User::manageableRoles())],
            'kecamatan_id' => [
                Rule::requiredIf(fn (): bool => $request->input('role') === User::ROLE_KECAMATAN),
                'nullable',
                'integer',
                'exists:kecamatan,id',
            ],
            'password' => $passwordRules,
        ], [
            'kecamatan_id.required' => 'Wilayah kecamatan wajib dipilih untuk user per wilayah.',
        ]);
    }
}
