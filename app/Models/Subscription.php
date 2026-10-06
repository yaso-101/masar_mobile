<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'phone_number', 'is_paid'])]
class Subscription extends Model
{
    /**
     * Stored as 'yes' / 'no' in the database (easy to read and edit by hand),
     * but used as true / false in the code.
     */
    protected function isPaid(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === 'yes',
            set: fn ($value) => in_array($value, [true, 1, '1', 'yes'], true) ? 'yes' : 'no',
        );
    }

    /**
     * The user this subscription belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Store every number the same way: "+964 770 123 4567", "00964770..."
     * "0770-123-4567" and "7701234567" all become "07701234567".
     */
    public static function normalizePhone(?string $phone): string
    {
        $phone = preg_replace('/[\s\-()]/', '', (string) $phone);
        $phone = preg_replace('/^(\+|00)964/', '0', $phone);

        // Typed without the leading 0
        if (preg_match('/^7\d{9}$/', $phone)) {
            $phone = '0' . $phone;
        }

        return $phone;
    }
}
