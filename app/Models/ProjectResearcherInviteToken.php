<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProjectResearcherInviteToken extends Model {

    protected $fillable = ['creator_id', 'token_key', 'project_id', 'access_level', 'expires'];

    protected $table = 'project_invite_tokens';


    /**
     * @return string - the redeemable token key
     */
    public static function generateInviteToken($creator_id, $project_id, $access_level = "owner"){
        $token_key = \Hash::make(str_random(8));
        $token_expires = Carbon::now()->addDays(2);

        return ProjectResearcherInviteToken::create([
            'creator_id' => $creator_id,
            'project_id' => $project_id,
            'token_key' => $token_key,
            'access_level' => $access_level,
            'expires' => $token_expires
        ])->toArray()['token_key'];
    }

    /**
     * @return ProjectResearcherInviteToken | null = The database record, or false if the token is invalid
     */
    public static function getToken($token_key){
        return ProjectResearcherInviteToken::where([
            ['expires', ">", Carbon::now()],
            ['token_key', '=', $token_key]
        ])->first();
    }
}
