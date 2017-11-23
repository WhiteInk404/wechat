<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Cache;
use DB;

class Role extends EntrustRole
{
    /**
     * 患者
     */
    const PATIENT = 'Patient';

    /**
     * 医生
     */
    const DOCTOR = 'Doctor';

    /**
     * 社区医生
     */
    const COMMUNITY_DOCTOR = 'CommunityDoctor';

    /**
     * 导师
     */
    const ADVISOR = 'Advisor';
    /**
     * 专家
     */
    const EXPERT = 'Expert';

    protected $fillable = ['name', 'display_name', 'description'];

    public static function addRole($name, $display_name = null, $description = null)
    {
        $role = Role::query()->where('name', $name)->first();
        if (!$role) {
            $role = new Role(['name' => $name]);
        }
        $role->display_name = $display_name;
        $role->description  = $description;
        $role->save();

        return $role;
    }

    public static function getByName($name)
    {
        $role = Role::where('name', $name)->first();
        return $role;
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
