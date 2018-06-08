<?php


namespace App\Services\Repositories;



use App\Publication;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PublicationRepoService {

    /**
     * @param $project_id
     * @param $display_name
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \HttpException
     */
    public function createRepo($project_id, $display_name) {
        $url = $this->host . "/projects/$project_id/pub-repos";
        $repo_data = simplePost($url, ['displayName' => $display_name]);
        return $repo_data;
    }

    public function deleteRepo($project_id, $repo_id) {
        $url = $this->host . "/projects/$project_id/pub-repos/$repo_id";
        return simpleDelete($url);
    }


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