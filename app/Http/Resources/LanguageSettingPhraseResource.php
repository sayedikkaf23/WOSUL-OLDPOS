<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LanguageSettingPhraseResource extends Resource
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
            'slack' => $this->slack,
            'lang_setting_id' => $this->lang_setting_id,
            'lang_phrase' => $this->lang_phrase,
            'lang_label' => $this->lang_label,
            'status' => new MasterStatusResource($this->status_data),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
