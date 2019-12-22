import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

import { OtherService } from '../../../services/other.service';
import { AdminService } from '../../../services/admin.service';

@Component({
    selector: 'app-cms',
    templateUrl: './cms.component.html',
    styleUrls: ['./cms.component.scss']
})
export class CmsComponent implements OnInit {

    public cmsList: any = [];

    constructor(
        private adminService: AdminService,
        private otherService: OtherService,
        private toastr: ToastrService,
    ) { }

    ngOnInit() {
        this.getCMSContent();
    }

    getCMSContent() {
        this.adminService.getCMSContent().subscribe(result => {

            if (result['status'] === 'success') {
                this.cmsList = result['data'];
            } else {
                this.toastr.error(result['message']);
            }

        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

}
