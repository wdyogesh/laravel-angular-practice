import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'app-admin-sidebar',
    templateUrl: './sidebar.component.html',
    styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

    @Input() routes: any = [];

    constructor() { }

    ngOnInit() {
    }

    getId(route) {
        return route.route;
    }

}
