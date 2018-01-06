<?php

namespace App\Http\Controllers;

use App\Project;
use App\Publication;
use App\Services\Projects\ProjectService;
use App\Services\Publications\CsvUploadService;
use App\Services\Publications\PublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller {

    public function create(Request $request) {
        $request->validate([
            'name' => 'string|required',
            'embedding_url' => 'url|required',
        ]);

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

    public function uploadFromCSV(Project $project, Request $request){
        $service = new CsvUploadService();

        $rows = $request->data;

        $fail = 0;
        $records = array_map(function($row){
            // TODO - validate
            return [
                'name' => $row[0],
                'embedding_url' => $row[1]
            ];
        }, $rows);

        if($fail){
            return response()->json([
                'msg' => 'Failed to parse csv. Please check to format and try again.',
                'details' => $service->fail_message,
                'original_data' => $request->data
            ], 400);
        }

        $project->publications()->createMany($records);

        return $records;
    }


    /** @var PublicationService  */
    protected $publicationService;

    public function __construct(PublicationService $publicationService) {
        $this->publicationService = $publicationService;
    }
}
