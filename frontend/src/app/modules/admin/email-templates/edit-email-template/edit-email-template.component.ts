import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';
import { ValidatorList } from '../../../../services/validator.service';

@Component({
    selector: 'app-edit-email-template',
    templateUrl: './edit-email-template.component.html',
    styleUrls: ['./edit-email-template.component.scss']
})
export class EditEmailTemplateComponent implements OnInit {

    private emailTemplateId;
    private emailTemplateTitle;
    public validationMessages = ValidatorList.accountValidationMessages;
    public editTemplateForm: FormGroup;
    public emailTemplate;

    constructor(
        private router: Router,
        private fb: FormBuilder,
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
        this.createEditEmailTemplateForm();
    }

    createEditEmailTemplateForm() {
        this.editTemplateForm = this.fb.group({
            id: [this.emailTemplateId, [Validators.required]],
            template: ['', [Validators.required, ValidatorList.avoidEmptyStrigs]],
            subject: ['', [Validators.required, ValidatorList.avoidEmptyStrigs]],
        });

    }

    getEmailTemplateData() {
        this.adminService.getEmailTemplateData(this.emailTemplateTitle).subscribe(result => {
            if (result['status'] == 'success') {
                if (!result['data']) {
                    this.router.navigate(['/admin/email-templates']).then(() => {
                        this.toastr.error('Requested information not available');
                    });
                    return;

                } else {
                    this.emailTemplate = result['data'];
                    this.emailTemplateId = this.emailTemplate.id;

                    this.editTemplateForm.patchValue({
                        id: this.emailTemplateId,
                        subject : this.emailTemplate.subject,
                        template : this.emailTemplate.template,
                    });
                }
            } else {
                this.toastr.error(result['message']);

            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    onSubmitForm(params) {

        this.editTemplateForm.controls.template.setValue(this.editTemplateForm.value.template);

        if (this.editTemplateForm.valid) {
            this.adminService.updateEmailTemplate(this.editTemplateForm.value).subscribe(result => {
                if (result['status'] == 'success') {
                    this.router.navigate(['admin/email-templates']).then(() => {
                        this.toastr.success(result['message']);
                    });
                } else {
                    this.toastr.error(result['message']);
                }
            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });
        } else {
            this.validateAllFormFields(this.editTemplateForm);
            return;
        }
    }

    validateAllFormFields(formGroup: FormGroup) {
        Object.keys(this.editTemplateForm.controls).forEach(field => {
            const control = this.editTemplateForm.get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

}
