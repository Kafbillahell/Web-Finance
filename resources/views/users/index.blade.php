@extends('layouts.default')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Data User</h5>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">No</th>
                        <th class="text-left px-4 py-2 border-b">Nama</th>
                        <th class="text-left px-4 py-2 border-b">Email</th>
                        <th class="text-left px-4 py-2 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">1</td>
                        <td class="px-4 py-2 border-b">Azhar</td>
                        <td class="px-4 py-2 border-b truncate max-w-xs">azhar@email.com</td>
                        <td class="px-4 py-2 border-b">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs ml-1">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection