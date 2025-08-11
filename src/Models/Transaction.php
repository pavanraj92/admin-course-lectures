<?php

namespace admin\courses\Models;

// use admin\courses\Models\CoursePurchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;

class Transaction extends Model
{
    use Sortable;
    protected $fillable = [
        'user_id',
        'payment_gateway',
        'transaction_reference',
        'amount',
        'currency',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public $sortable = ['transaction_reference', 'amount', 'status', 'created_at'];

    const STATUSES = ['pending', 'success', 'failed'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });

        $query->when($filters['keyword'] ?? null, function ($q, $keyword) {
            $q->where(function ($sub) use ($keyword) {
                $sub->where('transaction_reference', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    });
            });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Future link to course purchase
    // public function coursePurchase(): BelongsTo
    // {
    //     return $this->belongsTo(CoursePurchase::class, 'transaction_reference', 'transaction_id');
    // }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
