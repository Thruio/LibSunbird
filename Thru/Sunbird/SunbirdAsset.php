<?php
namespace Thru\Sunbird;

class SunbirdAsset extends SunbirdObject{

    public $asset_id;
    public $asset_uuid;
    public $size;
    public $filename;
    public $location;
    public $md5;
    public $created;

    public $data;

    public function get_url(){
        return SUNBIRD_HOST . "/storage/" . $this->location;
    }
}
