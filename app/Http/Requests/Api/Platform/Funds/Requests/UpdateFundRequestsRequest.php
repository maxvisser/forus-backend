<?php

namespace App\Http\Requests\Api\Platform\Funds\Requests;

use App\Models\FundRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateFundRequestsRequest
 * @property FundRequest $fund_request
 * @package App\Http\Requests\Api\Platform\Funds\FundRequests
 */
class UpdateFundRequestsRequest extends FormRequest
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
        $organization = $this->fund_request->fund->organization;

        $externalEmployees = [];
        $employees = $organization->employeesOfRole('validation')->pluck('id')->toArray();

        foreach ($organization->external_validators as $external_validator) {
            $externalEmployees = array_merge(
                $externalEmployees,
                $external_validator->employeesOfRole('validation')->pluck('id')->toArray()
            );
        }

        return [
            'state' => [
                'nullable',
                Rule::in([
                    FundRequest::STATE_APPROVED,
                    FundRequest::STATE_DECLINED,
                ])
            ],
            'employee_id' => [
                'nullable',
                Rule::in(array_values(array_unique(array_merge($employees, $externalEmployees))))
            ]
        ];
    }
}
