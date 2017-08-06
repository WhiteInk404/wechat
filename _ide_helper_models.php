<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Entities{
/**
 * App\Entities\Activity
 *
 * @property int $id
 * @property string $name
 * @property string|null $begin_time 活动开始日期
 * @property string|null $end_time 活动结束日期
 * @property string $pic_url 海报
 * @property string $labels 标签规则，英文逗号分隔
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Participant[] $participants
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Team[] $teams
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Activity onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereBeginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereLabels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Activity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Activity withoutTrashed()
 */
	class Activity extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\Participant
 *
 * @property int $id
 * @property int $activity_id
 * @property int $team_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Entities\Activity $activity
 * @property-read \App\Entities\Team $team
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Participant whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Participant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Participant whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Participant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Participant whereUserId($value)
 */
	class Participant extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\Permission
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Permission[] $perms
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\Team
 *
 * @property int $id
 * @property int $activity_id
 * @property string $name
 * @property int $user_id 创建者用户 id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Entities\Activity $activity
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereUserId($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\WechatUser
 *
 * @property int $id
 * @property int $user_id `users`.`id`
 * @property string $openid openid
 * @property string $nickname nickname
 * @property int $gender 0 未知 1 男 2 女
 * @property string $city 城市
 * @property string $province 省份
 * @property string $country 国家
 * @property string $avatar_url 微信头像地址
 * @property string $union_id union id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereUnionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WechatUser whereUserId($value)
 */
	class WechatUser extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property int $passport_type 账户类型，0，普通，1 公众号，2 小程序
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Activity[] $activities
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Team[] $teams
 * @property-read \App\Entities\WechatUser $wechatUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

