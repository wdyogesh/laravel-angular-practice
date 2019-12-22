import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { ConfirmationService } from 'primeng/api';

import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';

@Component({
    selector: 'app-view-api-keys',
    templateUrl: './view-api-keys.component.html',
    styleUrls: ['./view-api-keys.component.scss']
})
export class ViewApiKeysComponent implements OnInit {

    id: any;
    keysData: any = [];

    constructor(
        private toastr: ToastrService,
        private activatedRoute: ActivatedRoute,
        private adminService: AdminService,
        private otherService: OtherService,
        private confirmationService: ConfirmationService
    ) {
        this.activatedRoute.params.subscribe(result => {
            this.id = result.id;
        });
    }

    ngOnInit() {
        this.getKeys();
    }

    getKeys() {
        this.adminService.getApiKeys(this.id).subscribe(result => {
            if (result['status'] === 'success') {
                this.keysData = result['data'];
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    confirmDeleteKey(id) {
        this.confirmationService.confirm({
            message: 'Are you sure that you want to delete this key?',
            accept: () => {
                this.deleteKey(id);
            }
        });
    }

    deleteKey(id) {
        this.adminService.deleteApiKey(id)
        .subscribe( result => {
            if (result['status'] === 'success') {
                this.getKeys();
                this.toastr.success(result['message']);
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }
}
