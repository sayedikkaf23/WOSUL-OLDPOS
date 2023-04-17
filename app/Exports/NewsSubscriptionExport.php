<?php

namespace App\Exports;

use App\Http\Resources\NewsSubscriptionResource;
use App\Models\NewsSubscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class NewsSubscriptionExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->rows_count = 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $status = $this->data['order_status'];
        $query = NewsSubscription::query();

        if($from_created_date != ''){
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            // $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('newsletter_subscription.created_on', '>=', $from_created_date);
        }
        if($to_created_date != ''){
            // $to_created_date = strtotime($to_created_date);
            // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            // $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('newsletter_subscription.created_on', '<=', $to_created_date);
        }
        if($status!=""){
            $query = $query->where('newsletter_subscription.status','=',$status);
        }
       

        $subscription = $query->orderBy('created_on', 'desc')->get();

        $this->rows_count = $query->count() + 3;
        return $subscription;
    }

    public function headings(): array   
    {
        return [
            trans('Email'),
            trans('Created On'),
        ];
    }

    public function map($subscription): array
    {
        $subscription = collect(new NewsSubscriptionResource($subscription));
        return [
            (isset($subscription['email']))?$subscription['email']:'',
            (isset($subscription['created_on']))?$subscription['created_on']:'',
            ];
    }
  
}
