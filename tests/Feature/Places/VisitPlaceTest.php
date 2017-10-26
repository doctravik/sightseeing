<?php

namespace Tests\Feature\Places;

use App\Place;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VisitPlaceTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanRecordVisitsToPlace()
    {
        $place = $this->createPlace();

        $this->assertVisitsCount(0, $place);

        $response = $this->get("/places/$place->slug");

        $this->assertVisitsCount(1, $place->fresh());
    }

    public function testItRemovesVisitsWhenPlaceIsDeleted()
    {
        [$author, $place] = $this->createPlaceWithAuthor();

        $this->assertVisitsCount(0, $place);

        $response = $this->get("/places/{$place->slug}");

        $this->assertVisitsCount(1, $place->fresh());

        $response = $this->actingAs($author)->json('delete', "/api/places/{$place->slug}");

        $this->assertNull($place->fresh());
        $this->assertCount(0, $place->visits);
    }

    public function testItCannotRecordVisitsTwiceFromOneIp()
    {
        $place = $this->createPlace();

        $this->assertVisitsCount(0, $place);

        $response = $this->get("/places/$place->slug");

        $this->assertVisitsCount(1, $place->fresh());

        $response = $this->get("/places/$place->slug");

        $this->assertVisitsCount(1, $place->fresh());
    }

    protected function assertVisitsCount($count, Place $place)
    {
        $this->assertCount($count, $place->visits);
        $this->assertEquals($count, $place->visits_count);
    }
}
