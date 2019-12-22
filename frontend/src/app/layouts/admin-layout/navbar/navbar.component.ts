import { Component, OnInit } from '@angular/core';

import { OtherService } from '../../../services/other.service';

@Component({
    selector: 'app-admin-navbar',
    templateUrl: './navbar.component.html',
    styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit {

    image: any;
    userData: any;

    constructor(
        private otherService: OtherService
    ) {
        this.otherService.getUserData().subscribe((result) => {
            this.userData = result;
        });
    }

    ngOnInit() {
    }

    logout() {
        this.otherService.doLogout();
    }

}
