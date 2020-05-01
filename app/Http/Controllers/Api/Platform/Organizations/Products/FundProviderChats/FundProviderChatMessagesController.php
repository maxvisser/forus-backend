<?php

namespace App\Http\Controllers\Api\Platform\Organizations\Products\FundProviderChats;

use App\Http\Controllers\Controller;
use App\Http\Resources\FundProviderChatMessageResource;
use App\Models\FundProviderChat;
use App\Models\FundProviderChatMessage;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\Request;

class FundProviderChatMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Organization $organization
     * @param Product $product
     * @param FundProviderChat $fundProviderChat
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(
        Request $request,
        Organization $organization,
        Product $product,
        FundProviderChat $fundProviderChat
    ) {
        $this->authorize('show', [$organization]);
        $this->authorize('showFunds', [$product, $organization]);

        $fundProviderChat->messages()->update([
            'provider_seen' => true
        ]);

        return FundProviderChatMessageResource::collection(
            $fundProviderChat->messages()->paginate($request->input('per_page'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @param Product $product
     * @param FundProviderChat $fundProviderChat
     * @return FundProviderChatMessageResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(
        Request $request,
        Organization $organization,
        Product $product,
        FundProviderChat $fundProviderChat
    ) {
        $this->authorize('show', [$organization]);
        $this->authorize('showFunds', [$product, $organization]);

        return new FundProviderChatMessageResource($fundProviderChat->addMessage(
            'provider',
            auth_address(),
            $request->input('message')
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Organization $organization
     * @param Product $product
     * @param FundProviderChat $fundProviderChat
     * @param FundProviderChatMessage $fundProviderChatMessage
     * @return FundProviderChatMessageResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(
        Request $request,
        Organization $organization,
        Product $product,
        FundProviderChat $fundProviderChat,
        FundProviderChatMessage $fundProviderChatMessage
    ) {
        $this->authorize('show', [$organization]);
        $this->authorize('showFunds', [$product, $organization]);

        $fundProviderChatMessage->update([
            'provider_seen' => true
        ]);

        return new FundProviderChatMessageResource(
            $fundProviderChat->messages()->find($fundProviderChatMessage->id)
        );
    }
}
