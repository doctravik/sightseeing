<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /**
     * Attributes allowed for mass assignments.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'ip'];

    /**
     * Get all of the owning visitable models.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\MorthTo
     */
    public function visitable()
    {
        return $this->morphTo();
    }
}
