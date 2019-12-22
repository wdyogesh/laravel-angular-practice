import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { SuperadminRoutingModule } from './superadmin-routing.module';
import { ModulesComponent } from './modules/modules.component';
import { ProfileComponent } from './profile/profile.component';


@NgModule({
  declarations: [ModulesComponent, ProfileComponent],
  imports: [
    CommonModule,
    SuperadminRoutingModule,
    FormsModule,
    ReactiveFormsModule,
  ]
})
export class SuperadminModule { }
