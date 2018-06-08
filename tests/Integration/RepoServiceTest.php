<?php


namespace Tests\Integration;


use App\Services\Repositories\PublicationRepoService;
use Tests\TestCase;

class RepoServiceTest extends TestCase {

    /** @var PublicationRepoService */
    private $service;
    /** @var array */
    private $repo;

    const PROJECT_ID = 'test_project';

    public function setUp() {
        parent::setUp();
        $this->service = new PublicationRepoService('http://rc-publications');
        $repo = $this->service->createRepo(self::PROJECT_ID, "Main Repository");


        foreach (['displayName', 'id', 'projectID'] as $key) {
            $this->assertArrayHasKey($key, $repo);
        }

        $this->repo = $repo;
    }

    public function testAddThenRemovePublications() {
        $publications = [
            [
                'title' => "IP Chicken",
                'embeddingURL' => "http://ipchicken.com",
                'sourceID' => "TESTING123",
                'uuid' => "3d204aae-6b31-11e8-bc8f-0242ac120007",
            ]
        ];
        $res = $this->service->addPublications($this->repo['projectID'], $this->repo['id'], $publications);
        $this->assertArrayHasKey('publications', $res);
        $this->assertArraySubset($publications[0], $res['publications'][0]);

        $pubIDs = array_map(function ($pub) {
            return $pub['id'];
        }, $res['publications']);
    }

    public function tearDown() {
        parent::tearDown();
        $this->service->deleteRepo($this->repo['projectID'], $this->repo['id']);
    }

}