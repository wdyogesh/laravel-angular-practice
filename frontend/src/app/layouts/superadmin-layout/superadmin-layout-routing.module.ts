import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SuperAdminLayoutComponent } from './superadmin-layout.component';


const routes: Routes = [
    {
        path: '',
        component : SuperAdminLayoutComponent,
        children: [
            {
                path: '',
                loadChildren : () => import('../../modules/superadmin/superadmin.module').then(m => m.SuperadminModule),
            }
        ]
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SuperadminLayoutRoutingModule { }
