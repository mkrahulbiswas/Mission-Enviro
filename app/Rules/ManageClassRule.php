<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ManageClassRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $id;
    private $for;
    private $model;
    private $count;

    public function __construct($id, $for, $model, $whereId)
    {
        $this->id = $id;
        $this->for = $for;
        $this->model = $model;
        $this->whereId = $whereId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->model == 'ManageClass') {
            if ($this->for == 'schoolName') {
                if ($this->id == 0) {
                    foreach (app("App\\$this->model")::where('schoolId', $this->whereId)->get() as $temp) {
                        if ($temp->class == $value) {
                            $this->count = 1;
                        } else {
                            $this->count = 0;
                        }
                    }
                } else {
                    foreach (app("App\\$this->model")::where('schoolId', $this->whereId)->where('id', '!=', $this->id)->get() as $temp) {
                        if ($temp->class == $value) {
                            $this->count = 1;
                        } else {
                            $this->count = 0;
                        }
                    }
                }
            }
        } else {
            if ($this->for == 'name') {
                if ($this->id == 0) {
                    foreach (app("App\\$this->model")::where('category2Id', $this->whereId)->get() as $temp) {
                        if ($temp->name == $value) {
                            $this->count = 1;
                        } else {
                            $this->count = 0;
                        }
                    }
                } else {
                    foreach (app("App\\$this->model")::where('category2Id', $this->whereId)->where('id', '!=', $this->id)->get() as $temp) {
                        if ($temp->name == $value) {
                            $this->count = 1;
                        } else {
                            $this->count = 0;
                        }
                    }
                }
            }
        }

        if ($this->count > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The name has already been taken.';
    }
}
