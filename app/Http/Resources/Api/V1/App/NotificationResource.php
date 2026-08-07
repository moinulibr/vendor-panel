<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'body'           => $this->body,
            'type'           => $this->type,
            'target_channel' => $this->target_channel,
            'data'           => $this->data ?? (object)[],
            'is_read'        => $this->read_at !== null,
            'read_at'        => $this->read_at?->toIso8601String(),
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }
}