import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ConfirmationService } from 'primeng/api';
import { ToastModule } from 'primeng/toast';
import { MessageService } from 'primeng/api';
import { DataTableModule } from 'angular-6-datatable';
import { PaginatorModule } from 'primeng/paginator';
import { DropdownModule } from 'primeng/dropdown';
import { CKEditorModule } from 'ckeditor4-angular';

import { AdminRoutingModule } from './admin-routing.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { UserManagementComponent } from './user-management/user-management.component';
import { EditUserComponent } from './user-management/edit-user/edit-user.component';
import { AdminManagementComponent } from './admin-management/admin-management.component';
import { EditAdminComponent } from './admin-management/edit-admin/edit-admin.component';
import { AddAdminComponent } from './admin-management/add-admin/add-admin.component';
import { EmailTemplatesComponent } from './email-templates/email-templates.component';
import { ViewEmailTemplateComponent } from './email-templates/view-email-template/view-email-template.component';
import { EditEmailTemplateComponent } from './email-templates/edit-email-template/edit-email-template.component';
import { CmsComponent } from './cms/cms.component';
import { EditCmsComponent } from './cms/edit-cms/edit-cms.component';
import { ApiManagementComponent } from './api-management/api-management.component';
import { AddApiComponent } from './api-management/add-api/add-api.component';
import { AddApiKeysComponent } from './api-management/add-api-keys/add-api-keys.component';
import { ViewApiKeysComponent } from './api-management/view-api-keys/view-api-keys.component';
import { ProfileComponent } from './profile/profile.component';
import { EditApiComponent } from './api-management/edit-api/edit-api.component';
import { PaginatorComponent } from '../../shared/paginator/paginator.component';

@NgModule({
    declarations: [
        DashboardComponent,
        UserManagementComponent,
        EditUserComponent,
        AdminManagementComponent,
        EditAdminComponent,
        AddAdminComponent,
        EmailTemplatesComponent,
        ViewEmailTemplateComponent,
        EditEmailTemplateComponent,
        CmsComponent,
        EditCmsComponent,
        ApiManagementComponent,
        AddApiComponent,
        AddApiKeysComponent,
        ViewApiKeysComponent,
        ProfileComponent,
        EditApiComponent,
        PaginatorComponent
    ],
    imports: [
        CommonModule,
        AdminRoutingModule,
        FormsModule,
        PaginatorModule,
        ReactiveFormsModule,
        ConfirmDialogModule,
        CKEditorModule,
        ToastModule,
        DataTableModule,
        DropdownModule
    ],
    providers: [
        ConfirmationService,
        MessageService
    ]
})
export class AdminModule { }
