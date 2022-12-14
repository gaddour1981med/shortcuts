<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Link;

use function PHPUnit\Framework\assertTrue;

class UserCanDeleteLinkTest extends TestCase
{
    //use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_delete_own_links()
    {
        /** add new link */        
        $link= Link::factory()->create();

        /** call http delete with api  */
        $this->actingAs($link->user)
             ->withSession(['banned' => false])
             ->get("/en/links/$link->id/delete");

        /** check that record link is deleted */
        $this->assertDatabaseMissing('links', [
            'id' => $link->id
        ]);
    }


    public function test_user_can_not_delete_links_of_others()
    {
        /** add new user */
        $user=User::factory()->create();

        /** add new link by another user */      
        $link= Link::factory()->create();

        /** call http delete with api  */
        $this->actingAs($user)
             ->withSession(['banned' => false])
             ->get("/en/links/$link->id/delete");

        /** check that record link is deleted */
        $this->assertDatabaseHas('links', [
              'id' => $link->id
        ]);
    }
}
