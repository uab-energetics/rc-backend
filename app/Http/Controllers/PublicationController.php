<?php

namespace App\Http\Controllers;

use App\Project;
use App\Publication;
use App\Services\Projects\ProjectService;
use App\Services\Publications\CsvUploadService;
use App\Services\Publications\PublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicationController extends Controller {

    public function create(Request $request) {
        $request->validate(self::CREATE_VALIDATION_RULES);

        DB::beginTransaction();
            $publication = $this->publicationService->makePublication($request->all());
        DB::commit();

        return $publication;
    }

    public function createInProject(Project $project, Request $request, ProjectService $projectService) {
        $request->validate([
            'name' => 'string|required',
            'embedding_url' => 'url|required',
        ]);

        DB::beginTransaction();
            $publication = $this->publicationService->makePublication($request->all());
            $projectService->addPublication($project, $publication);
        DB::commit();

        return $publication;
    }

    public function retrieve(Publication $publication) {
        return $publication;
    }

    public function update(Publication $publication, Request $request) {
        $request->validate([
            'name' => 'string',
            'embedding_url' => 'url',
        ]);

        DB::beginTransaction();
            $this->publicationService->updatePublication($publication, $request->all());
        DB::commit();

        return $publication->refresh();
    }

    public function delete(Publication $publication) {
        DB::beginTransaction();
            $this->publicationService->deletePublication($publication);
        DB::commit();
        return okMessage("Successfully deleted publication");
    }

    public function uploadFromCSV(Project $project, Request $request, ProjectService $projectService){
        $request->validate([ 'data' => 'required' ]);

        $service = new CsvUploadService();
        $records = $service->parse($request->data);

        if(!$records){
            return response()->json([
                'msg' => "Could not parse CSV",
                'details' => $service->fail_message
            ], 400);
        }

        DB::beginTransaction();
            foreach ($records as $pubParams) {
                $validator = Validator::make($pubParams, self::CREATE_VALIDATION_RULES);
                if ($validator->fails()) return invalidParamMessage($validator);
                $publication = $this->publicationService->makePublication($pubParams);
                $projectService->addPublication($project, $publication);
            }
        DB::commit();

        return $records;
    }


    /** @var PublicationService  */
    protected $publicationService;

    public function __construct(PublicationService $publicationService) {
        $this->publicationService = $publicationService;
    }

    const CREATE_VALIDATION_RULES = [
        'name' => 'string|required',
        'embedding_url' => 'url|required',
    ];
}
