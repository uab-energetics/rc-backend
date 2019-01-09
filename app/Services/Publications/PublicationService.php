<?php


namespace App\Services\Publications;


use App\ExternalPublicationMap;
use App\Publication;
use Ramsey\Uuid\Uuid;

class PublicationService {

    public function search($search) {
        return search(Publication::query(), $search, Publication::searchable);
    }

    public function makePublication($params) {
        if (isset($params['uuid']) === false) {
            $params['uuid'] = Uuid::uuid1()->toString();
        }
        return Publication::create($params);
    }

    public function getPublication($publication_id) {
        return Publication::findOrFail($publication_id);
    }

    public function updatePublication(Publication $publication, $params) {
        return $publication->update($params);
    }

    public function deletePublication(Publication $publication) {
        $publication->delete();
    }

    public function retrieveByUuid($uuid) {
        return Publication::where('uuid', '=', $uuid)->first();
    }

    public function retrieveExternalIds($external_ids) {
        return ExternalPublicationMap::query()
            ->whereIn('external_id', $external_ids);
    }

    public function addExternalID(Publication $publication, $external_id) {
        return ExternalPublicationMap::upsert([
            'publication_id' => $publication->getKey(),
            'external_id' => strval($external_id),
        ]);
    }

    public function removeExternalID(Publication $publication, $external_id) {
        $publication->externalIds()
            ->where('external_id', '=', $external_id)
            ->delete();
    }

    public function addExternalPublications($external_pubs) {
        $publications = [];
        foreach ($external_pubs as $external_pub) {
            [$params, $external_id] = $this->transformExternalPub($external_pub);
            $existing = $this->retrieveByUuid($params['uuid']);
            if ($existing === null) {
                $existing = $this->makePublication($params);
            }
            $publications[] = $existing;
            $this->addExternalID($existing, $external_id);
        }
        return $publications;
    }

    public function transformExternalPub($external) {
        $id = $external['id'];
        $internal = [
            'uuid' => $external['uuid'],
            'source_id' => $external['sourceID'],
            'embedding_url' => $external['embeddingURL'],
            'name' => $external['title'],
        ];
        return [$internal, $id];
    }

}
