<?php

namespace Tests\Feature\api\users;

use App\Category;
use App\EncodingExperimentBranch;
use App\Form;
use App\Models\Question;
use App\Models\Response;
use App\Project;
use App\Publication;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;


class CsvUploadTest extends JWTTestCase {


    use DatabaseTransactions;

    public function testEncodings() {
        $this->asAnonymousUser();

        $pid = factory(Project::class)->create()->id;


        /* Should fail elegantly with some debug information */

        $BAD_CSV_DATA = json_decode('[["User ID","User Name","Branch","Question One","Name","Gender","Pets","Age","Humanity"],["1","Chris Rocco","Contstants","[\"Canada\",\"New Orleans\",\"New York\"]","2020202020","Female","[\"Lizard\",\"Turtle\",\"Yeast\"]","NO_RESPONSE","Yes"]]');
        $this->json('POST', "projects/$pid/publications/csv", [
            'data' => $BAD_CSV_DATA
        ])->assertStatus(400);


        /* Should parse and upload good data into the publications table */

        $GOOD_CSV_DATA = [
            [ 'test paper a', 'some url' ],
            [ 'test paper b', 'some url' ],
        ];
        $this->json('POST', "projects/$pid/publications/csv", [
            'data' => $GOOD_CSV_DATA
        ])->assertStatus(200);




        $project = Project::find($pid);
        $publications = $project->publications()->where([
            ['name', 'LIKE', 'test paper %'],
            ['embedding_url', '=', 'some url']
        ])->get()->count();

        $this->assertEquals($publications, 2);
    }
}






