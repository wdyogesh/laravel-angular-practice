import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { ConfirmationService } from 'primeng/api';

import { environment } from '../../../../environments/environment';

import { AdminService } from '../../../services/admin.service';
import { OtherService } from '../../../services/other.service';


@Component({
    selector: 'app-api-management',
    templateUrl: './api-management.component.html',
    styleUrls: ['./api-management.component.scss']
})
export class ApiManagementComponent implements OnInit {

    apiMetaData: any;
    totalRecords: any;
    search = {
        first : 0,
        page : 0,
        rows : environment.pagination_rows,
        by_text : '',
        by_status : '',
    };

    constructor(
        private toastr: ToastrService,
        private otherService: OtherService,
        private adminService: AdminService,
        private confirmationService: ConfirmationService,
    ) { }

    ngOnInit() {
        this.getApiData();
    }

    getApiData() {

        this.adminService.getKeysMeta().subscribe((result) => {

            if (result['status'] == 'success') {
                this.apiMetaData = result['data'];
            } else {
                this.toastr.error(result['message']);
            }

        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    confirmDeleteApi(id) {
        this.confirmationService.confirm({
            message: 'Are you sure that you want to delete this Api?',
            accept: () => {
                this.deleteApi(id);
            }
        });
    }

    deleteApi(id) {
        this.adminService.deleteApi(id)
        .subscribe( result => {
            if (result['status'] == 'success') {
                this.getApiData();
                this.toastr.success(result['message']);
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

}
