<?php

namespace App\Http\Requests\Api\Platform\Organizations\Funds;

use App\Models\Fund;
use App\Models\Organization;
use App\Services\Forus\Record\Models\RecordType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateFundRequest
 * @property null|Organization $organization
 * @package App\Http\Requests\Api\Platform\Organizations\Funds
 */
class UpdateFundCriteriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $organization = $this->organization;
        $validators = $organization->external_validators()->pluck(
            'organizations.id'
        );

        return [
            'operator'          => 'required|in:=,<,>',
            'record_type_key'   => [
                'required',
                Rule::in(RecordType::query()->pluck('key')->toArray())
            ],
            'value'             => 'required|string|between:1,10',
            'show_attachment'   => 'nullable|boolean',
            'description'       => 'nullable|string|max:4000',
            'validators'      => [
                'nullable',
                'array'
            ],
            'validators.*'      => Rule::in($validators->toArray())
        ];
    }
}
