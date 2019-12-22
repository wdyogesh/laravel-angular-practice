import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ModulesComponent } from './modules/modules.component';
import { AuthenticationGuard } from 'src/app/guard/authentication.guard';
import { ProfileComponent } from './profile/profile.component';


const routes: Routes = [
    {
        path: '',
        redirectTo: 'modules',
    },
    {
        path: 'modules',
        component: ModulesComponent,
        canActivate: [AuthenticationGuard]
    },
    {
        path: 'profile',
        component: ProfileComponent,
        canActivate: [AuthenticationGuard]
    },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SuperadminRoutingModule { }
