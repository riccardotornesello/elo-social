<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeagueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'creation' => $this->created_at->toDateTimeString(),
            'team' => $this->whenPivotLoaded('teams', function () {
                return [
                    'id' => $this->pivot->id,
                    'name' => $this->pivot->name,
                    'rating' => $this->pivot->rating,
                    'role' => $this->pivot->role,
                    'join' => $this->created_at->toDateTimeString(),
                ];
            })
        ];
    }
}
