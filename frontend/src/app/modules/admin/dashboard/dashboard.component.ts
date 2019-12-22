import { Component, OnInit, Input } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

import { AdminService } from '../../../services/admin.service';
import { OtherService } from '../../../services/other.service';

@Component({
    selector: 'app-dashboard',
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

    totalUsers: any;
    activeUsers: any;
    totalAdmins: any;
    activeAdmins: any;
    @Input() routes: any = [];


    constructor(
        private toastr: ToastrService,
        private adminService: AdminService,
        private otherService: OtherService
    ) { }

    ngOnInit() {
        this.getUserCount();
        this.getAdminCount();
    }

    getUserCount() {
        this.adminService.getUserCount(2).subscribe(result => {
            if (result['status'] === 'success') {
                this.totalUsers = result['data']['userCount'];
                this.activeUsers = result['data']['active'];
                console.log
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    getAdminCount() {
        this.adminService.getUserCount(1).subscribe(result => {
            if (result['status'] === 'success') {
                this.totalAdmins = result['data']['userCount'];
                this.activeAdmins = result['data']['active'];
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

}
