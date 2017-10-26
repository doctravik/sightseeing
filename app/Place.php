<?php

namespace App;

use App\Geo\NearestPlaces;
use App\Visits\RecordVisits;
use App\Filters\PlaceFilters;
use App\Favorites\Favoritable;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\InvalidArgumentException;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class Place extends Model
{
    use PostgisTrait, RecordVisits, Favoritable;

    /**
     * Miss assignable fields.
     *
     * @var array
     */
    protected $fillable = [
        'latitude',
        'longitude',
        'name',
        'address',
        'country',
        'description',
        'user_id',
        'location',
        'image'
    ];

    protected $postgisFields = [];

    protected $postgisTypes = [
        'location' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ]
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Set the place's description.
     *
     * @param  string  $value
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = Purifier::clean($value);
    }

    /**
     * Set the place's name.
     *
     * @param  string  $name
     * @return void
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = str_slug($name);
    }

    /**
     * Create instance of the NearPlaces as initial point for building full query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  mixed $place
     * @return NearestPlaces
     */
    public function scopeNearestTo($builder, ...$place)
    {
        return new NearestPlaces($builder, ...$this->normalizeCoordinates(...$place));
    }

    /**
     * Set $coordinates to the proper array format depent on input value.
     *
     * @param  mixed $coordinates
     * @return array
     */
    protected function normalizeCoordinates($coordinates)
    {
        if (func_num_args() === 2) {
            return func_get_args();
        }

        if ($coordinates instanceof Place) {
            return [$coordinates->latitude, $coordinates->longitude];
        }

        if (is_array($coordinates)) {
            return [
                $coordinates['latitude'] ?? $coordinates[0] ?? 0,
                $coordinates['longitude'] ?? $coordinates[1] ?? 0
            ];
        }

        throw new InvalidArgumentException('Undefined argument in nearestTo function');
    }

    /**
     * Filter places consider queries.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query)
    {
        return (new PlaceFilters($query))->apply();
    }

    /**
     * Save path to the image in the attribute of Place's model.
     *
     * @param string $path
     * @return void
     */
    public function addPhoto($path)
    {
        $this->image = $path;

        $this->save();
    }

    /**
     * Remove photo's file from filesystem.
     *
     * @return void
     */
    public function deletePhoto()
    {
        if ($this->image) {
            Storage::delete($this->image);
        }
    }

    /**
     * Place has author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
