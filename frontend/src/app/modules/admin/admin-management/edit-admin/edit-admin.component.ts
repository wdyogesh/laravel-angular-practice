import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';

import { AdminService } from '../../../../services/admin.service';
import { ValidatorList } from '../../../../services/validator.service';
import { OtherService } from '../../../../services/other.service';

@Component({
    selector: 'app-edit-admin',
    templateUrl: './edit-admin.component.html',
    styleUrls: ['./edit-admin.component.scss']
})
export class EditAdminComponent implements OnInit {

    id: string;
    image: string;
    adminData: any;
    fileError: string;
    phoneCodeList: any;
    editAdminForm: FormGroup;
    @ViewChild('myfile', {static: false}) myInputVariable: ElementRef;
    public validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private toastr: ToastrService,
        private router: Router,
        private fb: FormBuilder,
        private activatedRoute: ActivatedRoute,
        private adminService: AdminService,
        private otherService: OtherService
    ) {
        this.activatedRoute.params.subscribe(result => {
            this.id = result.id;
        });
    }

    ngOnInit() {
        this.getPhoneCodes();
        this.createEditAdminForm();
        this.getAdminDetails();
    }

    createEditAdminForm() {
        this.editAdminForm = this.fb.group({
            first_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            last_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            email: ['', [Validators.required, ValidatorList.emailValidator]],
            // ip_address: ['', [Validators.required]],
            mobile_code: ['', [Validators.required]],
            mobile: ['', [Validators.required, Validators.minLength(7), Validators.maxLength(15), Validators.pattern('^[0-9]*$')]],
        });
    }

    getAdminDetails() {

        this.adminService.getUserDetails(this.id).subscribe((result) => {

            if (result['status'] === 'success') {

                if (!result['data']) {
                    this.router.navigate(['/admin/admin-management']);
                    this.toastr.warning('Something went wrong.');
                    return;
                }

                this.adminData = result['data'];

                this.editAdminForm.patchValue({
                    first_name: this.adminData.first_name,
                    last_name: this.adminData.last_name,
                    email: this.adminData.email,
                    // ip_address:this.userData.ip_address,
                    mobile_code: this.adminData.mobile_code.id,
                    mobile: this.adminData.mobile,
                });

                if (this.adminData['image']) {
                    this.image = result['image_path'] + '/' + result['data']['image'];
                } else {
                    this.image = 'assets/images/placeholder.jpg';
                }

            } else {
                this.toastr.error(result['message']);
            }

        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    validateAllFormFields(formGroup: FormGroup) {
        Object.keys(this.editAdminForm.controls).forEach(field => {
            const control = this.editAdminForm.get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

    onSubmitForm() {

        if (this.editAdminForm.invalid) {

            this.validateAllFormFields(this.editAdminForm);
            return;

        } else {

            let updateDataObject = {
                id: this.id,
                first_name: this.editAdminForm.value.first_name,
                last_name: this.editAdminForm.value.last_name,
                mobile_code: this.editAdminForm.value.mobile_code,
                // ip_address:this.editUserForm.value.ip_address,
                mobile: this.editAdminForm.value.mobile,
                email: this.editAdminForm.value.email,
            };

            this.adminService.updateUserData(updateDataObject).subscribe((result) => {

                if (result['status'] === 'success') {
                    this.router.navigate(['/admin/admin-management']).then(() => {
                        this.toastr.success(result['message']);
                    });
                } else {
                    this.toastr.error(result['message']);
                }

            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
                this.toastr.error(error);
            });
        }

    }

    handleFileInput(file) {
        try {

            this.fileError = '';
            const uploadFile = file[0];
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG'];
            const fileExtension = uploadFile.name.split('.').pop();

            if (allowedExtensions.indexOf(fileExtension) > -1) {

                let formData = new FormData();
                formData.append('id', this.id);
                formData.append('file', uploadFile);
                this.adminService.doUpdateProfiePicture(formData).subscribe(res => {

                    if (res['status'] === 'success') {
                        this.toastr.success(res['message']);
                        this.image = res['data']['picture_path'] + '/' + res['data']['image'];
                        this.myInputVariable.nativeElement.value = '';
                    } else {
                        this.toastr.error(res['message']);
                        this.myInputVariable.nativeElement.value = '';
                    }

                }, (error) => {
                    this.otherService.unAuthorizedUserAccess(error);
                });

            } else {
                throw new Error(('Invalid file format, please upload valid image. Supported format are jpg,jpeg,png,JPG,JPEG'));
            }

        } catch (err) {
            this.fileError = err;
            this.myInputVariable.nativeElement.value = '';
        }
    }

    public getPhoneCodes() {
        try {
            this.otherService.getPhoneCodes().subscribe(res => {

                if (res['status'] === 'success') {
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
