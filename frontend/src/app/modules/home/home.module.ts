import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

import { HomeRoutingModule } from './home-routing.module';
import { LoginComponent } from './login/login.component';
import { LandingComponent } from './landing/landing.component';
import { SignupComponent } from './signup/signup.component';


@NgModule({
  declarations: [LoginComponent, LandingComponent, SignupComponent],
  imports: [
    CommonModule,
    HomeRoutingModule,
    FormsModule,
    ReactiveFormsModule
  ]
})
export class HomeModule { }
