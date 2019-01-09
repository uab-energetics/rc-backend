<?php


namespace App\Services\Repositories;



use App\Exceptions\RepoNotFoundException;
use App\Publication;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PubRepoService {

    /**
     * @param $project_id
     * @param $display_name
     * @return array
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function createRepo($project_id, $display_name) {
        $url = $this->host . "/projects/$project_id/pub-repos";
        $repo_data = simplePost($url, ['displayName' => $display_name]);
        return $repo_data;
    }

    /**
     * @param $project_id
     * @param $repo_id
     * @return array
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function deleteRepo($project_id, $repo_id) {
        $url = $this->host . "/projects/$project_id/pub-repos/$repo_id";
        return simpleDelete($url);
    }

    /**
     * @param $project_id
     * @param $repo_id
     * @return array|false
     * @throws RepoNotFoundException
     */
    public function getPublications($project_id, $repo_id) {
        $url = $this->host . "/projects/$project_id/pub-repos/$repo_id/publications";
        $res = simpleGet($url);
        if ($res->getStatusCode() !== 200) {
            throw new RepoNotFoundException("$repo_id: " . $res->getReasonPhrase(), $res->getStatusCode());
        }
        return json_decode($res->getBody(), true);
    }


    /**
     * @param $project_id
     * @param $repo_id
     * @param $publications
     * @return array
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function addPublications($project_id, $repo_id, $publications) {
        $url = $this->host . "/projects/$project_id/pub-repos/$repo_id/publications";
        return simplePost($url, ['publications' => $publications]);
    }


    /** @var string */
    public $host;

    public function __construct($publication_service_host) {
        $this->host = $publication_service_host;
    }
}
