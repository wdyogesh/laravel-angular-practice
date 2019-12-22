import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { HomeLayoutRoutingModule } from './home-layout-routing.module';

import { NavbarComponent } from './navbar/navbar.component';
import { HomeLayoutComponent } from './home-layout.component';


@NgModule({
    declarations: [
        HomeLayoutComponent,
        NavbarComponent
    ],
    imports: [
        CommonModule,
        HomeLayoutRoutingModule
    ]
})
export class HomeLayoutModule { }
