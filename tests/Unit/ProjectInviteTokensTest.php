<?php

namespace Tests\Unit;


use App\ProjectInviteToken;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectInviteTokensTest extends TestCase {

    use DatabaseTransactions;

    function testInviteTokens(){

        $user_id = factory(User::class)->create()->getKey();

        $generated_token = ProjectInviteToken::generateInviteToken($user_id);

        $token_record = ProjectInviteToken::getToken($generated_token);

        $this->assertEquals($generated_token, $token_record['token_key']);
    }

}