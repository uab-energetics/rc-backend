<?php

namespace Tests\Unit;


use App\Services\Exports\FormExportService;
use Tests\TestCase;

class FormExportsTest extends TestCase {

    private $headers;

    function setUp() {
        parent::setUp();
        $this->headers = [
            FormExportService::header('User ID', 'user'),
            FormExportService::header('Question One', 'question', 1),
            FormExportService::header('Question Two', 'question', 2)
        ];
    }

    function testExportForm(){
        $output = $this->formExportService->exportFormData($this->headers, form);

//        $this->assertEquals('Yes!', $output[1][2]);
//        $this->assertEquals('No', $output[1][1]);
        $this->assertEquals(FormExportService::NO_RESPONSE, $output[2][2]);
        //echo json_encode($output, JSON_PRETTY_PRINT);
    }

}

const form = [
    'encodings' => [
        [
            'user_id' => 1234,
            'branches'=> [
                [
                    'user_id' => 1234, // this lookup will be done via DB query or pre-processing - values are duplicated here to simulate pre-processing.
                    'id' => 1,
                    'responses' => [
                        [
                            'qid' => 1,
                            'data' => 'No'
                        ],
                        [
                            'qid' => 2,
                            'data' => 'Yes!'
                        ]
                    ]
                ]
            ]
        ],
        [
            'user_id' => 435,
            'branches' => [
                [
                    'user_id' => 435,
                    'id' => 2,
                    'responses' => [
                        [
                            'qid' => 1,
                            'data' => 'some response'
                        ]
                    ]
                ]
            ]
        ]
    ]
];