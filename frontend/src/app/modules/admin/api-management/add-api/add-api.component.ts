import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';
import { ValidatorList } from '../../../../services/validator.service';

@Component({
    selector: 'app-add-api',
    templateUrl: './add-api.component.html',
    styleUrls: ['./add-api.component.scss']
})
export class AddApiComponent implements OnInit {

    addApiForm: FormGroup;
    validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private router: Router,
        private fb: FormBuilder,
        private otherService: OtherService,
        private toastr: ToastrService,
        private adminService: AdminService
    ) { }

    ngOnInit() {
        this.createAddApiForm();
    }

    createAddApiForm() {

        this.addApiForm = this.fb.group({
            title: ['', [Validators.required]],
        });

    }

    onSubmitForm(value) {

        if (this.addApiForm.invalid) {

            this.validateAllFormFields(this.addApiForm);
            return ;

        } else {

            this.adminService.addApiMeta(value).subscribe((result) => {

                if (result['status'] === 'success') {

                    this.router.navigate(['/admin/api-management']).then(() => {
                        this.toastr.success('Added new Api Successfully');
                    });

                } else {
                    this.toastr.error(result['message']);
                }

            }, (error) => {

                this.otherService.unAuthorizedUserAccess(error);

            });
        }
    }

    validateAllFormFields(formGroup: FormGroup) {
        Object.keys(this.addApiForm.controls).forEach(field => {
            const control = this.addApiForm.get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

}
