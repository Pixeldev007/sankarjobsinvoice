<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $payment_mode
 * @property float $amount
 * @property string $payment_date
 * @property int|null $transaction_id
 * @property string|null $meta
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereInvoiceId($value)
 * @method static Builder|Payment whereMeta($value)
 * @method static Builder|Payment whereNotes($value)
 * @method static Builder|Payment wherePaymentDate($value)
 * @method static Builder|Payment wherePaymentMode($value)
 * @method static Builder|Payment whereTransactionId($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property int $is_approved
 * @property-read Invoice $invoice
 */
class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'payments';

    protected $fillable = [
        'invoice_id', 'amount', 'payment_date', 'payment_mode', 'transaction_id', 'notes', 'is_approved',
    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'amount' => 'double',
        'payment_date' => 'datetime',
        'payment_mode' => 'string',
        'transaction_id' => 'string',
        'meta' => 'json',
        'notes' => 'string',
        'user_id' => 'integer',
        'is_approved' => 'integer',
    ];

    const PAYMENT_ATTACHMENT = 'payment_attachment';

    const FULLPAYMENT = 2;
    const PARTIALLYPAYMENT = 3;
    const PAYMENT_TYPE = [
        self::FULLPAYMENT => 'Full Payment',
        self::PARTIALLYPAYMENT => 'Partially Payment',
    ];

    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
    const STATUS_ALL = 3; // Ensure this is correctly defined
    const PAID = 'Paid';
    const PROCESSING = 'Processing';
    const DENIED = 'Denied';
    const STATUS_ARR_ALL = 'All'; // Ensure this is correctly defined

    const PAYMENT_STATUS = [
        self::STATUS_ALL => self::STATUS_ARR_ALL,
        self::PENDING => self::PROCESSING,
        self::APPROVED => self::PAID,
        self::REJECTED => self::DENIED,
    ];

    const STATUS = [
        'RECEIVED_AMOUNT' => 'Received Amount',
        'PAID_AMOUNT' => 'Paid Amount',
        'DUE_AMOUNT' => 'Due Amount',
    ];

    public static $rules = [
        'payment_type' => 'required',
        'amount' => 'required',
        'payment_mode' => 'required',
        'payment_attachment' => 'nullable|mimes:pdf,png,jpeg,jpg',
    ];

    public function getPaymentAttachmentAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PAYMENT_ATTACHMENT)->first();
        if ($media !== null) {
            return $media->getFullUrl();
        }

        return false;
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
