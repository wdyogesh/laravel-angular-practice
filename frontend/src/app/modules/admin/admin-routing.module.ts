import { NgModule } from '@angular/core';
import { Routes, RouterModule, ActivatedRoute } from '@angular/router';
import { AuthenticationGuard } from '../../guard/authentication.guard';
import { RoutesGuard } from '../../guard/routes.guard';

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

const routes: Routes = [
    {
        path: '',
        redirectTo: 'dashboard',
    },
    {
        path: 'dashboard',
        component: DashboardComponent,
        canActivate: [AuthenticationGuard]
    },
    {
        path: 'profile',
        component: ProfileComponent,
        canActivate: [AuthenticationGuard, RoutesGuard]
    },
    {
        path: 'user-management',
        children: [
            {
                path: '',
                component: UserManagementComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'edit/:id',
                component: EditUserComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            }
        ]
    },
    {
        path: 'admin-management',
        children: [
            {
                path: '',
                component: AdminManagementComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'edit/:id',
                component: EditAdminComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'add-admin',
                component: AddAdminComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            }
        ]
    },
    {
        path: 'email-templates',
        children: [
            {
                path: '',
                component: EmailTemplatesComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'view/:title',
                component: ViewEmailTemplateComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'edit/:title',
                component: EditEmailTemplateComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            }
        ]
    },
    {
        path: 'cms',
        children: [
            {
                path: '',
                component: CmsComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'edit/:id',
                component: EditCmsComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            }
        ]
    },
    {
        path: 'api-management',
        children: [
            {
                path: '',
                component: ApiManagementComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'add-api',
                component: AddApiComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'edit-api/:id',
                component: EditApiComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'add-api-keys/:id',
                component: AddApiKeysComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            },
            {
                path: 'view-api-keys/:id',
                component: ViewApiKeysComponent,
                canActivate: [AuthenticationGuard, RoutesGuard]
            }
        ]
    },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule { }
