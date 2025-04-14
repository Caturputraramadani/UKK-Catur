
@extends('layouts.main')
@section('container')

    <div class="flex items-center text-gray-500 text-sm ml-2">
        <i class="ti ti-home text-xl"></i>
        <i class="ti ti-chevron-right text-xl mx-2"></i>
        <span class="text-gray-500 font-semibold text-lg">User</span>
    </div>

    <h1 class="text-gray-700 text-2xl font-bold ml-2">USER</h1>
    <div class="card">
        <div class="card-body">

            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('users.export') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full mb-3">
                    Export User (.xlsx)
                </a>
                <a href="javascript:;" class="btn btn-primary waves-effect btn-label waves-light" onclick="openUserModal()">Add User</a>
            </div>

            <div class="relative overflow-x-auto">
                <table class="text-left w-full whitespace-nowrap text-sm text-gray-500">
                    <thead>
                        <tr class="text-sm">
                            <th class="p-4 font-semibold">#</th>
                            <th class="p-4 font-semibold">EMAIL</th>
                            <th class="p-4 font-semibold">NAMA</th>
                            <th class="p-4 font-semibold">ROLE</th>
                            <th class="p-4 font-semibold">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="p-4">
                                    <h3 class="font-medium">{{ $loop->iteration }}</h3>
                                </td>
                                <td class="p-4">
                                    <h3 class="font-medium">{{ $user->email }}</h3>
                                </td>
                                <td class="p-4">
                                    <h3 class="font-medium">{{ $user->name }}</h3>
                                </td>
                                <td class="p-4">
                                    <h3 class="font-medium text-teal-500  capitalize">{{ $user->role }}</h3>
                                </td>
                                <td class="p-4">
                                    <div
                                        class="hs-dropdown relative inline-flex [--placement:bottom-right] sm:[--trigger:hover]">
                                        <a class="relative hs-dropdown-toggle cursor-pointer align-middle rounded-full">
                                            <i class="ti ti-dots-vertical text-2xl text-gray-400"></i>
                                        </a>
                                        <div
                                            class="card hs-dropdown-menu transition-[opacity,margin] rounded-md duration hs-dropdown-open:opacity-100 opacity-0 mt-2 min-w-max w-[150px] hidden z-[12]">
                                            <div class="card-body p-0 py-2">
                                                <a href="javascript:void(0)"
                                                    onclick="openUserModal({{ json_encode($user) }})"
                                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-400">
                                                    <p class="text-sm">Edit</p>
                                                </a>
                                                <a href="javascript:void(0)" onclick="deleteUser('{{ $user->id }}')"
                                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-400">
                                                    <p class="text-sm">Delete</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">User not found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit Data -->
    <div id="dataModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden z-[999]">
        <div class="bg-white rounded-lg p-6 w-full sm:w-96 max-w-md">
            <div class="flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add User</h3>
                <button type="button" onclick="closeUserModal()"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form id="userForm" action="{{ route('users.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                    <input type="email" id="email" name="email"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-4">
                    <label for="role" class="block text-sm font-medium text-gray-900">Role</label>
                    <select id="role" name="role"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="employee">Employee</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeUserModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
