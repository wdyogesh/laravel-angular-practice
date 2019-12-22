import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AuthenticationGuard } from './guard/authentication.guard';

const routes: Routes = [
    {
        path: '',
        loadChildren: () => import('./layouts/home-layout/home-layout.module').then(m => m.HomeLayoutModule),
    },
    {
        path: 'superadmin',
        canActivate: [AuthenticationGuard],
        loadChildren: () => import('./layouts/superadmin-layout/superadmin-layout.module').then(m => m.SuperadminLayoutModule),
    },
    {
        path: 'admin',
        canActivate: [AuthenticationGuard],
        loadChildren: () => import('./layouts/admin-layout/admin-layout.module').then(m => m.AdminLayoutModule),
    },
    {
        path: 'user',
        loadChildren: () => import('./layouts/user-layout/user-layout.module').then(m => m.UserLayoutModule),
    }, {
        path: '**',
        loadChildren: () => import('./shared/page-not-found/page-not-found.module').then(m => m.PageNotFoundModule),
    },
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule { }
