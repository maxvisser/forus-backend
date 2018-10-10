<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FundTopUp
 * @package App\Models
 * @property mixed $id
 * @property integer $fund_id
 * @property float $amount
 * @property integer|null $bunq_transaction_id
 * @property string $code
 * @property string $state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class FundTopUp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fund_id', 'amount', 'bunq_transaction_id', 'code', 'state'
    ];
}
