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
 * @property string|null $description
 * @property string|null $begin_time 活动开始日期
 * @property string|null $end_time 活动结束日期
 * @property string $pic_url 海报
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $friendly_begin_time
 * @property-read mixed $friendly_end_time
 * @property-read mixed $full_pic_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Participant[] $participants
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Team[] $teams
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Activity onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereBeginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Activity whereId($value)
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
 * App\Entities\Reminder
 *
 * @property int $id
 * @property int $user_id
 * @property string $time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Reminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Reminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Reminder whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Reminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Reminder whereUserId($value)
 */
	class Reminder extends \Eloquent {}
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
 * App\Entities\SignRecord
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\SignRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\SignRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\SignRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\SignRecord whereUserId($value)
 */
	class SignRecord extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\Team
 *
 * @property int $id
 * @property int $activity_id
 * @property string $name
 * @property int $user_id 创建者用户 id
 * @property int $count 参与人数
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Entities\Activity $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Participant[] $participants
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Team whereCount($value)
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

namespace App\Entities{
/**
 * App\Entities\Wordbook
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\WordbookContent[] $contents
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Wordbook onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Wordbook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Wordbook whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Wordbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Wordbook whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Wordbook whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Wordbook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Wordbook withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Entities\Wordbook withoutTrashed()
 */
	class Wordbook extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\WordbookContent
 *
 * @property int $id
 * @property int $wordbook_id
 * @property string $facade
 * @property string $back
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\WordRecord[] $wordRecord
 * @property-read \App\Entities\Wordbook $wordbook
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookContent whereBack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookContent whereFacade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookContent whereWordbookId($value)
 */
	class WordbookContent extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\WordbookState
 *
 * @property int $id
 * @property int $user_id
 * @property int $wordbook_id
 * @property int $word_total
 * @property int $remember_total
 * @property int $remembered_wordbook_total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $is_over
 * @property-read \App\User $user
 * @property-read \App\Entities\Wordbook $wordbook
 * @property-read \App\Entities\WordbookContent $wordbookContent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereRememberTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereRememberedWordbookTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereWordTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordbookState whereWordbookId($value)
 */
	class WordbookState extends \Eloquent {}
}

namespace App\Entities{
/**
 * App\Entities\WordRecord
 *
 * @property int $id
 * @property int $user_id
 * @property int $wordbook_id
 * @property int $wordbook_content_id
 * @property int $status 0 不认识 1 认识 2 模糊
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @property-read \App\Entities\Wordbook $wordbook
 * @property-read \App\Entities\WordbookContent $wordbookContent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereWordbookContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\WordRecord whereWordbookId($value)
 */
	class WordRecord extends \Eloquent {}
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
 * @property-read mixed $sign_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Participant[] $participants
 * @property-read \App\Entities\Reminder $reminder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\SignRecord[] $signRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Team[] $teams
 * @property-read \App\Entities\WechatUser $wechatUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\WordRecord[] $wordbookRecord
 * @property-read \App\Entities\WordbookState $wordbookState
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

