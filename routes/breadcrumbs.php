<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push(__('Dashboard'), route('dashboard'));
});

Breadcrumbs::for('settings.profile', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.profile'), route('settings.profile'));
});

Breadcrumbs::for('settings.password', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.password'), route('settings.password'));
});

Breadcrumbs::for('settings.appearance', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.appearance'), route('settings.appearance'));
});

Breadcrumbs::for('settings.language', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.locale'), route('settings.language'));
});

Breadcrumbs::for('settings.institution', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.institution_identity'), route('settings.institution'));
});

// Backwards-compatible alias
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.profile'), route('settings.profile'));
});
