<?php

use App\Models\User;

test('users can view the introduction', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for individuals.');

    $response = $this->actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ]);

    $response->assertRedirect(localized_route('individuals.show-role-selection'));

    $user = $user->fresh();

    expect($user->finished_introduction)->toBeTrue();

    $user->update(['context' => 'organization']);

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for community organizations.');

    $response = $this->actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ]);

    $response->assertRedirect(localized_route('organizations.show-type-selection'));

    $response = $this->actingAs($user)->get(localized_route('dashboard'));

    $response->assertRedirect(localized_route('organizations.show-type-selection'));

    $user->update(['context' => 'regulated-organization']);

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for regulated organizations.');

    $response = $this->actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ]);

    $response->assertRedirect(localized_route('regulated-organizations.show-type-selection'));

    $response = $this->actingAs($user)->get(localized_route('dashboard'));

    $response->assertRedirect(localized_route('regulated-organizations.show-type-selection'));

    $user->update(['context' => 'regulated-organization-employee']);

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for regulated organization employees.');

    $response = $this->actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('user’s first name can be retrieved', function () {
    $user = User::factory()->create(['name' => 'Jonny Appleseed']);
    expect($user->first_name)->toEqual('Jonny');
});

test('user’s contact person can be retrieved', function () {
    $user = User::factory()->create(['name' => 'Jonny Appleseed', 'preferred_contact_person' => 'me', 'support_person_name' => 'Jenny Appleseed']);

    expect($user->contact_person)->toEqual('Jonny');

    $user->update(['preferred_contact_person' => 'support_person']);

    expect($user->contact_person)->toEqual('Jenny Appleseed');
});

test('user’s vrs requirement can be retrieved', function () {
    $user = User::factory()->create([
        'preferred_contact_person' => 'me',
        'vrs' => true,
        'support_person_vrs' => false,
    ]);

    expect($user->requires_vrs)->toBeTrue();

    $user->update(['preferred_contact_person' => 'support_person']);

    expect($user->requires_vrs)->toBeFalse();
});

test('user’s primary contact point can be retrieved', function () {
    $user = User::factory()->create([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
    ]);

    expect($user->primary_contact_point)->toEqual('jonny@example.com');

    $user->update([
        'phone' => '9055555555',
        'vrs' => true,
        'preferred_contact_person' => 'support-person',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9054444444',
        'support_person_vrs' => false,
    ]);

    expect($user->primary_contact_point)->toEqual('jenny@example.com');

    $user->update(['preferred_contact_method' => 'phone']);

    expect($user->primary_contact_point)->toEqual('1 (905) 444-4444');

    $user->update(['preferred_contact_person' => 'me']);

    expect($user->primary_contact_point)->toEqual("1 (905) 555-5555.  \nJonny requires VRS for phone calls");
});

test('user’s primary contact method can be retrieved', function () {
    $user = User::factory()->create([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
        'phone' => '9055555555',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' => 'email',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9054444444',
        'support_person_vrs' => false,
    ]);

    expect($user->primary_contact_method)->toEqual('Send an email to Jonny at <jonny@example.com>.');

    $user->update(['preferred_contact_person' => 'support-person']);

    expect($user->primary_contact_method)->toEqual('Send an email to Jonny’s support person, Jenny Appleseed, at <jenny@example.com>.');

    $user->update(['preferred_contact_method' => 'phone']);

    expect($user->primary_contact_method)->toEqual('Call Jonny’s support person, Jenny Appleseed, at 1 (905) 444-4444.');

    $user->update(['preferred_contact_person' => 'me']);

    expect($user->primary_contact_method)->toEqual("Call Jonny at 1 (905) 555-5555.  \nJonny requires VRS for phone calls.");
});

test('user’s alternate contact point can be retrieved', function () {
    $user = User::factory()->create([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
    ]);

    expect($user->alternate_contact_point)->toBeNull();

    $user->update([
        'phone' => '9055555555',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' => 'phone',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9054444444',
        'support_person_vrs' => false,
    ]);

    expect($user->alternate_contact_point)->toEqual('jonny@example.com');

    $user->update(['preferred_contact_person' => 'support-person']);

    expect($user->alternate_contact_point)->toEqual('jenny@example.com');

    $user->update(['preferred_contact_method' => 'email']);

    expect($user->alternate_contact_point)->toEqual('1 (905) 444-4444');

    $user->update(['preferred_contact_person' => 'me']);

    expect($user->alternate_contact_point)->toEqual("1 (905) 555-5555  \nJonny requires VRS for phone calls.");
});

test('user’s alternate contact method can be retrieved', function () {
    $user = User::factory()->create([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
    ]);

    expect($user->alternate_contact_method)->toBeNull();

    $user->update([
        'phone' => '9055555555',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' => 'phone',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9054444444',
        'support_person_vrs' => false,
    ]);

    expect($user->alternate_contact_method)->toEqual('<jonny@example.com>');

    $user->update(['preferred_contact_person' => 'support-person']);

    expect($user->alternate_contact_method)->toEqual('<jenny@example.com>');

    $user->update(['preferred_contact_method' => 'email']);

    expect($user->alternate_contact_method)->toEqual('1 (905) 444-4444');

    $user->update(['preferred_contact_person' => 'me']);

    expect($user->alternate_contact_method)->toEqual("1 (905) 555-5555  \nJonny requires VRS for phone calls.");
});
