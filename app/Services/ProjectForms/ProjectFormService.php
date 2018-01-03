<?php


namespace App\Services\ProjectForms;


use App\EncodingTask;
use App\Form;
use App\FormEncoder;
use App\FormPublication;
use App\Project;
use App\ProjectForm;
use App\Publication;
use App\Services\Encodings\AssignmentService;
use App\User;
use Illuminate\Support\Facades\DB;

class ProjectFormService {

    public function retrievePublications(Form $form, $query = "") {
        $projectForm = $this->getProjectForm($form);
        return $projectForm->publications()->get();
    }

    public function retrieveEncoders(Form $form, $query = "") {
        $projectForm = $this->getProjectForm($form);
        return $projectForm->encoders()->get();
    }

    public function addPublication(Form $form, Publication $publication, $priority = null) {
        $projectForm = $this->getProjectForm($form);
        $params = [
            'project_form_id' => $projectForm->getKey(),
            'publication_id' => $publication->getKey(),
        ];
        if ($priority !== null) $params['priority'] = $priority;
        return FormPublication::upsert($params);
    }

    public function addEncoder(Form $form, User $encoder) {
        $projectForm = $this->getProjectForm($form);
        return FormEncoder::upsert([
            'project_form_id' => $projectForm->getKey(),
            'encoder_id' => $encoder->getKey(),
        ]);
    }

    public function getNextAssignments(Form $form, User $encoder, $count = null) {
        $projectForm = $this->getProjectForm($form);
        if ($count === null) $count = $projectForm->task_target_encoder;
        $query = DB::select(self::SQL_PAPER_QUEUE, [
                $projectForm->getKey(),
                $projectForm->getKey(),
                $encoder->getKey(),
                $projectForm->task_target_publication,
                $count
        ]);

        $tasks = collect();
        foreach(Publication::hydrate($query) as $publication) {
            $task = $this->assignTask($projectForm, $publication, $encoder);
            $tasks->push($task);
        }
        return $tasks->pluck('id');
    }

    public function assignTask(ProjectForm $projectForm, Publication $publication, User $encoder) {
        $encoding = $this->assignmentService->assignTo($projectForm->form_id, $publication->getKey(), $encoder->getKey());
        $task = EncodingTask::upsert([
            'project_form_id' => $projectForm->getKey(),
            'encoding_id' => $encoding->getKey(),
            'encoder_id' => $encoder->getKey(),
        ]);
        return $task;
    }

    /**
     * @param Form $form
     * @return ProjectForm
     */
    protected function getProjectForm(Form $form) {
        return ProjectForm::query()
            ->where('project_id', '=', $this->project->getKey())
            ->where('form_id', '=', $form->getKey())
            ->firstOrFail();
    }

    /** @var Project */
    protected $project;
    /** @var AssignmentService  */
    protected $assignmentService;

    public function __construct($project, AssignmentService $assignmentService) {
        if (!($project instanceof  Project)) {
            $project = Project::findOrFail($project);
        }
        $this->project = $project;
        $this->assignmentService = $assignmentService;
    }

const SQL_PAPER_QUEUE = "
SELECT
  publications.*,
  task_counts.task_count,
  form_pub.priority
FROM publications
  JOIN form_publication form_pub ON publications.id = form_pub.publication_id
  JOIN project_form proj_form ON form_pub.project_form_id = proj_form.id AND proj_form.id = ?
  JOIN (SELECT
      publications.id,
      SUM(CASE WHEN tasks.id IS NULL
       THEN 0 ELSE 1 END
  ) AS task_count
  FROM publications
   LEFT JOIN encodings ON encodings.publication_id = publications.id
   LEFT JOIN encoding_tasks tasks ON encodings.id = tasks.encoding_id AND tasks.project_form_id = ?
 GROUP BY publications.id
 HAVING NOT EXISTS(SELECT * FROM encoding_tasks et JOIN encodings e ON et.encoding_id = e.id AND et.encoder_id = ? WHERE e.publication_id = publications.id)
) task_counts ON publications.id = task_counts.id

WHERE
  (priority = 0 AND task_count < ?)
  OR priority > 0
  
ORDER BY task_count DESC, priority DESC
LIMIT ?
";
}