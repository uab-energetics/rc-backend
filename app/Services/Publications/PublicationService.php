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

    public function addExternalID(Publication $publication, $external_id) {
        return ExternalPublicationMap::upsert([
            'publication_id' => $publication->getKey(),
            'external_id' => strval($external_id),
        ]);
    }

    public function removeExternalID(Publication $publication, $external_id) {
        return ExternalPublicationMap::query()
            ->where('publication_id', '=', $publication->getKey())
            ->where('external_id', '=', $external_id)
            ->delete();
    }

}