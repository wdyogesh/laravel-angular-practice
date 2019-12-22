import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

/*   Services   */
import { ValidatorList } from '../../../../services/validator.service';
import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';

@Component({
    selector: 'app-add-admin',
    templateUrl: './add-admin.component.html',
    styleUrls: ['./add-admin.component.scss']
})
export class AddAdminComponent implements OnInit {

    phoneCodeList: any = [];
    addAdminForm: FormGroup;
    public validationMessages = ValidatorList.accountValidationMessages;


    constructor(
        private toastr: ToastrService,
        private adminService: AdminService,
        private otherService: OtherService,
        private router: Router,
        private fb: FormBuilder,
    ) { }

    ngOnInit() {
        this.getPhoneCodes();
        this.createAddAdminForm();
    }

    onSubmitForm() {

        if (this.addAdminForm.invalid) {

            this.validateAllFormFields(this.addAdminForm);
            return ;

        } else {

            const updateDataObject = {
                first_name: this.addAdminForm.value.first_name,
                last_name: this.addAdminForm.value.last_name,
                email: this.addAdminForm.value.email,
                mobile_code: this.addAdminForm.value.mobile_code,
                mobile: this.addAdminForm.value.mobile,
                password: this.addAdminForm.value.password,
                password_confirmation: this.addAdminForm.value.password_confirmation,
            };

            this.adminService.addAdmin(updateDataObject).subscribe((result) => {

                if (result['status'] === 'success') {
                    this.router.navigate(['/admin/admin-management']).then(() => {
                        this.toastr.success('Admin created successfully');
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
        Object.keys(this.addAdminForm.controls).forEach(field => {
            const control = this.addAdminForm.get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

    createAddAdminForm() {

        this.addAdminForm = this.fb.group({
            first_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            last_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            email: ['', [Validators.required, ValidatorList.emailValidator]],
            mobile_code: ['', [Validators.required]],
            mobile: ['', [Validators.required, Validators.minLength(7), Validators.maxLength(15), Validators.pattern('^[0-9]*$')
            ]],
            password: ['', [Validators.required, Validators.minLength(6),
                Validators.pattern('^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{6,}$')
            ]],
            password_confirmation: ['', [Validators.required]],
        });
    }

    getPhoneCodes() {
        try {

            this.otherService.getPhoneCodes().subscribe(res => {

                if (res['status'] == 'success') {
                    this.phoneCodeList = res['data'];
                } else {
                    this.toastr.error(res['message']);
                }

            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });

        } catch (err) {
            this.toastr.error(err);
        }
    }

}
