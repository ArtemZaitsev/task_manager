<?php

declare(strict_types=1);

use App\Models\Direction;
use App\Orchid\Screens\Audit\AuditListScreen;
use App\Orchid\Screens\Component\PhysicalObject\PhysicalObjectEditScreen;
use App\Orchid\Screens\Component\PhysicalObject\PhysicalObjectListScreen;
use App\Orchid\Screens\Component\Subsystem\SubsystemEditScreen;
use App\Orchid\Screens\Component\Subsystem\SubsystemListScreen;
use App\Orchid\Screens\Component\System\SystemEditScreen;
use App\Orchid\Screens\Component\System\SystemListScreen;
use App\Orchid\Screens\Direction\DirectionEditScreen;
use App\Orchid\Screens\Direction\DirectionListScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Family\FamilyEditScreen;
use App\Orchid\Screens\Family\FamilyListScreen;
use App\Orchid\Screens\Group\GroupEditScreen;
use App\Orchid\Screens\Group\GroupListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Product\ProductEditScreen;
use App\Orchid\Screens\Product\ProductListScreen;
use App\Orchid\Screens\Project\ProjectEditScreen;
use App\Orchid\Screens\Project\ProjectListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Subgroup\SubgroupEditScreen;
use App\Orchid\Screens\Subgroup\SubgroupListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;
use App\Orchid\Screens\EmailSenderScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

Route::screen('email', EmailSenderScreen::class)
    ->name('platform.email')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Email sender');
    });

Route::screen('physical_objects', PhysicalObjectListScreen::class)
    ->name(PhysicalObjectListScreen::ROUTE_NAME);

Route::screen('physical_object/{entity?}', PhysicalObjectEditScreen::class)
    ->name(PhysicalObjectEditScreen::ROUTE_NAME);

Route::screen('systems', SystemListScreen::class)
    ->name(SystemListScreen::ROUTE_NAME);

Route::screen('system/{entity?}', SystemEditScreen::class)
    ->name(SystemEditScreen::ROUTE_NAME);

Route::screen('subsystems', SubsystemListScreen::class)
    ->name(SubsystemListScreen::ROUTE_NAME);

Route::screen('subsystem/{entity?}', SubsystemEditScreen::class)
    ->name(SubsystemEditScreen::ROUTE_NAME);


Route::screen('audit', AuditListScreen::class)
    ->name('platform.audit.list');

Route::screen('projects', ProjectListScreen::class)
    ->name('platform.project.list');

Route::screen('project/{project?}', ProjectEditScreen::class)
    ->name('platform.project.edit');

Route::screen('families', FamilyListScreen::class)
    ->name('platform.family.list');

Route::screen('family/{family?}', FamilyEditScreen::class)
    ->name('platform.family.edit');

Route::screen('product/{product?}', ProductEditScreen::class)
    ->name('platform.product.edit');

Route::screen('products', ProductListScreen::class)
    ->name('platform.product.list');

Route::screen('direction/{direction?}', DirectionEditScreen::class)
    ->name('platform.direction.edit');

Route::screen('directions', DirectionListScreen::class)
    ->name('platform.direction.list');

Route::screen('group/{group?}', GroupEditScreen::class)
    ->name('platform.group.edit');

Route::screen('groups', GroupListScreen::class)
    ->name('platform.group.list');

Route::screen('subgroup/{subgroup?}', SubgroupEditScreen::class)
    ->name('platform.subgroup.edit');

Route::screen('subgroups', SubgroupListScreen::class)
    ->name('platform.subgroup.list');

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Example screen');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', 'Idea::class','platform.screens.idea');
