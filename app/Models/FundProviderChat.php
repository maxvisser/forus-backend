<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FundProviderChat
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $fund_provider_id
 * @property string $identity_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FundProvider|null $fund_provider
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FundProviderChatMessage[] $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat whereFundProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat whereIdentityAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FundProviderChat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FundProviderChat extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id', 'fund_provider_id', 'identity_address',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fund_provider() {
        return $this->belongsTo(FundProvider::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages() {
        return $this->hasMany(FundProviderChatMessage::class);
    }

    /**
     * @param $counterpart
     * @param $identity_address
     * @param $message
     * @return FundProviderChatMessage
     */
    public function addMessage(
        $counterpart,
        $identity_address,
        $message
    ) {
        /** @var FundProviderChatMessage $message */
        $message = $this->messages()->create(array_merge(compact(
            'identity_address', 'message', 'counterpart'
        ), [
            'sponsor_seen' => $counterpart == 'sponsor',
            'provider_seen' => $counterpart == 'provider',
        ]));

        return $message;
    }
}
