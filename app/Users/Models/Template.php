<?php

namespace BookStack\Users\Models;

use BookStack\Activity\Models\Loggable;
use BookStack\App\Model;
use BookStack\Permissions\Models\EntityPermission;
use BookStack\Permissions\Models\JointPermission;
use BookStack\Permissions\Models\RolePermission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Role.
 *
 * @property int        $id
 * @property string     $display_name
 * @property string     $description
 * @property string     $external_auth_id
 * @property string     $system_name
 * @property bool       $mfa_enforced
 * @property Collection $users
 */
class Template extends Model
{
    use HasFactory;

    protected $table = 'template';

    protected $fillable = ['name', 'path', 'isActive',];

    /**
     * The roles that belong to the role.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
