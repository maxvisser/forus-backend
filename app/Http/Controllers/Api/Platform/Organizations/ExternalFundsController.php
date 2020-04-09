<?php

namespace App\Http\Controllers\Api\Platform\Organizations;

use App\Http\Resources\FundResource;
use App\Models\Fund;
use App\Models\FundCriterion;
use App\Models\Organization;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class FundsController
 * @package App\Http\Controllers\Api\Platform\Organizations
 */
class ExternalFundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Organization $organization
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(
        Request $request,
        Organization $organization
    ) {
        $this->authorize('viewAny', [Fund::class, $organization]);

        $recordTypes = collect(record_repo()->getRecordTypes())->keyBy('key');

        $funds = Fund::whereHas('criteria.fund_criterion_validators', function(
            Builder $builder
        ) use ($organization) {
            $builder->whereHas('external_validator.validator_organization', function(
                Builder $builder
            ) use ($organization) {
                $builder->where('organizations.id', $organization->id);
            });
        })->get();

        $funds = $funds->map(function(Fund $fund) use ($organization, $recordTypes) {
            return self::fundToResource($fund, $organization, $recordTypes->toArray());
        });

        return [
            'data' => $funds
        ];
    }

    private static function fundToResource(Fund $fund, Organization $organization, array $recordTypes) {
        return [
            'id' => $fund->id,
            'name' => $fund->name,
            'organization' => $fund->organization->name,
            'criteria' => $fund->criteria()->whereHas('fund_criterion_validators', function (
                Builder $builder
            ) use ($organization, $recordTypes) {
                $builder->whereHas('external_validator.validator_organization', function(
                    Builder $builder
                ) use ($organization) {
                    $builder->where('organizations.id', $organization->id);
                });
            })->get()->map(function(FundCriterion $fundCriterion) use ($organization, $recordTypes) {
                return [
                    'id' => $fundCriterion->id,
                    'name' => $recordTypes[$fundCriterion->record_type_key]['name'],
                    'accepted' => $fundCriterion->fund_criterion_validators()->where([
                        'accepted' => true
                    ])->whereHas('external_validator', function(
                        Builder $builder
                    ) use ($organization) {
                        $builder->whereHas('validator_organization', function(
                            Builder $builder
                        ) use ($organization) {
                            $builder->where('organizations.id', $organization->id);
                        });
                    })->exists(),
                ];
            }),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @param Fund $fund
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(
        Request $request,
        Organization $organization,
        Fund $fund
    ) {
        $this->authorize('show', $organization);
        // $this->authorize('update', [$fund, $organization]);

        $criteria = $request->input('criteria');
        $recordTypes = collect(record_repo()->getRecordTypes())->keyBy('key');

        foreach ($criteria as $criterion) {
           FundCriterion::find($criterion['id'])->fund_criterion_validators()->whereHas(
               'external_validator.validator_organization', function(
               Builder $builder
           ) use ($organization) {
               $builder->where('organizations.id', $organization->id);
           })->update([
               'accepted' => $criterion['accepted'] ?? false
           ]);
        }

        return self::fundToResource($fund, $organization, $recordTypes->toArray());
    }
}
