<?php


namespace App\Services\Forms;


use App\Category;
use App\Encoding;
use App\Form;
use App\FormQuestion;
use App\Models\Question;
use App\Services\Exports\FormExportService;

class FormService {

    public function makeForm($params) {
        $category = Category::create([
            'parent_id' => null,
            'name' => 'root',
        ]);

        $params['root_category_id'] = $category->getKey();
        $params['published'] = false;
        $form = Form::create($params);

        return $form;
    }

    public function search($query) {
        return Form::search($query)->get();
    }

    public function updateForm(Form $form, $params) {
        unset($params['root_category_id']);
        unset($params['type']);
        $form->update($params);
    }

    public function deleteForm(Form $form) {
        $rootCategory = $form->rootCategory()->first();
        $form->delete();
        $rootCategory->delete();
        return true;
    }

    public function getQuestions(Form $form) {
        return $form->questions()->get();
    }

    public function addQuestion(Form $form, Question $question, Category $category = null) {
        if ($category === null) {
            $category = $form->rootCategory()->first();
        }

        $formQuestion = FormQuestion::create([
            'form_id' => $form->getKey(),
            'question_id' => $question->getKey(),
            'category_id' => $category->getKey(),
        ]);

        return $formQuestion;
    }

    public function moveQuestion(Form $form, Question $question, Category $category) {
        $edge = FormQuestion::query()
            ->where('form_id', '=', $form->getKey())
            ->where('question_id', '=', $question->getKey())
            ->first();

        if ($edge === null) {
            return false;
        }

        $edge->category_id = $category->getKey();
        $edge->save();
        return true;
    }

    public function removeQuestion(Form $form, Question $question) {
        $edge = FormQuestion::query()
            ->where('form_id', '=', $form->getKey())
            ->where('question_id', '=', $question->getKey())
            ->first();

        if ($edge === null) {
            return false;
        }
        $edge->delete();
        return true;
    }

    public function exportForm(Form $form) {
        $headers = $this->generateFormHeaders($form);
        $formArr = $form->toArray();
        $formArr['encodings'] = $this->getExportArrayEncodings($form);

//        return $formArr;
        $export = $this->formExportService->exportFormData($headers, $formArr);
        return $export;
    }

    protected function generateFormHeaders(Form $form) {
        $result = [
            FormExportService::header("Publication ID", 'publication_id'),
            FormExportService::header("Publication Name", 'publication_name'),
            FormExportService::header("User ID", 'user_id'),
            FormExportService::header("User Name", 'user_name'),
            FormExportService::header("Branch", 'branch'),
        ];
        foreach ($this->getQuestions($form) as $question) {
            $result[] = FormExportService::header($question->name, 'question', $question->getKey());
        }
        return $result;
    }

    protected function getExportArrayEncodings(Form $form) {
        $result = [];
        foreach($form->encodings()->get() as $encoding) {
            $result[] = $this->normalizeEncodingArray($encoding, $form->type);
        }
        return $result;
    }

    protected function normalizeEncodingArray(Encoding $encoding, $formType) {
        $result = $encoding->toArray();
        switch($formType) {
            case FORM_EXPERIMENT:
                $result['branches'] = $result['experiment_branches'];
                unset($result['experiment_branches']);
                break;
            case FORM_SIMPLE:
                $result['branches'] = [
                    [
                    'name' => "Simple",
                    'responses' => $result['simple_responses'],
                    ]
                ];
                unset($result['simple_responses']);
                break;
            default:
                throw new \Exception("Unsupported form type ($formType)");
                break;
        }
        return $result;
    }

    /**
     * @param Form $form
     * @param $category_id
     * @return Category | false
     */
    public function findCategory(Form $form, $category_id) {
        $category = Category::find( $category_id );
        if ($category === null) {
            $category = $form->rootCategory()->first();
        }
        if ($form->getKey() !== $category->getForm()->getKey()) {
            return false;
        }
        return $category;
    }

    /** @var FormExportService  */
    protected $formExportService;

    public function __construct(FormExportService $formExportService) {
        $this->formExportService = $formExportService;
    }

}