import { Component, OnInit } from '@angular/core';
import { ConfirmationService } from 'primeng/api';
import { ToastrService } from 'ngx-toastr';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ViewChild, ElementRef } from '@angular/core';
import { environment } from '../../../../environments/environment';

/*   Services   */
import { OtherService } from '../../../services/other.service';
import { ValidatorList } from '../../../services/validator.service';
import { AdminService } from '../../../services/admin.service';
import { AuthService } from '../../../services/auth.service';
import { ActivatedRoute } from '@angular/router';

@Component({
    selector: 'app-admin-management',
    templateUrl: './admin-management.component.html',
    styleUrls: ['./admin-management.component.scss']
})
export class AdminManagementComponent implements OnInit {

    adminData: any;
    image: any;
    adminDataLength: any;
    totalRecords: any;
    search = {
        first : 0,
        page : 0,
        rows : environment.pagination_rows,
        by_text : '',
        by_status : '',
    };
    changePasswordForm: FormGroup;
    validationMessages = ValidatorList.accountValidationMessages;
    @ViewChild('closeBtn', {static: false}) closeBtn: ElementRef;

    constructor(
        private fb: FormBuilder,
        private confirmationService: ConfirmationService,
        private authService: AuthService,
        private toastr: ToastrService,
        private adminService: AdminService,
        private otherService: OtherService,
        private activatedRoute: ActivatedRoute
    ) { }

    ngOnInit() {
        this.createChangePwdForm();
        this.getAdminData();
    }

    getAdminData() {
        this.search['role_id'] = 1;
        this.adminService.getAdminData(this.search).subscribe((result) => {
            if (result['status'] == 'success') {
                this.adminData = result['data'];
                this.adminDataLength = this.adminData.length;
                this.totalRecords = result['count'];
                this.image = result['image_path'];
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
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

        let formData = this.changePasswordForm.value

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
            id,
        });
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
        this.adminService.deleteUserPermanently(id).subscribe( result => {
            if (result['status'] == 'success') {
                this.getAdminData();
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

        this.getAdminData();
    }

    searchFun() {
        this.search.page = 0;
        this.search.first = 0;

        this.getAdminData();
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

    //     if (this.id_array.length == this.adminDataLength)
    //     {
    //         string = 'all';
    //     }

    //     this.ConfirmationService.confirm({
    //         message: 'Are you sure that you want to delete '+string+' users?',
    //         accept: () => {
    //             this.blocked = true
    //             this.AdminService.deleteMultipleUserPermanently({'ids' : this.id_array}).subscribe(result => {
    //                 this.id_array = [];
    //                 if (result['status'] == 'success') {
    //                     this.blocked = false
    //                     this.getAdminData();
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
    //     if (this.id_array.length != this.adminDataLength) {
    //         this.adminData.map(item => {
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
