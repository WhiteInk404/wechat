<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use EstGroupe\Taggable\Taggable;

class MedicalRecorde extends Model
{
    use Taggable;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'medical_recordes';

    const FIRST_VIS = 1;
    const MORE_VIS = 2;

    public static $visType = [
        1 => '首诊',
        2 => '复诊'
    ];

    public function medicalRecordeImages()
    {
        return $this->hasMany('App\Models\MedicalRecordImage', 'medical_record_id');
    }
}
