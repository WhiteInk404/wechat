<?php
namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\TagRule;
use App\Models\UserTag;
use App\Models\ReplyRule;
use App\Models\WxMenu;
use Auth;

class TagsController extends BaseController
{
    public function index()
    {
        $tags = Tag::paginate(10);
        $data = [
            'tags' => $tags,
        ];
        return view('admin.wechat.tags_index', $data);
    }

    public function create()
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $wxMenus = WxMenu::where('cid', $cid)->where('type', WxMenu::TYPE_DEFAULT)->first();
        $clickMenus = [];
        foreach ($wxMenus->button as $k => $butGroup) {
            if (property_exists($butGroup, 'sub_button') && $butGroup->sub_button) {
                $clickMenus[$k]['type'] = 'optgroup';
                $clickMenus[$k]['label'] = $butGroup->name;
                foreach ($butGroup->sub_button as $kk => $button) {
                    if ($button->type == 'click') {
                        $clickMenus[$k]['values'][$kk]['value'] = $button->key;
                        $clickMenus[$k]['values'][$kk]['text'] = $button->name;
                    } else if ($button->type == 'view') {
                        $clickMenus[$k]['values'][$kk]['value'] = $button->url;
                        $clickMenus[$k]['values'][$kk]['text'] = $button->name;
                    }
                }
            } else if ($butGroup->type == 'click') {
                $clickMenus[$k]['type'] = 'option';
                $clickMenus[$k]['value'] = $button->key;
                $clickMenus[$k]['text'] = $button->name;
            } else if ($butGroup->type == 'view') {
                $clickMenus[$k]['type'] = 'option';
                $clickMenus[$k]['value'] = $button->url;
                $clickMenus[$k]['text'] = $button->name;
            }
        }
        $fields = $this->getFields();
        $groupIds = array_keys($fields);
        foreach ($groupIds as $key => $groupId ) {
            if ($groupId == 'tag_type') {
                unset($groupIds[$key]);
            }
        }
        $groupIds[] = 'click_menu';
        $groupIds = json_encode($groupIds);
        $data = [
            'fields' => $fields,
            'groupIds' => $groupIds,
            'action' => '添加',
            'tagRuleId' => '0',
            'tagName' => false,
            'clickMenus' => $clickMenus,
        ];
        return view('admin.wechat.tags_edit', $data);
    }

    public function edit ($tagRuleId)
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $tagRule = TagRule::join('tags', 'tag_rules.tid', 'tags.id')
            ->where('tags.cid', $cid)
            ->where('tag_rules.id', $tagRuleId)
            ->select('tag_rules.id', 'tags.name', 'tag_rules.type', 'tag_rules.sub_type', 'tag_rules.rul')
            ->first();
        $fields = $this->getFields();
        $data = [];
        $clickMenuSelecteds = [];
        $data['clickMenus'] = [];
        if ($tagRule) {
            $fields['tag_type']['default'] = $tagRule->type;
            $fields[$tagRule->type]['default'] = $tagRule->sub_type;

            if ($tagRule->sub_type == 'sex' && is_object($tagRule->rul) && property_exists($tagRule->rul, 'sex')) {
                $fields['sex']['default'] = $tagRule->rul->sex;
            } else if ($tagRule->sub_type == 'city' && is_object($tagRule->rul) && property_exists($tagRule->rul, 'city')) {
                $fields['city']['default'] = $tagRule->rul->city;
            } else if ($tagRule->sub_type == 'province' && is_object($tagRule->rul) && property_exists($tagRule->rul, 'province')) {
                $fields['province']['default'] = $tagRule->rul->province;
            } else if ($tagRule->sub_type == 'key_word' && is_object($tagRule->rul) && property_exists($tagRule->rul, 'key_word')) {
                $fields['key_word']['default'] = $tagRule->rul->key_word;
                if ($tagRule->rul->key_word == 'complete_match' && property_exists($tagRule->rul, 'complete_match')) {
                    $fields['complete_match']['default'] = $tagRule->rul->complete_match;
                } else if ($tagRule->rul->key_word == 'fuzzy_match' && property_exists($tagRule->rul,'fuzzy_match')) {
                    $fields['fuzzy_match']['default'] = $tagRule->rul->fuzzy_match;
                }
            } else if ($tagRule->sub_type == 'click_menu') {
                if (is_object($tagRule->rul) && property_exists($tagRule->rul, 'click_menu') && is_array($tagRule->rul->click_menu)) {
                    $clickMenuSelecteds = $tagRule->rul->click_menu;
                }
            }
        }
        $wxMenus = WxMenu::where('cid', $cid)->where('type', WxMenu::TYPE_DEFAULT)->first();
        $clickMenus = [];
        foreach ($wxMenus->button as $k => $butGroup) {
            if (property_exists($butGroup, 'sub_button') && $butGroup->sub_button) {
                $clickMenus[$k]['type'] = 'optgroup';
                $clickMenus[$k]['label'] = $butGroup->name;
                foreach ($butGroup->sub_button as $kk => $button) {
                    if ($button->type == 'click') {
                        $clickMenus[$k]['values'][$kk]['value'] = $button->key;
                        if (in_array($button->key, $clickMenuSelecteds)) {
                            $clickMenus[$k]['values'][$kk]['selected'] = 'selected';
                        }
                        $clickMenus[$k]['values'][$kk]['text'] = $button->name;
                    } else if ($button->type == 'view') {
                        $clickMenus[$k]['values'][$kk]['value'] = $button->url;
                        if (in_array($button->url, $clickMenuSelecteds)) {
                            $clickMenus[$k]['values'][$kk]['selected'] = 'selected';
                        }
                        $clickMenus[$k]['values'][$kk]['text'] = $button->name;
                    }
                }
            } else if ($butGroup->type == 'click') {
                $clickMenus[$k]['type'] = 'option';
                $clickMenus[$k]['value'] = $button->key;
                if (in_array($button->key, $clickMenuSelecteds)) {
                    $clickMenus[$k]['selected'] = 'selected';
                }
                $clickMenus[$k]['text'] = $button->name;
            } else if ($butGroup->type == 'view') {
                $clickMenus[$k]['type'] = 'option';
                $clickMenus[$k]['value'] = $button->url;
                if (in_array($button->url, $clickMenuSelecteds)) {
                    $clickMenus[$k]['selected'] = 'selected';
                }
                $clickMenus[$k]['text'] = $button->name;
            }
        }
        $groupIds = array_keys($fields);
        foreach ($groupIds as $key => $groupId) {
            if ($groupId == 'tag_type') {
                unset($groupIds[$key]);
            }
        }
        $groupIds[] = 'click_menu';
        $groupIds = json_encode($groupIds);

        $data['fields'] = $fields;
        $data['groupIds'] = $groupIds;
        $data['action'] = '编辑';
        $data['tagRuleId'] = $tagRuleId;
        $data['tagName'] = $tagRule->name;
        $data['clickMenus'] = $clickMenus;
        return view('admin.wechat.tags_edit', $data);
    }

    public function store(Request $request)
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $tagRuleId = $request->input('tag_rule_id');
        $name = $request->input('tag_name');
        if (!$name) {
            return $this->ajaxError('请输入标签名');
        }
        $type = $request->input('tag_type');
        $subType = $request->input($type);
        $tid = Tag::where('cid', $cid)->where('name', $name)->value('id');
        if (!$tid) {
            $tid = Tag::insertGetId(['name' => $name, 'cid' => $cid]);
        }
        $tagRules = TagRule::where('cid', $cid)->where('id', $tagRuleId)->first();
        if (!$tagRuleId) {
            $tagRules = new TagRule;
        }
        $tagRules->cid = $cid;
        $tagRules->tid = $tid;
        $tagRules->type = $type;
        $tagRules->sub_type = $subType;
        $rul = [];
        if ($subType == 'sex') {
            $rul['sex'] = $request->input('sex');
        } else if ($subType == 'city') {
            $rul['city'] = $request->input('city');
        } else if ($subType == 'province') {
            $rul['province'] = $request->input('province');
        } else if ($subType == 'key_word') {
            $keyWord = $request->input('key_word');
            $rul['key_word'] =  $keyWord;
            $rul[$keyWord] = $request->input($keyWord);
        } else if ($subType == 'click_menu') {
            $clickMenu = $request->input('click_menu');
            $rul['click_menu'] = $clickMenu;
        }
        $tagRules->rul = $rul;
        $tagRules->save();
        if ($tagRules) {
            return $this->ajaxMessage('保存成功');
        }
        return $this->ajaxError('保存失败');
    }

    public function show(Request $request, $tid) {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $name = $request->input('name');
        $type = $request->input('type');
        $subType = $request->input('sub_type');
        $query = TagRule::join('tags', 'tag_rules.tid', '=', 'tags.id')->where('tags.cid', $cid);
        if ($tid) {
            $name = Tag::where('cid', $cid)->where('id', $tid)->value('name');
        }
        if ($name) {
            $query->where('tags.name', $name);
        }
        if ($type || $type === '0') {
            $query->where('tag_rules.type', $type);
        }
        if ($subType) {
            $query->where('tag_rules.sub_type', $subType);
        }
        $query->select('tag_rules.id', 'tags.name', 'tag_rules.type', 'tag_rules.sub_type', 'tag_rules.rul');
        $tagRules = $query->paginate(10);
        foreach ($tagRules as $key => $tagRule) {
            $tagRules[$key]->rul = json_encode($tagRules[$key]->rul);
        }
        $data = [
            'tagRules' => $tagRules,
            'name' => $name,
            'type' => $type,
            'subType' => $subType,
        ];
        return view('admin.wechat.tag_rules', $data);
    }
    public function destroy(Request $request, $id)
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $tid = TagRule::where('cid', $cid)->where('id', $id)->value('tid');

        $result = TagRule::where('cid', $cid)->where('id', $id)->delete();
        if (!$result) {
            return $this->ajaxError('删除失败');
        }
        $tagRule = TagRule::where('cid', $cid)->where('tid', $tid)->first();
        if (!$tagRule) {
            $result1 = Tag::where('cid', $cid)->where('id', $tid)->delete();
            if (!$result1) {
                return $this->ajaxError('删除标签失败');
            }
            $result2 = UserTag::where('cid', $cid)->where('tid', $tid)->delete();
            if (!$result2) {
                return $this->ajaxError('删除用户标签失败');
            }
        }
        return $this->ajaxMessage('删除成功');
    }

    public function getSearch(Request $request)
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $q = $request->input('q');
        $q = trim($q);
        $result = [
            'items' => [],
            'pagination' => ['more' => false]
        ];
        if (strlen($q) >= 2) {
            $result['items'] = Tag::where('cid', $cid)->where('name', 'like', "%$q%")->take(10)->get();
        }
        return  response()->json($result);
    }

    private function getFields()
    {
        $fields = [
            'tag_type' => [
                'input' => 'radio',
                'label' => '标签类型',
                'default' => 'user_data',
                'values' => [
                    'user_data' => '微信用户资料',
                    'user_action' => '用户行为',
                ],
            ],
            'user_data' => [
                'isHide' => 'yes',
                'input' => 'radio',
                'label' => '标签子类型',
                'default' => 'sex',
                'values' => [
                    'sex' => '性别',
                    'city' => '城市',
                    'province' => '省份',
                ],
            ],
            'user_action' => [
                'isHide' => 'yes',
                'input' => 'radio',
                'label' => '标签子类型',
                'default' => 'key_word',
                'values' => [
                    'key_word' => '关键词回复',
                    'click_menu' => '点击菜单',
                    'attention' => '关注',
                ],
            ],
            'key_word' => [
                'isHide' => 'yes',
                'input' => 'radio',
                'label' => '关键词匹配规则',
                'default' => 'complete_match',
                'values' => [
                    'complete_match' => '完全匹配',
                    'fuzzy_match' => '模糊匹配',
                ],
            ],
            'complete_match' => [
                'isHide' => 'yes',
                'input' => 'text',
                'label' => '完全匹配关键词',
                'default' => '',
                'values' => '',
            ],
            'fuzzy_match' => [
                'isHide' => 'yes',
                'input' => 'text',
                'label' => '模糊匹配关键词',
                'default' => '',
                'values' => '',
            ],
            'sex' => [
                'isHide' => 'yes',
                'input' => 'radio',
                'label' => '性别',
                'default' => 'boy',
                'values' => [
                    'boy' => '男',
                    'girl' => '女',
                ],
            ],
            'city' => [
                'isHide' => 'no',
                'input' => 'text',
                'label' => '城市',
                'readonly' => 'readonly',
                'default' => 'city1',
                'values' => '',
            ],
            'province' => [
                'isHide' => 'no',
                'input' => 'select',
                'label' => '省份',
                'default' => 'province1',
                'values' => [
                    '安徽' => '安徽',
                    '澳门' => '澳门',
                    '北京' => '北京',
                    '重庆' => '重庆',
                    '福建' => '福建',
                    '广东' => '广东',
                    '甘肃' => '甘肃',
                    '广西' => '广西',
                    '贵州' => '贵州',
                    '河北' => '河北',
                    '河南' => '河南',
                    '黑龙江' => '黑龙江',
                    '海南' => '海南',
                    '湖南' => '湖南',
                    '吉林' => '吉林',
                    '江苏' => '江苏',
                    '江西' => '江西',
                    '辽宁' => '辽宁',
                    '内蒙古' => '内蒙古',
                    '宁夏' => '宁夏',
                    '青海' => '青海',
                    '四川' => '四川',
                    '山东' => '山东',
                    '上海' => '上海',
                    '陕西' => '陕西',
                    '山西' => '山西',
                    '天津' => '天津',
                    '台湾' => '台湾',
                    '西藏' => '西藏',
                    '香港' => '香港',
                    '新疆' => '新疆',
                    '云南' => '云南',
                    '浙江' => '浙江',
                ],
            ],
        ];
        return $fields;
    }
}