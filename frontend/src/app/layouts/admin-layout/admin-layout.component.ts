import { Component, OnInit } from '@angular/core';
import { ViewEncapsulation } from '@angular/core';

import { AdminService } from '../../services/admin.service';

@Component({
    selector: 'app-admin-layout',
    template: `
    <div class="container-scroller admin">
        <app-admin-navbar></app-admin-navbar>
        <div class="container-fluid page-body-wrapper">
            <app-admin-sidebar [routes]=modules></app-admin-sidebar>
            <div class="main-panel">
                <router-outlet></router-outlet>
                <app-admin-footer></app-admin-footer>
            </div>
        </div>
    </div>
    `,
    styles: []
})

export class AdminLayoutComponent implements OnInit {

    modules: any = [];

    constructor(
        private adminService: AdminService
    ) {
        this.adminService.getActiveModules().subscribe(result => {
            this.modules = result['data'];
            localStorage.setItem('modules', JSON.stringify(this.modules));
        });
    }

    ngOnInit() {
    }

    getData() {
        return this.modules;
    }

}


