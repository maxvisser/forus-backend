<?php

namespace App\Http\Controllers\Api\Platform\Organizations;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationBasicResource;
use App\Models\Organization;
use Illuminate\Http\Request;

class ValidatorOrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Organization $organization
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(
        Request $request,
        Organization $organization
    ) {
        return OrganizationBasicResource::collection(
            $organization->external_validators()->paginate()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @param Organization $validatorOrganization
     * @return OrganizationBasicResource
     */
    public function show(
        Organization $organization,
        Organization $validatorOrganization
    ) {
        return new OrganizationBasicResource($validatorOrganization);
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return OrganizationBasicResource
     */
    public function store(
        Request $request,
        Organization $organization
    ) {
        $validatorOrganization = Organization::find(
            $request->input('organization_id')
        );

        $organization->external_validators()->syncWithoutDetaching(
            $validatorOrganization->id
        );

        return new OrganizationBasicResource($validatorOrganization);
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param Organization $validatorOrganization
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(
        Request $request,
        Organization $organization,
        Organization $validatorOrganization
    ) {
        $organization->external_validators()->detach($validatorOrganization->id);

        return response()->json([], 200);
    }
}
