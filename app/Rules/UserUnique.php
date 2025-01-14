<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserUnique implements Rule
{
    /**
     * @var $userId
     */
    protected $userId;

    /**
     * @var $messageKey
     */
    protected $messageKey;

    /**
     * Create a new rule instance.
     *
     * @param null $userId
     * @param null $messageKey
     */
    public function __construct($userId = null, $messageKey = null)
    {
        $this->userId = $userId;
        $this->messageKey = $messageKey;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $q = DB::table(User::query()->getQuery()->from)->where($attribute, $value);
        if ($this->userId) {
            $q->where('id', '<>', $this->userId);
        }//end if

        $count = $q->count();
        if ($count) {
            return false;
        }//end if

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->messageKey ? trans($this->messageKey) : trans('validation.unique');
    }
}
