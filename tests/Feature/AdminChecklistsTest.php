<?php

namespace Tests\Feature;

use App\Models\Checklist;
use App\Models\ChecklistGroup;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminChecklistsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test manage checklist groups.
     *
     * @return void
     */
    public function testManageChecklistGroups()
    {
        $admin = User::factory()->create(['is_admin' =>  1]);
        // As admin, direct to 'welcome' after create checklistgroup
        $response = $this->actingAs($admin)->post('admin/checklist_groups', [
            'name' => 'First Group'
        ]);
        $response->assertRedirect('/welcome');

        // Create checklistgroup successfully
        $group = ChecklistGroup::where('name', 'First Group')->first();
        $this->assertNotNull($group);

        // Can edit checklistgroup
        $group = ChecklistGroup::where('name', 'First Group')->first();
        $response = $this->actingAs($admin)->get("admin/checklist_groups/{$group->id}/edit");
        $response->assertStatus(200);

        // Update group with new name then direct to 'welcome'
        $response = $this->actingAs($admin)->put("admin/checklist_groups/{$group->id}", [
            'name' => 'Updated First Group'
        ]);
        $response->assertRedirect('/welcome');

        // Edit successfully
        $group = ChecklistGroup::where('name', 'Updated First Group')->first();
        $this->assertNotNull($group);

        // Check group is existed in sidebar
        $menu = (new MenuService())->getMenu();
        $groups = $menu['adminMenu'];
        $this->assertTrue($groups->contains($group));
    }

    /**
     * A test manage checklists.
     *
     * @return void
     */
    public function testManageChecklists()
    {
        $admin = User::factory()->create(['is_admin' =>  1]);
        $checklistGroup = ChecklistGroup::factory()->create();

        $checklistUrl = "admin/checklist_groups/{$checklistGroup->id}/checklists";

        $response = $this->actingAs($admin)->get("{$checklistUrl}/create");
        $response->assertStatus(200);


        $response = $this->actingAs($admin)->post($checklistUrl, [
            'name' => 'First Checklist'
        ]);
        $checklist = $checklistGroup->checklists()->first();

        $this->assertNotNull($checklist);
        $response->assertRedirect("{$checklistUrl}/{$checklist->id}/edit");

        
        $response = $this->actingAs($admin)->put("{$checklistUrl}/{$checklist->id}", [
            'name' => 'Updated Checklist'
        ]);
        $response->assertRedirect("{$checklistUrl}/{$checklist->id}/edit");

        $checklist = $checklistGroup->checklists()->where('name', 'Updated Checklist')->first();
        $this->assertNotNull($checklist);

        $menu = (new MenuService())->getMenu();
        $checklists = $menu['adminMenu']->first()->checklists;
        $this->assertTrue($checklists->contains($checklist));

        $response = $this->actingAs($admin)->delete("{$checklistUrl}/{$checklist->id}");
        $checklists = $checklistGroup->checklists;
        $this->assertFalse( $checklists->contains($checklist));
    }
}
