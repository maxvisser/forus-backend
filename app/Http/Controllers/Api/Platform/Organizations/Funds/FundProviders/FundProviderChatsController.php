<?php

namespace App\Http\Controllers\Api\Platform\Organizations\Funds\FundProviders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Platform\Organizations\Funds\FundProviders\FundsProviderChats\IndexFundProviderChatRequest;
use App\Http\Requests\Api\Platform\Organizations\Funds\FundProviders\FundsProviderChats\StoreFundProviderChatRequest;
use App\Http\Resources\FundProviderChatResource;
use App\Models\Fund;
use App\Models\FundProvider;
use App\Models\FundProviderChat;
use App\Models\Organization;

class FundProviderChatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexFundProviderChatRequest $request
     * @param Organization $organization
     * @param Fund $fund
     * @param FundProvider $fundProvider
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(
        IndexFundProviderChatRequest $request,
        Organization $organization,
        Fund $fund,
        FundProvider $fundProvider
    ) {
        $this->authorize('showSponsor', [
            $fundProvider, $organization, $fund
        ]);

        $query = $fundProvider->fund_provider_chats();

        if ($request->has('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        return FundProviderChatResource::collection(
            $query->paginate($request->input('per_page'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFundProviderChatRequest $request
     * @param Organization $organization
     * @param Fund $fund
     * @param FundProvider $fundProvider
     * @return FundProviderChatResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(
        StoreFundProviderChatRequest $request,
        Organization $organization,
        Fund $fund,
        FundProvider $fundProvider
    ) {
        $this->authorize('showSponsor', [
            $fundProvider, $organization, $fund
        ]);

        $product_id = $request->input('product_id');

        $exists = $fundProvider->fund_provider_chats()->where(
            compact('product_id')
        )->exists();

        /** @var FundProviderChat $fundProviderChat */
        if (!$exists) {
            $fundProviderChat = $fundProvider->fund_provider_chats()->create([
                'product_id' => $request->input('product_id'),
                'identity_address' => auth_address(),
            ]);

            $fundProviderChat->addMessage(
                'sponsor',
                auth_address(),
                $request->input('message')
            );
        } else {
            $fundProviderChat = $fundProvider->fund_provider_chats()->where(
                compact('product_id')
            )->first();
        }

        return new FundProviderChatResource($fundProviderChat);
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @param Fund $fund
     * @param FundProvider $fundProvider
     * @param FundProviderChat $fundProviderChat
     * @return FundProviderChatResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(
        Organization $organization,
        Fund $fund,
        FundProvider $fundProvider,
        FundProviderChat $fundProviderChat
    ) {
        $this->authorize('showSponsor', [
            $fundProvider, $organization, $fund
        ]);

        return new FundProviderChatResource($fundProviderChat);
    }
}
