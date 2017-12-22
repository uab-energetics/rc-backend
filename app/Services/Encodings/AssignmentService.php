<?php


namespace App\Services\Encodings;


use App\Project;
use App\ProjectEncoding;

class AssignmentService {

    public function assignTo($form_id, $publication_id, $user_id) {
        $encoding = $this->encodingService->makeEncoding($form_id, $publication_id, $user_id);
        return $encoding;
    }


    /** @var EncodingService  */
    protected $encodingService;

    public function __construct(EncodingService $encodingService) {
        $this->encodingService = $encodingService;
    }

}