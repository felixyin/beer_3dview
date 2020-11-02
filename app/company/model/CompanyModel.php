<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\company\model;

use think\Db;
use think\Model;

class CompanyModel extends Model
{
    public function getprovince()
    {
        return $this->belongsTo('app\city\model\CityModel','province','id');
    }
    public function getcity()
    {
        return $this->belongsTo('app\city\model\CityModel','city','id');
    }
    public function getcounty()
    {
        return $this->belongsTo('app\city\model\CityModel','county','id');
    }
}
