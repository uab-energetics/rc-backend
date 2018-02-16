<?php

namespace App\Services\Exports;


use App\Encoding;
use App\Form;
use App\Models\Response;
use App\Services\Forms\FormService;
use Illuminate\Support\Collection;

class FormExporter extends AbstractExportService {

    public function export() {
        $rows = [];
        foreach( $this->encodings as $encoding ){
            $rows = array_merge($rows, $this->mapEncodingToRows($encoding));
        }
        $pretty_headers = array_map(function($header){
            return $header['disp'];
        }, $this->headers);

        array_unshift($rows, $pretty_headers); // push display headers row to front
        return $rows;
    }

    private function mapEncodingToRows(Encoding $encoding){
        $headers = $this->headers;
        $rows = [];
        $this->transformBranches($encoding);
        foreach ($encoding->experimentBranches as $branch) {
            $rows[] = $this->mapModelToRow($headers, $branch, $encoding);
        }
        return $rows;
    }

    protected function lookupValue($rowModel, $header, $extra) {
        /** @var Encoding $encoding */
        $encoding = $extra;
        switch ($header['key'][0]){
            case "branch":
                return $rowModel['name'];
            case "question":
                return $this->branchGetResponse($rowModel, $header['key'][1]);
            case "publication_id":
                return $this->getEncodingPublication($encoding)['id'];
            case "publication_name":
                return $this->getEncodingPublication($encoding)['name'];
            case "publication_source_id":
                return $this->getEncodingPublication($encoding)['source_id'];
            case "user_id":
                return $this->getEncodingUser($encoding)['id'];
            case "user_name":
                return $this->getEncodingUser($encoding)['name'];
            default:
                return null;
        }
    }

    protected function transformBranches(Encoding $encoding) {

        foreach ($encoding->experimentBranches as &$branch) {
            $responseMap = [];
            foreach ($branch['responses'] as $response) {
                $responseMap[$response['question_id']] = $response;
            }
            $branch['responseMap'] = $responseMap;
        }
    }

    protected function branchGetResponse($branch, $question_id) {
        $response = array_get($branch, 'responseMap.'.$question_id, null);
        if ($response === null) return null;
        return $response->toAtomic();
    }

    protected function getEncodingUser(Encoding $encoding) {
        return $encoding->owner;
    }

    protected function getEncodingPublication(Encoding $encoding) {
        return $encoding->publication;
    }


    protected function generateFormHeaders() {
        $result = [
            self::header("Publication ID", 'publication_id'),
            self::header("Publication Source ID", 'publication_source_id'),
            self::header("Publication Name", 'publication_name'),
            self::header("User ID", 'user_id'),
            self::header("User Name", 'user_name'),
            self::header("Branch", 'branch'),
        ];
        foreach ($this->formService->getQuestions($this->form) as $question) {
            $result[] = self::header($question->name, 'question', $question->getKey());
        }
        return $result;
    }

    protected function getEncodings() {
        return $this->form->encodings()
            ->with(['publication', 'owner'])
            ->get();
    }

    /** @var Form */
    protected $form;
    /** @var Encoding[]|Collection */
    protected $encodings;
    /** @var FormService  */
    protected $formService;
    /** @var array */
    private $headers;

    public function __construct(FormService $formService, Form $form) {
        $this->form = $form;
        $this->formService = $formService;

        $this->form->load(['questions']);
        $this->headers = $this->generateFormHeaders();
        $this->encodings = $this->getEncodings();
    }
}