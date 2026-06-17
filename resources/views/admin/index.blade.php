@extends('layouts.app')
@section('content')
<div x-data="adminPanel()" class="space-y-6">
    {{-- Tabs --}}
    <div class="flex border-b border-gray-150 dark:border-slate-800 gap-2 overflow-x-auto">
        <a href="/admin-panel" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-emerald-600 text-emerald-700 dark:text-emerald-400 whitespace-nowrap">{{ __("ui.user_control") }}</a>
        <a href="/admin-panel/subscriptions" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">{{ __("ui.manage_subscriptions") }}</a>
        <a href="/admin-panel/security-log" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">{{ __("ui.security_log") }}</a>
    </div>

    {{-- User Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-950/50 border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6">{{ __("ui.name_identifier") }}</th>
                        <th class="py-4 px-6">{{ __("ui.email_address") }}</th>
                        <th class="py-4 px-6">{{ __("ui.registration_date") }}</th>
                        <th class="py-4 px-6">{{ __("ui.assigned_role") }}</th>
                        <th class="py-4 px-6 text-center">{{ __("ui.update_permissions") }}</th>
                        <th class="py-4 px-6 text-center">{{ __("ui.actions") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                <div>
                                    <span class="font-bold text-gray-900 dark:text-white block">{{ $user->name }}</span>
                                    <span class="block text-[10px] text-gray-400 font-mono">ID: user-{{ $user->role === 'admin' ? 'admin' : 'standard' }}-{{ $user->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-gray-600 dark:text-gray-400 font-medium">{{ $user->email }}</td>
                        <td class="py-4 px-6 text-gray-400">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</td>
                        <td class="py-4 px-6">
                            @if($user->role === 'admin')
                            <div class="flex flex-col gap-1 items-start">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                    Admin
                                </span>
                                <span class="inline-flex items-center text-[9px] font-bold uppercase text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 px-1.5 py-0.5 rounded-md">{{ __("ui.verified") }}</span>
                            </div>
                            @else
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                User
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <form method="POST" action="/admin-panel/users/{{ $user->id }}/toggle-role" class="inline">
                                    @csrf @method('PATCH')
                                    @if($user->role === 'admin')
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-slate-700 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.demote_to_user") }}</button>
                                    @else
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-emerald-200 dark:border-emerald-800 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 transition-all cursor-pointer">{{ __("ui.promote_to_admin") }}</button>
                                    @endif
                                </form>
                                <button @click="openPermissions({{ $user->id }})" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-slate-700 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer gap-1">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68 1.65 1.65 0 0 0 9 3V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                    Permissions
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEditUser({{ json_encode(['id'=>$user->id, 'name'=>$user->name, 'email'=>$user->email]) }})" class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer text-xs">✏️</button>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="/admin-panel/users/{{ $user->id }}" onsubmit="return confirm('Delete this user permanently?')">
                                    @csrf @method('DELETE')
                                    <button class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all cursor-pointer text-xs">🗑️</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Edit User Credentials Modal --}}
    <div x-show="showEditUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showEditUserModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-5 flex items-center justify-between">
                <h3 class="text-white font-black text-base flex items-center gap-2">
                    <span>✏️</span>
                    <span>{{ __("ui.edit_user_credentials") }}</span>
                </h3>
                <button @click="showEditUserModal=false" class="text-white/80 hover:text-white text-xl cursor-pointer">✕</button>
            </div>
            <form :action='/admin-panel/users/' + editUserForm.id" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.full_name") }}</label>
                    <input type="text" name="name" x-model="editUserForm.name" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.email_address") }}</label>
                    <input type="email" name="email" x-model="editUserForm.email" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.reset_password") }}</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <p class="text-[10px] text-gray-400 mt-1">Optional. Enter a new password if you want to reset this user's password.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showEditUserModal=false" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.cancel") }}</button>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all cursor-pointer shadow-sm">{{ __("ui.save_transaction") }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Permissions Modal --}}
    <div x-show="showPermModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showPermModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden max-h-[90vh] flex flex-col">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-5 flex items-center justify-between shrink-0">
                <h3 class="text-white font-black text-base flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09"/></svg>
                    Module Permissions: <span x-text="permUser.name"></span>
                </h3>
                <button @click="showPermModal=false" class="text-white/80 hover:text-white text-xl cursor-pointer">✕</button>
            </div>

            {{-- User info --}}
            <div class="px-6 py-3 border-b border-gray-100 dark:border-slate-800 shrink-0">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-bold">Email:</span> <span x-text="permUser.email"></span>
                    • <span class="font-bold">Role:</span> <span x-text="permUser.role === 'admin' ? 'Admin (Administrators have unconditional full permissions override)' : 'User'"></span>
                </p>
            </div>

            {{-- Permissions Table --}}
            <div class="overflow-y-auto flex-1 px-6 py-4">
                <form :action="'/admin-panel/users/' + permUser.id + '/permissions'" method="POST" id="permForm">
                    @csrf
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-gray-500 uppercase text-[10px] font-bold tracking-wider">
                                <th class="text-left py-3 pr-4">System Module Name</th>
                                <th class="text-center py-3 w-16">View</th>
                                <th class="text-center py-3 w-16">Create</th>
                                <th class="text-center py-3 w-16">Edit</th>
                                <th class="text-center py-3 w-16">Delete</th>
                            </tr>
                        </thead>
                            @foreach($modules as $mod)
                            <tr class="{{ $mod['level'] === 'main' ? 'bg-gray-50/30 dark:bg-slate-800/20' : '' }}">
                                <td class="py-3 pr-4 {{ $mod['level'] === 'sub' ? 'pl-8' : '' }}">
                                    <div class="flex items-start gap-2">
                                        @if($mod['level'] === 'sub')
                                        <span class="text-gray-300 dark:text-slate-600 text-xs mt-0.5">└──</span>
                                        @endif
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white text-[11px]">
                                                @if($mod['icon'])<span class="mr-1">{{ $mod['icon'] }}</span>@endif
                                                {{ $mod['name'] }}
                                            </span>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $mod['desc'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                @foreach(['view', 'create', 'edit', 'delete'] as $perm)
                                <td class="text-center py-3">
                                    @if(in_array($perm, $mod['perms']))
                                    <input type="checkbox"
                                        :name="'permissions[{{ $mod['key'] }}][' + '{{ $perm }}' + ']'"
                                        value="1"
                                        :checked="permData['{{ $mod['key'] }}']?.{{ $perm }}"
                                        class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                    @else
                                    <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                <button @click="showPermModal=false" type="button" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.cancel") }}</button>
                <button type="submit" form="permForm" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all cursor-pointer shadow-sm">{{ __("ui.apply_permissions") }}</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
<script>
function adminPanel() {
    return {
        showPermModal: false,
        showEditUserModal: false,
        editUserForm: { id: null, name: '', email: '' },
        permUser: { id: null, name: '', email: '', role: '' },
        permData: {},

        openEditUser(user) {
            this.editUserForm = user;
            this.showEditUserModal = true;
        },

        async openPermissions(userId) {
            try {
                const res = await fetch('/admin-panel/users/' + userId + '/permissions');
                const data = await res.json();
                this.permUser = data.user;
                this.permData = data.permissions;
                this.showPermModal = true;
            } catch (e) {
                alert('Failed to load permissions');
            }
        }
    }
}
</script>
@endsection
