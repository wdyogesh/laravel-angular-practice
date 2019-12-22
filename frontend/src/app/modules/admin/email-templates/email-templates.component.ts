import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

/*   Services   */
import { OtherService } from '../../../services/other.service';
import { AdminService } from '../../../services/admin.service';

@Component({
    selector: 'app-email-templates',
    templateUrl: './email-templates.component.html',
    styleUrls: ['./email-templates.component.scss']
})
export class EmailTemplatesComponent implements OnInit {

    public emailTemplates: any = [];

    constructor(
        private adminService: AdminService,
        private otherService: OtherService,
        private toastr: ToastrService
    ) { }

    ngOnInit() {
        this.getEmailTemplates();
    }

    getEmailTemplates() {
        this.adminService.getEmailTemplates().subscribe(result => {
            if (result['status'] === 'success') {
                this.emailTemplates = result['data'];
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    makeTitle(title) {
        title = title.toLowerCase();
        return title.replace(/ /g, '_');
    }

}
