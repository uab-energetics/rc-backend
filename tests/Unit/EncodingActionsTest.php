<?php

namespace Tests\Unit;

use App\BranchResponse;
use App\Encoding;
use App\EncodingExperimentBranch;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Services\Encodings\EncodingActions as actions;

class EncodingActions extends TestCase
{
    use DatabaseTransactions;

    public function testEncodingService() {
        $service = new \App\Services\Encodings\EncodingService();

        $encoding = factory(Encoding::class)->create()->id;

        $branch_a = factory(EncodingExperimentBranch::class)->make()->toArray();
        $branch_b = factory(EncodingExperimentBranch::class)->make()->toArray();
        $branch_c = factory(EncodingExperimentBranch::class)->make()->toArray();
        $res_a = factory(Response::class)->make()->toArray();
        $res_b = factory(Response::class)->make()->toArray();
        $res_c = factory(Response::class)->make()->toArray();
        $res_d = factory(Response::class)->make()->toArray();

        $newBranches = $service->dispatchAll([
            actions::recordBranch($encoding, $branch_a),
            actions::recordBranch($encoding, $branch_b),
            actions::recordBranch($encoding, $branch_c)
        ]);

        $newResponses = $service->dispatchAll([
            actions::recordResponse($encoding, $newBranches[0]['id'], $res_a),
            actions::recordResponse($encoding, $newBranches[1]['id'], $res_b),
            actions::recordResponse($encoding, $newBranches[2]['id'], $res_c),
            actions::recordResponse($encoding, $newBranches[2]['id'], $res_d),
        ]);

        echo json_encode(Encoding::find($encoding), JSON_PRETTY_PRINT);

        foreach ($newBranches as $branch){
            $service->dispatch( actions::deleteBranch($encoding, $branch['id']));
        }

        echo json_encode(Encoding::find($encoding), JSON_PRETTY_PRINT);
    }

}
