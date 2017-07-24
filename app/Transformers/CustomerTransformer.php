<?php
namespace App\Transformers;
use League\Fractal\TransformerAbstract;
use App\Http\Models\Customer;

class CustomerTransformer extends TransformerAbstract
{
    public function transform(Customer $customer)
    {
        return [
            'id'         => $customer->id,
            'surname'       => $customer->surname,
            'last_name'       => $customer->last_name,
            'other_name'       => $customer->other_name,
            'status'         => $customer->status,
            'activation_code' =>$customer->activation_code,
            'mobile_number'=>$customer->mobile_number,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ];
    }
}