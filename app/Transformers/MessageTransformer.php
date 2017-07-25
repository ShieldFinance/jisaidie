<?php
namespace App\Transformers;
use League\Fractal\TransformerAbstract;
use App\Http\Models\Message;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        return [
            'id'         => $message->id,
            'message'       => $message->message,
            'subject'       => $message->subject,
            'is_read'       => $message->is_read,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
        ];
    }
}