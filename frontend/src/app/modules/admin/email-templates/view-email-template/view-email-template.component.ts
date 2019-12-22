import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { AdminService } from 'src/app/services/admin.service';
import { OtherService } from 'src/app/services/other.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
    selector: 'app-view-email-template',
    templateUrl: './view-email-template.component.html',
    styleUrls: ['./view-email-template.component.scss']
})
export class ViewEmailTemplateComponent implements OnInit {

    public emailTemplate: any = [];
    private emailTemplateTitle;

    constructor(
        private router: Router,
        private adminService: AdminService,
        private otherService: OtherService,
        private activatedRoute: ActivatedRoute,
        private toastr: ToastrService
    ) {
        this.activatedRoute.params.subscribe(data => {
            this.emailTemplateTitle = data.title;
        });
    }

    ngOnInit() {
        this.getEmailTemplateData();
    }

    getEmailTemplateData() {
        console.log(this.emailTemplateTitle);

        this.adminService.getEmailTemplateData(this.emailTemplateTitle).subscribe(result => {
            if (result['status'] == 'success') {
                if (!result['data']) {

                    this.router.navigate(['/admin/email-templates']).then(() => {
                        this.toastr.error('Requested data not found');
                    });
                    return;
                } else {
                    this.emailTemplate = result['data'];
                }
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

}
