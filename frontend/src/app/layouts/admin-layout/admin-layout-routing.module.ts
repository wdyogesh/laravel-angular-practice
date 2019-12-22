import { NgModule, ViewChild } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AdminLayoutComponent } from './admin-layout.component';

const routes: Routes = [
    {
        path: '',
        component : AdminLayoutComponent,
        children: [
            {
                path: '',
                loadChildren : () => import('../../modules/admin/admin.module').then(m => m.AdminModule),
            }
        ]
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})

export class AdminLayoutRoutingModule {
    constructor() {
    }
}
