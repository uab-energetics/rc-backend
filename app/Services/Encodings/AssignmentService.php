<?php


namespace App\Services\Encodings;


use App\Project;
use App\ProjectEncoding;

class AssignmentService {

    public function assignTo($form_id, $publication_id, $user_id, $project_id = null) {
        $encoding = $this->encodingService->makeEncoding($form_id, $publication_id, $user_id);
        if ($project_id !== null && Project::find($project_id) !== null) {
            $project_edge = ProjectEncoding::create([
                'project_id' => $project_id,
                'encoding_id' => $encoding->getKey(),
            ]);
        }
        return $encoding;
    }


    /** @var EncodingService  */
    protected $encodingService;

    public function __construct(EncodingService $encodingService) {
        $this->encodingService = $encodingService;
    }

}