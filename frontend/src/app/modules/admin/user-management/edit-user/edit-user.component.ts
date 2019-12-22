import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';

import { AdminService } from '../../../../services/admin.service';
import { ValidatorList } from '../../../../services/validator.service';
import { OtherService } from '../../../../services/other.service';

@Component({
  selector: 'app-edit-user',
  templateUrl: './edit-user.component.html',
  styleUrls: ['./edit-user.component.scss']
})
export class EditUserComponent implements OnInit {

    id: string;
    image: string;
    userData: any;
    fileError: string;
    phoneCodeList: any;
    editUserForm: FormGroup;
    @ViewChild('myfile', {static: false}) myInputVariable: ElementRef;
    public validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private router: Router,
        private fb: FormBuilder,
        private toastr: ToastrService,
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
        this.createEditUserForm();
        this.getUserDetails();
    }

    createEditUserForm() {
        this.editUserForm = this.fb.group({
            first_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            last_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            email: ['', [Validators.required, ValidatorList.emailValidator]],
            // ip_address: ['', [Validators.required]],
            mobile_code: ['', [Validators.required]],
            mobile: ['', [Validators.required, Validators.minLength(7), Validators.maxLength(15), Validators.pattern('^[0-9]*$')]],
        });
    }

    getUserDetails() {
        this.adminService.getUserDetails(this.id).subscribe((result) => {
            if (result['status'] == 'success') {
                if (!result['data']) {
                    this.router.navigate(['/admin/user-management']).then(() => {
                        this.toastr.error('Could not find user');
                    });
                    return;
                }
                this.userData = result['data'];
                this.editUserForm.patchValue({
                    first_name: this.userData.first_name,
                    last_name: this.userData.last_name,
                    email: this.userData.email,
                    // ip_address:this.userData.ip_address,
                    mobile_code: this.userData.mobile_code.id,
                    mobile: this.userData.mobile,
                });

                if (this.userData['image']) {
                    this.image = result['image_path'] + '/' + result['data']['image'];
                } else {
                    this.image = 'assets/images/placeholder.jpg';
                }
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.toastr.error('Error occurred!');
        });
    }

    validateAllFormFields(formGroup: FormGroup) {
        Object.keys(this.editUserForm.controls).forEach(field => {
            const control = this.editUserForm.get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

    onSubmitForm() {
        if (this.editUserForm.invalid) {
            this.validateAllFormFields(this.editUserForm);
            return;
        } else {
            let updateDataObject = {
                id: this.id,
                first_name: this.editUserForm.value.first_name,
                last_name: this.editUserForm.value.last_name,
                mobile_code: this.editUserForm.value.mobile_code,
                // ip_address:this.editUserForm.value.ip_address,
                mobile: this.editUserForm.value.mobile,
                email: this.editUserForm.value.email,
            };
            this.adminService.updateUserData(updateDataObject).subscribe((result) => {
                if (result['status'] === 'success') {
                    this.router.navigate(['/admin/user-management']).then(() => {
                        this.toastr.success(result['message']);
                    });
                } else {
                    this.toastr.error(result['message']);
                }
            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });
        }
    }

    handleFileInput(file) {
        try {
            this.fileError = '';
            let uploadFile = file[0];
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG'];
            const fileExtension = uploadFile.name.split('.').pop();
            if (allowedExtensions.indexOf(fileExtension) > -1) {
                let formData = new FormData();
                formData.append('id', this.id);
                formData.append('file', uploadFile);
                this.adminService.doUpdateProfiePicture(formData).subscribe(result => {
                    if (result['status'] === 'success') {
                        this.toastr.success(result['message']);
                        this.image = result['data']['picture_path'] + '/' + result['data']['image'];
                        this.myInputVariable.nativeElement.value = '';
                    } else {
                        this.toastr.error(result['message']);
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
            this.otherService.getPhoneCodes().subscribe(result => {
                if (result['status'] === 'success') {
                    this.phoneCodeList = result['data'];
                } else {
                    this.toastr.success(result['message']);
                }
            }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
            });

        } catch (err) {
            this.toastr.success(err);
        }
    }

}
