<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Panel\Contact\StoreRequest;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Log;
use App\Models\Note;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    private $contactId;
    private $noteId;

//StoreRequest $request
    public function store(Request $request) {
        // store log
        $logCreate = Log::create([
            'result' => 'failed',
            'action' => 'link contact to lead',
        ]);

        // store ro Amocrm
        $this->storeAmocrm($request);

        // store contact
        Contact::create([
            'id' => $this->contactId,
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        // store note
        Note::create([
            'id' => $this->noteId,
            'note_type' => 'common',
            'entity_id' => $this->contactId,
            'entity_type' => 'contacts',
            'text' => $request->text
        ]);

        // attach contact to lead
        $deal = Deal::find($request->id);
        $deal->contacts()->attach($this->contactId);

        // store log
        $logUpdate = Log::find($logCreate->id);
        $logUpdate->result = 'success';
        $logUpdate->save();

        return 'OK';
    }

    // store ro Amocrm
    private function storeAmocrm($request) {
        // contact store
        $contactStoreData = [[
            'name' => $request->name,
            'custom_fields_values' => [
                [
                    "field_name" => "Work phone",
                    "field_code" => "PHONE",
                    "values" => [
                        [
                            "value" => $request->phone
                        ]
                    ]
                ]
            ]
        ]];
        $this->contactId = $this->contactStoreAmocrm($contactStoreData);

        // note store
        $noteStoreData = [
            'entity_id' => [
                $this->contactId,
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
        $this->noteId = $this->noteStoreAmocrm($noteStoreData);

        // link Contact To Lead
        $linkStoreData = [[
            "to_entity_id" => $this->contactId,
            "to_entity_type" => "contacts"
        ]];
        $this->linkContactToLead($request->id, $linkStoreData);
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

    private function noteStoreAmocrm($storeData) {
        $path = "api/v4/contacts/$this->contactId/notes";
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
    private function linkContactToLead($leadId, $storeData) {
        $path = "api/v4/leads/$leadId/link";
        $response = SendRequestToAmocrm('post', $path, $storeData);

        if($response['status'] == 401) {
            return ['message' => 'Token has expired', 'status' => 401];
        }

        if($response['status'] != 200) {
            return ['message' => $response['message'], 'status' => $response['status']];
        }
    }


}
