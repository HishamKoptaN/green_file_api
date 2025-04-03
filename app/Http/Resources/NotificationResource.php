<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'image' => $this->image,
            'data' => json_decode($this->data, true),
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'is_read' => $this->whenPivotLoaded(
                'notification_recipients',
                function () {
                    return (bool) $this->pivot->is_read;
                }
            ),
            'read_at' => $this->whenPivotLoaded(
                'notification_recipients',
                function () {
                    return $this->pivot->read_at
                        ? Carbon::parse($this->pivot->read_at)->toDateTimeString()
                        : null;
                }
            ),
        ];
    }
}
