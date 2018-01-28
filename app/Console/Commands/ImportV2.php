<?php

namespace App\Console\Commands;

use App\Category;
use App\Form;
use App\Services\Forms\CategoryService;
use App\Services\Forms\FormService;
use App\Services\Questions\QuestionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportV2 extends Command {
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'v2:import {file}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Imports V2 projects as V3 forms';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle() {
        $fileName = $this->argument('file');
        $json = file_get_contents($fileName);
        $projects = json_decode($json, true);
        $this->import($projects);
    }

    protected function import(array $v2Projects) {
        $failures = [];
        DB::beginTransaction();
        foreach ($v2Projects as $v2Project) {
            $validator = $this->v2ProjectValidator($v2Project);
            if ($validator->fails()) {
                $failures[] = [
                    'project' => $v2Project,
                    'reasons' => $validator->errors()
                ];
                continue;
            }
            /** @var Form $form */
            $form = $this->makeForm($v2Project);
            echo PHP_EOL . $form->name . PHP_EOL;

            $categoryMap = $this->makeCategories($form->rootCategory()->first(), $v2Project['structure']);

            $questions = $this->makeQuestions($form, $categoryMap, $v2Project['structure']);

//            echo json_encode (Form::find($form->getKey())) . PHP_EOL . PHP_EOL;
        }
        DB::commit();
        echo "Success!" . PHP_EOL;
    }

    protected function v2ProjectValidator($v2Project) {
        return Validator::make($v2Project, [
            'name' => 'required|string',
            'description' => 'required|string',
            'structure' => 'required',
        ]);
    }

    protected function makeForm($v2Project) {
        return $this->formService->makeForm([
            'name' => $v2Project['name'],
            'description' => $v2Project['description'],
            'type' => 'experiment',
        ]);
    }

    protected function makeCategories(Category $root, $v2Structure) {
        $domainMap = $this->getDomainMap($v2Structure['domains']);
        $result = [];

        foreach ($domainMap as $id => $domain) {
            $categoryParams = [
                'name' => $domain['name'],
                'description' => $domain['description'],
            ];

            if ($domain['parent'] === null) {
                $categoryParams['parent_id'] = $root->getKey();
            } else {
                $categoryParams['parent_id'] = $result[$domain['parent']]->getKey();
            }

            $category = $this->categoryService->makeCategory($categoryParams);
            $result[$domain['_id']] = $category;
        }
        return $result;
    }

    protected function makeQuestions(Form $form, $categoryMap, $v2Structure) {
        $variables = array_filter($v2Structure['questions'], function ($variable) {return $variable !== null; });

        foreach ($variables as $variable) {
            $questionParams = $this->getQuestionParams($variable);
            $question = $this->questionService->makeQuestion($questionParams);

            $category = getOrDefault($categoryMap[$variable['parent']], null);
            $this->formService->addQuestion($form, $question, $category);
        }
    }

    // Guarantees that creating categories in the order of the result  won't break the parent_id foreign key constraint
    private function getDomainMap(array $domains) {
        $domains = array_filter($domains, function ($domain) { return $domain !== null; });

        $result = [];
        foreach ($domains as $i => $domain) {
            if (preg_match("/^domains.*/", $domain['parent'])) {
                //we're not interested in children domains
                continue;
            }
            $domain['parent'] = null;
            $result[$domain['_id']] = $domain;
        }

        //map all of the children domains
        $loopCounter = 0;
        while (count($result) < count($domains)) {
            foreach ($domains as $domain) {
                if (!isset( $result[$domain['parent']] )) continue;
                $result[$domain['_id']] = $domain;
            }
            $loopCounter++;
            if ($loopCounter > 1000){
                foreach ($domains as $i => $domain) {
                    if (isset( $result[$domain['_id']] )) continue;
                    $domain['parent'] = null;
                    $result[$domain['_id']] = $domain;
                }
                echo "Had to boost orphaned categories" . PHP_EOL;
                break;
            }
        }
        return $result;
    }

    private function getQuestionParams($v2Variable) {
        $result = [
            'name' => $v2Variable['name'],
            'description' => $v2Variable['tooltip'],
            'prompt' => $v2Variable['question'],
            'type' => $this->typeMap[$v2Variable['type']],
            'true_option' => getOrDefault($v2Variable['trueOption'], null),
            'false_option' => getOrDefault($v2Variable['falseOption'], null),
        ];
        $result['default_format'] = $result['type'];
        $result['accepts'] = [['type' => $result['type']]];
        $result['options'] = $this->transformOptions( getOrDefault($v2Variable['options'], []) );

        return $result;
    }

    private function transformOptions($options) {
        $result = [];
        foreach ($options as $option) {
            $result[] = ['txt' => $option];
        }
        return $result;
    }

    protected $typeMap = [
        'text' => RESPONSE_TEXT,
        'boolean' => RESPONSE_BOOL,
        'number' => RESPONSE_NUMBER,
        'range' => RESPONSE_RANGE,
        'select' => RESPONSE_SELECT,
        'multiselect' => RESPONSE_MULTI_SELECT,
    ];


    /** @var FormService  */
    protected $formService;
    /** @var CategoryService  */
    protected $categoryService;

    /** @var QuestionService  */
    protected $questionService;

    public function __construct(FormService $formService, CategoryService $categoryService, QuestionService $questionService) {
        parent::__construct();
        $this->formService = $formService;
        $this->categoryService = $categoryService;
        $this->questionService = $questionService;
    }
}
