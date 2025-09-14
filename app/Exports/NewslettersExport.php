<?php

namespace App\Exports;

use App\Models\Newsletter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewslettersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Newsletter::select('id', 'email', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'Subscribed At',
        ];
    }
}
