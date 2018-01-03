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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjectFormService {

    //TODO: add priority to output
    public function retrievePublications(Project $project, Form $form, $query = "") {
        $projectForm = $this->getProjectForm($project, $form);
        return $projectForm->publications()->get();
    }

    public function retrieveEncoders(Project $project, Form $form, $query = "") {
        $projectForm = $this->getProjectForm($project, $form);
        return $projectForm->encoders()->get();
    }

    /**
     * @param Project $project
     * @param Form $form
     * @param Publication[]|Collection $publications
     * @param null|integer $priority
     */
    public function addPublications(Project $project, Form $form, $publications, $priority = null) {
        $projectForm = $this->getProjectForm($project, $form);
        foreach($publications as $publication) {
            $this->doAddPublication($projectForm, $publication, $priority);
        }
    }

    public function addPublication(Project $project, Form $form, Publication $publication, $priority = null) {
        $projectForm = $this->getProjectForm($project, $form);
        return $this->doAddPublication($projectForm, $publication, $priority);
    }

    protected function doAddPublication(ProjectForm $projectForm, Publication $publication, $priority = null) {
        $params = [
            'project_form_id' => $projectForm->getKey(),
            'publication_id' => $publication->getKey(),
        ];
        if ($priority !== null) $params['priority'] = $priority;
        return FormPublication::upsert($params);
    }

    public function addEncoders(Project $project, Form $form, $encoders) {
        $projectForm = $this->getProjectForm($project, $form);
        foreach($encoders as $encoder) {
            $this->doAddEncoder($projectForm, $encoder);
        }
    }

    public function addEncoder(Project $project, Form $form, User $encoder) {
        $projectForm = $this->getProjectForm($project, $form);
        return $this->doAddEncoder($projectForm, $encoder);
    }

    protected function doAddEncoder(ProjectForm $projectForm, User $encoder) {
        $edge = FormEncoder::upsert([
            'project_form_id' => $projectForm->getKey(),
            'encoder_id' => $encoder->getKey(),
        ]);
        $this->assignNextTasks($projectForm, $encoder, $projectForm->task_target_encoder);
        return $edge;
    }

    /**
     * @param Project $project
     * @param Form $form
     * @param User $encoder
     * @param null|integer $count
     * @return Collection|EncodingTask[]
     */
    public function requestTasks(Project $project, Form $form, User $encoder, $count = null) {
        $projectForm = $this->getProjectForm($project, $form);
        if ($count === null) $count = $projectForm->task_target_encoder;
        $count = min($count, $projectForm->task_target_encoder);
        return $this->assignNextTasks($projectForm, $encoder, $count);
    }

    protected function assignNextTasks(ProjectForm $projectForm, User $encoder, $count) {
        $target = $projectForm->task_target_publication;
        $publications = $this->getNextPublications($projectForm, $encoder, $count, $target);

        $tasks = collect();
        foreach($publications as $publication) {
            $task = $this->assignTask($projectForm, $publication, $encoder);
            $tasks->push($task);
        }
        return $tasks;
    }

    /**
     * @param ProjectForm $projectForm
     * @param User $encoder
     * @param integer $count
     * @param integer $target
     * @return Collection | Publication[]
     */
    public function getNextPublications(ProjectForm $projectForm, User $encoder, $count, $target) {
        $query = DB::select(self::SQL_PAPER_QUEUE, [
                $projectForm->getKey(),
                $projectForm->getKey(),
                $encoder->getKey(),
                $target,
                $count
        ]);
        return Publication::hydrate($query);
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
     * @param Project $project
     * @param Form $form
     * @return ProjectForm
     */
    protected function getProjectForm(Project $project, Form $form) {
        return ProjectForm::query()
            ->where('project_id', '=', $project->getKey())
            ->where('form_id', '=', $form->getKey())
            ->firstOrFail();
    }

    /** @var Project */
    protected $project;
    /** @var AssignmentService  */
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService) {
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