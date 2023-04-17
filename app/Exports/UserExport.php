<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order as OrderModel;;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

use App\Http\Resources\UserResource;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class UserExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public $from_created_date, $to_created_date, $sales;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $role = $this->data['role'];
        $status = $this->data['status'];

        $query = User::query()
        ->select('users.*', 'roles.id','users.id as user_id')
        ->roleJoin()
        ->hideSuperAdminRole();

        if($this->from_created_date != ''){
            $this->from_created_date = $this->from_created_date.' 00:00:00';
            $query = $query->where('users.created_at', '>=', $this->from_created_date);
        }
        if($this->to_created_date != ''){
            $this->to_created_date = $this->to_created_date.' 23:59:59';
            $query = $query->where('users.created_at', '<=', $this->to_created_date);
        }
        if($role != ''){
            $query = $query->where('roles.slack', $role);
        }
        if(isset($status)){
            $query = $query->where('users.status', $status);
        }

        $users = $query->get();


        // $this->sales = [];
        // if(isset($users)){
        //     foreach($users as $user){
        //         $this->sales[$user['slack']] = OrderModel::withoutGlobalScopes()->closed()
        //         ->where('orders.created_by',$user->user_id)
        //         ->where('created_at', '<=', $this->to_created_date)
        //         ->where('created_at', '>=', $this->from_created_date)
        //         ->sum('orders.total_order_amount_rounded');
        //     }
        // }
        return $users;
    }

    public function headings(): array
    {
        return [
            trans('USER CODE'),
            trans('FULL NAME'),
            trans('EMAIL'),
            trans('PHONE'),
            trans('ROLE'),
            // trans('TOTAL SALES'),
            trans('STATUS'),
            trans('FROM'),
            trans('TO'),
        ];
    }

    public function map($user): array
    {
        $user = collect(new UserResource($user));
        
        return [
            (isset($user['user_code']))?$user['user_code']:'',
            (isset($user['fullname']))?$user['fullname']:'',
            (isset($user['email']))?$user['email']:'',
            (isset($user['phone']))?$user['phone']:'',
            (isset($user['role']['label']))?$user['role']['label']:'',
            // (isset($user['slack']) && $this->sales[$user['slack']] != null)?$this->sales[$user['slack']]:'0',
            (isset($user['status']['label']))?$user['status']['label']:'',
            (isset($this->from_created_date) && $this->from_created_date != null) ? Carbon::parse($this->from_created_date)->toDateString() : '',
            (isset($this->to_created_date) && $this->to_created_date != null) ? Carbon::parse($this->to_created_date)->toDateString() : ''
        ];
    }
}
