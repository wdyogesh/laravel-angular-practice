import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AdminLayoutRoutingModule } from './admin-layout-routing.module';


import { AdminLayoutComponent } from './admin-layout.component';
import { NavbarComponent } from './navbar/navbar.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { FooterComponent } from './footer/footer.component';

@NgModule({
    declarations: [
        AdminLayoutComponent,
        NavbarComponent,
        SidebarComponent,
        FooterComponent
    ],
    imports: [
        CommonModule,
        AdminLayoutRoutingModule
    ],
})

export class AdminLayoutModule { }
