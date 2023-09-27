<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Panel\Contact\StoreRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{

//StoreRequest $request
    public function store(Request $request) {

        // contact store
        $contactStoreData = [
            'name' => ["$request->name"],
            'custom_fields_values' => [
                "field_name" => "Work phone",
                "field_code" => "PHONE",
                "values" => [
                    "value" => "$request->phone"
                    ]
            ]
        ];
        $contactId = $this->contactStoreAmocrm($contactStoreData);
        dd($contactId);

        // note store
        $noteStoreData = [
            'entity_id' => [
                $contactId,
                'note_type' => 'common'
            ],
            'entity_type' => [
                'contacts',
                'note_type' => 'common'
            ],
            'note_type' => [
                'common',
                'note_type' => 'common'
            ],
            "params" => [
                "text" => $request->text,
                'note_type' => 'common'
            ]
        ];
        $noteId = $this->noteStoreAmocrm($contactId, $noteStoreData);


    }

    private function contactStoreAmocrm($storeData) {
        $path = "api/v4/contacts";
        $response = SendRequestToAmocrm('post', $path, $storeData);

        if($response['status'] == 401) {
            return ['message' => 'Token has expired', 'status' => 401];
        }

        if($response['status'] != 200) {
            return ['message' => $response['message'], 'status' => $response['status']];
        }

        return $response['result']->_embedded->contacts[0]->id;
    }

    private function noteStoreAmocrm($contactId, $storeData) {
        $path = "api/v4/contacts/$contactId/notes";
        $response = SendRequestToAmocrm('post', $path, $storeData);

        if($response['status'] == 401) {
            return ['message' => 'Token has expired', 'status' => 401];
        }

        if($response['status'] != 200) {
            return ['message' => $response['message'], 'status' => $response['status']];
        }

        return $response['result']->_embedded->notes[0]->id;
    }

    // link contact to lead
    private function linkContcToLead($contactId, $storeData) {
        $path = "api/v4/leads/contacts/link";
        $response = SendRequestToAmocrm('post', $path, $storeData);

        if($response['status'] == 401) {
            return ['message' => 'Token has expired', 'status' => 401];
        }

        if($response['status'] != 200) {
            return ['message' => $response['message'], 'status' => $response['status']];
        }

        return $response['result']->_embedded->notes->id;
    }


}
