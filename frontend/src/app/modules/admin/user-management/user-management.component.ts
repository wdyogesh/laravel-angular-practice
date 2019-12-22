import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ConfirmationService } from 'primeng/api';
import { ViewChild, ElementRef } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

import { environment } from '../../../../environments/environment';
import { AdminService } from '../../../services/admin.service';
import { AuthService } from '../../../services/auth.service';
import { ValidatorList } from '../../../services/validator.service';
import { OtherService } from '../../../services/other.service';

@Component({
    selector: 'app-user-management',
    templateUrl: './user-management.component.html',
    styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {

    userData: any;
    image: string;
    changePasswordForm: FormGroup;
    @ViewChild('closeBtn', {static: false}) closeBtn: ElementRef;
    public validationMessages = ValidatorList.accountValidationMessages;
    search = {
        first : 0,
        page : 0,
        rows : environment.pagination_rows,
        by_text : '',
        by_status : '',
    };
    userDataLength: number;
    totalRecords: any;


    constructor(
        private fb: FormBuilder,
        private toastr: ToastrService,
        private adminService: AdminService,
        private authService: AuthService,
        private otherService: OtherService,
        private confirmationService: ConfirmationService
    ) {

    }

    ngOnInit() {
        this.createChangePwdForm();
        this.getAllUsers();
    }

    getAllUsers() {
        this.search['role_id'] = 2;
        this.adminService.getAllUsers(this.search).subscribe(result => {
            if (result['status'] === 'success') {
                this.userData = result['data'];
                this.image = result['image_path'];
                this.userDataLength = this.userData.length;
                this.totalRecords = result['count']
            } else {
                this.toastr.error(result['message']);
            }
        });
    }

    createChangePwdForm() {
        this.changePasswordForm = this.fb.group({
            id: ['', [Validators.required]],
            password: ['', [
                Validators.required,
                Validators.minLength(6),
                Validators.pattern('^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{6,}$')
            ]],
            password_confirmation: ['', [
                Validators.required,
                Validators.minLength(6),
                Validators.pattern('^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{6,}$')
            ]],
        });
    }

    validateAllFormFields(formGroup) {
        Object.keys(this[formGroup].controls).forEach(field => {
            const control = this[formGroup].get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

    doChangePassword() {
        if (this.changePasswordForm.invalid) {
            this.validateAllFormFields('changePasswordForm');
            return;
        }

        let formData = this.changePasswordForm.value;

        if (formData.password !== formData.password_confirmation) {
            return;
        }

        this.authService.changePasswordByAdmin(formData).subscribe(result => {
            if (result['status'] == 'success') {
                this.toastr.success(result['message']);
                this.closeBtn.nativeElement.click();
            } else {
                this.toastr.error(result['message']);
            }

        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    resetForm(form) {
        this[form].reset();
    }

    assignUserId(id) {
        this.changePasswordForm.patchValue({
            id
        });

        console.log('===', this.changePasswordForm.value);
    }


    activateDeactivateUserData(id, status) {
        try {
            let param = {
                id,
                status,
            };
            this.adminService.toggleUser(param).subscribe((result) => {
                if (result['status'] == 'success') {
                    this.toastr.success(result['message']);
                    this.ngOnInit();
                }
            },
            (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });
        } catch (error) {
            console.log('error ', error);
        }
    }

    confirmActivate(id) {
        this.confirmationService.confirm({
            message: 'Are you sure that you want to activate this user?',
            accept: () => {
                this.activateDeactivateUserData(id, '1');
            }
        });
    }

    confirmDeactivate(id) {
        this.confirmationService.confirm({
            message: 'Are you sure that you want to deactivate this user?',
            accept: () => {
                this.activateDeactivateUserData(id, '0');
            }
        });
    }

    confirmDeleteUser(id) {
        this.confirmationService.confirm({
            message: 'Are you sure that you want to delete this user Permanently?',
            accept: () => {
                this.deleteUserPermanently(id);
            }
        });
    }

    deleteUserPermanently(id) {
        this.adminService.deleteUserPermanently(id)
        .subscribe( result => {
            if (result['status'] == 'success') {
                this.getAllUsers();
                this.toastr.success(result['message']);
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    paginate(event) {

        this.search.rows = event.rows;
        this.search.page = event.page;
        this.search.first = event.first;

        this.getAllUsers();
    }

    searchFun() {
        this.search.page = 0;
        this.search.first = 0;

        this.getAllUsers();
    }

        // collectIdMultipleDelete(id) {
    //     if (this.id_array.indexOf(id) > -1) {
    //         let index = this.id_array.indexOf(id);
    //         this.id_array.splice(index, 1);
    //     } else {
    //         this.id_array.push(id);
    //     }
    // }

    // deleteMultiple() {
    //     if (this.id_array.length == 0)
    //     {
    //         this.ToastrService.warning('Please select subscriber first.');

    //         return;
    //     }

    //     let string = 'selected';

    //     if (this.id_array.length == this.userDataLength)
    //     {
    //         string = 'all';
    //     }

    //     this.ConfirmationService.confirm({
    //         message: 'Are you sure that you want to delete ' + string + ' users?',
    //         accept: () => {
    //             this.blocked = true
    //             this.AdminService.deleteMultipleUserPermanently({'ids' : this.id_array}).subscribe(result => {
    //                 this.id_array = [];
    //                 if (result['status'] == 'success') {
    //                     this.blocked = false
    //                     this.getUserData();
    //                     this.ToastrService.success(result['message']);
    //                 } else {
    //                     this.blocked = false
    //                     this.ToastrService.error(result['message'])
    //                 }
    //             },
    //             (error) => {
    //                 this.id_array = [];
    //                 this.blocked = false
    //                 this.OtherService.unAuthorizedUserAccess(error);
    //             });
    //         }
    //     });
    // }

    // toggle() {
    //     if (this.id_array.length != this.userDataLength) {
    //         this.userData.map(item => {
    //             if (this.id_array.indexOf(item.id) == -1)
    //             {
    //                 this.id_array.push(item.id);
    //             }
    //         });
    //     } else {
    //         this.id_array = [];
    //     }
    // }

}
