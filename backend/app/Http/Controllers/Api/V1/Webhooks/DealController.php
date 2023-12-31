<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Note;

class DealController extends Controller
{
    private $leadId = '';

    public function index($leads) {
        if ($leads['add'][0]) {
            $this->addLead($leads['add'][0]);
        }
    }

    private function addLead($lead) {
        // store deal
        $this->leadId = $lead['id'];
        $deal = Deal::create([
            'id' => $lead['id'],
            'name' => $lead['name'],
            'created_at' => $lead['created_at'],
            'updated_at' => $lead['updated_at']
        ]);

        // get contact Embedded
        $contactEmbedded = $this->getEmbedded();
        // check contact
        if (!Contact::find($contactEmbedded->id)) {

            // get Contact
            $contact = $this->getContact($contactEmbedded->id);

            // store contact
            Contact::create([
                'id' => $contact->id,
                'name' => $contact->name,
                'phone' => $this->customFieldsValues($contact->custom_fields_values, 'PHONE'),
                'created_at' => $contact->created_at,
                'updated_at' => $contact->updated_at
            ]);

            // get Notes
            $note = $this->getNotes($contact->id);

            // store note
            if($note['status']) {
                Note::create([
                    'id' => $note['result']->id,
                    'note_type' => $note['result']->note_type,
                    'entity_id' => $note['result']->entity_id,
                    'entity_type' => 'contacts',
                    'text' => $note['result']->params->text,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at
                ]);
            }
        }

        // attach deal & contact
        $deal->contacts()->attach($contactEmbedded->id);
        return ['status' => 200];
    }

    private function getEmbedded() {
        $path = "api/v4/leads/$this->leadId";
        $response = SendRequestToAmocrm('get', $path, ['with' => 'contacts']);

        if($response['status'] == 401) {
            abort(401, 'Token has expired.');
        }
        $contacts = $response['result']->_embedded->contacts;
        if(!isset($contacts[0])) {
            abort($response['status'], 'Contacts do not exist.');
        }

        return $contacts[0];
    }

    private function getContact($contactId) {
        $path = "api/v4/contacts/$contactId";
        $response = SendRequestToAmocrm('get', $path, ['with' => 'catalog_elements,customers']);

        if($response['status'] == 401) {
            abort(401, 'Token has expired.');
        }

        if($response['status'] != 200) {
            abort($response['status'], 'An error occurred.');
        }

        return $response['result'];
    }

    private function getNotes($contactId) {
        $path = "api/v4/contacts/$contactId/notes";
        $response = SendRequestToAmocrm('get', $path);
        if($response['status'] == 401) {
            abort(401, 'Token has expired.');
        }

        if (isset($response['result']->_embedded)) {
            return ['status' => true, 'result' => $response['result']->_embedded->notes[0]];
        }
        return ['status' => false];
    }

    private function customFieldsValues($custom_fields_values, $fieldName) {
        foreach ($custom_fields_values as $item) {
            if ($item->field_code == $fieldName) {
                return $item->values[0]->value;
            }
        }
        return '';
    }
}
