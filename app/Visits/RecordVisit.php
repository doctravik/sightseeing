<?php

namespace App\Visits;

use App\Visit;

trait RecordVisits
{
    /**
     * It has many visits.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorthMany
     */
    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    /**
     * Record visit.
     *
     * @return void
     */
    public function recordVisit()
    {
        if ($this->hasVisit($ip = $this->getClientIp())) {
            return;
        }

        $this->increment('visits_count');
        $this->save();

        $this->addVisit([
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip' => $ip
        ]);
    }

    /**
     * Get client ip.
     *
     * @return string
     */
    protected function getClientIp()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            return $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }

        return request()->ip();
    }

    /**
     * Save visit to the database.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function addVisit(array $attributes)
    {
        return $this->visits()->create($attributes);
    }

    /**
     * Check if it has visit with the given ip or id.
     *
     * @param  string|integer $visit
     * @return boolean
     */
    protected function hasVisit($visit)
    {
        if ($this->isValidIp($visit)) {
            return $this->visits()->whereIp($visit)->exists();
        }

        return $this->visits()->where('user_id', $visit)->exists();
    }

    /**
     * Check if it is a valid ip address.
     *
     * @param  string $ip
     * @return boolean
     */
    protected function isValidIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
}
