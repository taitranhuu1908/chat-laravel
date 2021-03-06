<?php

namespace Tests\Feature\Controller;

use App\Enums\FriendRequestStatus;
use App\Enums\FriendStatus;
use App\Models\User;
use App\Repositories\Friend\FriendRepository;
use App\Repositories\FriendRequest\FriendRequestRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->friendRepository = new FriendRepository();
        $this->friendRequestRepository = new FriendRequestRepository();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_get_all_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/user');

        $response->assertStatus(200);
    }

    public function test_edit_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $updated = [
            'full_name' => 'New Name',
            'phone' => '09876554321',
            'avatar' => 'https://picsum.photos/1200/800',
            'address' => 'New Address',
            'country' => 'Vietnam',
        ];

        $response = $this->json('PUT', '/user/edit-profile', $updated);

        $response->assertStatus(200);

        $user = $response->json()['data'];

        $this->assertEquals($updated['full_name'], $user['full_name']);
        $this->assertEquals($updated['phone'], $user['profile']['phone']);
        $this->assertEquals($updated['address'], $user['profile']['address']);
        $this->assertEquals($updated['country'], $user['profile']['country']);

        $this->assertDatabaseHas('users', [
            'full_name' => 'New Name',
        ]);

        $this->assertDatabaseHas('profiles', [
            'phone' => '09876554321',
            'address' => 'New Address',
            'country' => 'Vietnam',
        ]);
    }

    public function test_add_friend_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $friend = User::factory()->create();

        $response = $this->json('POST', '/user/add-friend-request', [
            'email' => $friend->email,
            'description' => 'Test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('friend_requests', [
            'user_id' => $friend->id,
            'request_id' => $user->id,
            'description' => 'Test',
            'status' => FriendRequestStatus::PENDING
        ]);
    }

    public function test_return_error_if_request_friend_myself()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->json('POST', '/user/add-friend-request', [
            'email' => $user->email,
            'description' => 'Test',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'B???n kh??ng th??? th??m m??nh l??m b???n',
        ]);
    }

    public function test_return_error_if_request_friend_user_does_not_exits()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->json('POST', '/user/add-friend-request', [
            'email' => 'qwe123@gmail.com',
            'description' => 'Test',
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Ng?????i d??ng kh??ng t???n t???i',
        ]);
    }

    public function test_return_error_if_user_sent_a_friend_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $friend = User::factory()->create();

        $this->friendRequestRepository->create([
            'user_id' => $friend->id, //
            'request_id' => $user->id,
            'description' => 'Test',
        ]);

        $response = $this->json('POST', '/user/add-friend-request', [
            'email' => $friend->email,
            'description' => 'Test',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'B???n ???? g???i l???i m???i k???t b???n cho ng?????i n??y',
        ]);
    }

    public function test_return_error_if_user_is_already_friend()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $friend = User::factory()->create();

        $this->friendRepository->create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
        ]);
        $this->friendRepository->create([
            'user_id' => $friend->id,
            'friend_id' => $user->id,
        ]);

        $response = $this->json('POST', '/user/add-friend-request', [
            'email' => $friend->email,
            'description' => 'Test',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Hai b???n ???? tr??? th??nh b???n b??',
        ]);
    }

    public function test_block_friend_request()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $this->actingAs($friend);

        $this->friendRequestRepository->create([
            'user_id' => $friend->id, //
            'request_id' => $user->id,
            'description' => 'Test',
        ]);

        $response = $this->json('POST', '/user/block-friend-request', [
            'user_block_id' => $user->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('friend_requests', [
            'user_id' => $friend->id,
            'request_id' => $user->id,
            'status' => FriendRequestStatus::REJECTED
        ]);

    }

    public function test_accept_friend_request()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $this->actingAs($friend);


        $this->friendRequestRepository->create([
            'user_id' => $friend->id, //
            'request_id' => $user->id,
            'description' => 'Test',
        ]);

        $response = $this->json('POST', '/user/accept-friend-request', [
            'user_accept_id' => $user->id,
        ]);

        $this->assertDatabaseHas('friends', [
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => FriendStatus::FRIEND
        ]);

        $this->assertDatabaseHas('friends', [
            'user_id' => $friend->id,
            'friend_id' => $user->id,
            'status' => FriendStatus::FRIEND
        ]);

        $this->assertDatabaseHas('friend_requests', [
            'user_id' => $friend->id,
            'request_id' => $user->id,
            'status' => FriendRequestStatus::ACCEPTED,
        ]);

        $response->assertStatus(200);
    }

    public function test_block_friend()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $this->actingAs($user);

        $this->friendRepository->create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
        ]);
        $this->friendRepository->create([
            'user_id' => $friend->id,
            'friend_id' => $user->id,
        ]);

        $response = $this->json('POST', '/user/block-friend', [
            'user_block_id' => $friend->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('friends', [
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => FriendStatus::BLOCKED,
        ]);

        $this->assertDatabaseHas('friends', [
            'user_id' => $friend->id,
            'friend_id' => $user->id,
            'status' => FriendStatus::BLOCKED,
        ]);
    }
}

