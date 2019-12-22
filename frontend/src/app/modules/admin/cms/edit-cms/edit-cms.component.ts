import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';
import { ValidatorList } from '../../../../services/validator.service';

@Component({
    selector: 'app-edit-cms',
    templateUrl: './edit-cms.component.html',
    styleUrls: ['./edit-cms.component.scss']
})
export class EditCmsComponent implements OnInit {

    cmsId: any;
    public validationMessages = ValidatorList.accountValidationMessages;
    public editCmsForm: FormGroup;

    constructor(
        private router: Router,
        private fb: FormBuilder,
        private adminService: AdminService,
        private otherService: OtherService,
        private activatedRoute: ActivatedRoute,
        private toastr: ToastrService
    ) {
        this.activatedRoute.params.subscribe(data => {
            this.cmsId = data.id;
        });
    }

    ngOnInit() {
        this.createForm();
        this.getCMSDetail(this.cmsId);
        this.cmsId = this.activatedRoute.snapshot.paramMap.get('id');
    }

    createForm() {
        this.editCmsForm = this.fb.group({
            id: ['', [Validators.required]],
            display_title: ['', [Validators.required, Validators.pattern('^[a-zA-Z0-9 ]*$')]],
            content: ['', [Validators.required]],
        });
    }

    validateAllFormFields(formGroup) {
        Object.keys(this[formGroup].controls).forEach(field => {
            const control = this[formGroup].get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

    getCMSDetail(id) {

        this.adminService.getCMSDetail(id).subscribe(result => {

            if (result['status'] == 'success') {

                const data = result['data'];
                this.editCmsForm.patchValue({
                    id : data.id,
                    display_title : data.display_title,
                    content : data.content,
                });

            } else {
                this.toastr.error(result['message']);
            }

        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    onSubmitForm(data) {
        if (this.editCmsForm.valid) {
            if (data.content && data.content.includes('<html>')) {
                let value1 = data.content.split('<body>');
                data.content = value1[1].split('</body>')[0];
            }

            if (data.content && data.content.includes('<p>')) {
                let value1 = data.content.split('<p>');
                data.content = value1[1].split('</p>')[0];
            }

            this.adminService.updateCMSDetail(data).subscribe(result => {
                if (result['status'] === 'success') {

                    this.router.navigate(['/admin/cms']).then(() => {
                        this.toastr.success(result['message']);
                    });
                } else {
                    this.toastr.error(result['message']);
                }
            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });
        } else {
            this.validateAllFormFields('editCmsForm');
            return;
        }
    }
}
