<?php

namespace App\Exports;

use App\Filters\UserFilter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\User\Entities\TenantUser;

class UsersReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filter;
    function __construct(Request $request)
    {
        $this->filter = new UserFilter($request);
    }

    public function collection() {
        return $users = TenantUser::join('role_user', 'users.id', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->groupBy('users.name', 'users.email')
            ->select('users.name', 'users.email', DB::raw('GROUP_CONCAT(roles.name SEPARATOR \', \') as roles_name'))
            ->get();
    }

    public function headings(): array
    {
        return [
            'NAME',
            'EMAIL',
            'ROLES',
        ];
    }

    /**
     * @return array
     */
    public function csvHeadings(): array
    {
        return [
            'name',
            'email',
            'roles_name',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ],
                    ]
                ];
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
            },
        ];
    }
}
