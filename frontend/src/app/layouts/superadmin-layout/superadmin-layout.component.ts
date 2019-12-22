import { Component, OnInit } from '@angular/core';

@Component({
    selector: 'app-superadmin-layout',
    template: `
    <div class="container-scroller admin">
        <app-superadmin-navbar></app-superadmin-navbar>
        <div class="container-fluid page-body-wrapper">
            <app-superadmin-sidebar></app-superadmin-sidebar>
            <div class="main-panel">
                <router-outlet></router-outlet>
                <app-superadmin-footer></app-superadmin-footer>
            </div>
        </div>
    </div>
    `,
    styles: []
})

export class SuperAdminLayoutComponent implements OnInit {

    constructor() { }

    ngOnInit() {
    }

}


