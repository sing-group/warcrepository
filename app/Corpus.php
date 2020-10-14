<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corpus extends Model
{
    public $table = "corpus";
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'uuid', 'user_id', 'name', 'size', 'description', 'all_content_languages','all_content_types',
        'all_content_dates', 'total_sites', 'spam_amount', 'downloads', 'status', 'path','spamDir','hamDir', 'status'
    ];

    public function user(){

        return $this->belongsTo(User::class , 'user_id');
    }


    public static function getAllContentLanguagesArray(Corpus $corpus) {

        $languages = explode(',',$corpus->all_content_languages);
        return $languages;

    }
    public static function getAllContentTypesArray(Corpus $corpus) {
        $POSSIBLE_CONTENT_TYPES = array('image/png','image/gif', 'image/jpeg', 'text/javasctript', 'application/javascript', 'text/css', 'text/plain', 'text/html');

        $contentTypes = explode(',',$corpus->all_content_types);
        $distinctContentTypes = array();
        foreach ($POSSIBLE_CONTENT_TYPES as $pct){
            $include = false;
            foreach($contentTypes as $contentType){
                if(stristr($contentType, $pct) != false){
                    $include = true;
                }
            }
            if($include){
                array_push($distinctContentTypes, $pct);
            }
        }
        return $distinctContentTypes;

    }

    public function getAllContentDatesArray($corpus){
        $dates = explode(',',$corpus->all_content_dates);
        return $dates;
    }

    public function getDate(){
        $phpdate = strtotime( $this->created_at );
        return date( 'Y-m-d', $phpdate );
    }

}
