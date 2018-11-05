<?php

namespace App\Commons;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Scalar\String_;

class YearRangeValidator implements Rule
{
    private $from;
    private $to;

    public function __construct(string  $from, string  $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value >= $this->from && $value<= $this->to == null?Carbon::now()->year:$this->to;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be between '.$this->from. 'and ' . $this->to == null?Carbon::now()->year:$this->to;
    }
}
