import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { UserLayoutComponent } from './user-layout.component';
import { AuthenticationGuard } from '../../guard/authentication.guard';

const routes: Routes = [
    {
        path: '',
        component : UserLayoutComponent,
        children: [
            {
                path: '',
                loadChildren : () => import('../../modules/user/user.module').then(m => m.UserModule),
                canActivate: [AuthenticationGuard]
            }
        ]
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UserLayoutRoutingModule { }
