<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $profile_image = config('constants.upload.profile.default');
        if ($this->profile_image != '') {
            $profile_image = config('constants.upload.profile.view_path') . 'medium_' . $this->profile_image;
        }
        return [
            'id' => $this->id,
            'slack' => $this->slack,
            'user_code' => $this->user_code,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'login_code' => $this->login_code,
            'phone' => $this->phone,
            'is_admin' => $this->is_admin,
            'is_master' => $this->is_master,
            'is_cashier' => $this->is_cashier,
            'role' => new RoleResource($this->role),
            'status' => new MasterStatusResource($this->status_data),
            'profile_images' => $this->profile_image,
            'profile_image' => $profile_image,
            'detail_link' => (check_access(['A_DETAIL_USER'], true)) ? route('user', ['slack' => $this->slack]) : '',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => $this->createdUser,
            'updated_by' => $this->updatedUser,
        ];
    }
}
