<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->toName($this->name),
            "apiRoute" => $this->name,
        ];
    }

    public function toName(string $routeName) : string
    {
        $parts = explode(".", $routeName);

        $actionWords = [
            'import' => 'Import',
            'export' => 'Export',
            'delete' => 'Delete',
            'create' => 'Create',
            'get' => 'View',
            'update' => 'Update',
            'information' => 'Information',
            'updatePassword' => 'Change Password',
            'sendChangeEmail' => 'Send Email Change Request',
            'verifyChangeEmail' => 'Verify Email Change',
            'accessHistory' => 'Access History',
            'updateAvatar' => 'Update Profile Picture',

        ];

        $modelWords = [
            'users' => 'Users',
            'user' => 'User',
            'permissions' => 'Permissions',
            'importStatus' => 'Import Status'
        ];

        if ($parts[0] === 'api') {
            if ($parts[1] === 'admin') {
                if (isset($modelWords[$parts[2]])) {
                    $model = $modelWords[$parts[2]];
                    if (isset($parts[3]) && isset($actionWords[$parts[3]])) {
                        return "Admin {$actionWords[$parts[3]]} {$model}";
                    }
                    return "Admin Get List Of {$model}";
                }
                if ($parts[2] === 'user') {
                    if (isset($parts[3]) && isset($actionWords[$parts[3]])) {
                        return "Admin {$actionWords[$parts[3]]} User";
                    }
                }
            }
            if ($parts[1] === 'user') {
                    $action = $parts[2];
                    if (isset($actionWords[$action])) {
                        return "User {$actionWords[$action]}";
                    }
            }
        }
        return $routeName;
    }
}
