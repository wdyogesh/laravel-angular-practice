import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeLayoutComponent } from './home-layout.component';

const routes: Routes = [
    {
        path: '',
        component : HomeLayoutComponent,
        children: [
            {
                path: '',
                loadChildren : () => import('../../modules/home/home.module').then(m => m.HomeModule),
            }
        ]
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HomeLayoutRoutingModule { }
