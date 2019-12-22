import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SuperAdminLayoutComponent } from './superadmin-layout.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { NavbarComponent } from './navbar/navbar.component';
import { FooterComponent } from './footer/footer.component';

import { SuperadminLayoutRoutingModule } from './superadmin-layout-routing.module';


@NgModule({
    declarations: [
        SuperAdminLayoutComponent,
        SidebarComponent,
        NavbarComponent,
        FooterComponent
    ],
    imports: [
        CommonModule,
        SuperadminLayoutRoutingModule
    ]
})
export class SuperadminLayoutModule { }
