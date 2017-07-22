<?php
namespace App\Transformers;
use League\Fractal\TransformerAbstract;
use App\Http\Models\Screen;

class ScreenTransformer extends TransformerAbstract
{
    public function transform(Screen $screen)
    {
        return [
            'id'         => $screen->id,
            'title'       => $screen->title,
            'message'       => $screen->message,
            'icon'         => $screen->icon,
        ];
    }
}