<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            'Email',
            'Name',
            'Role',
        ];
    }

    public function map($user): array
    {
        return [
            $user->email,
            $user->name,
            ucfirst($user->role), // Kapitalisasi awal huruf role
        ];
    }
}
