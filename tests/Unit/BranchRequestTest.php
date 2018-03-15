<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class BranchRequestTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBranchRequest()
    {
       // $user = User::first();
       // $response = $this->actingAs($user)->json("GET", "/getEmployeeRequests", ["corpId" => "7", "approved" => 1]);
       // dd($response);
       $this->assertTrue(true);
    }
}
