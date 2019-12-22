import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';
import { ValidatorList } from '../../../../services/validator.service';

@Component({
    selector: 'app-edit-api',
    templateUrl: './edit-api.component.html',
    styleUrls: ['./edit-api.component.scss']
})
export class EditApiComponent implements OnInit {

    editApiForm: FormGroup;
    id: any;
    apiData: any;
    validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private fb: FormBuilder,
        private router: Router,
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
        this.getApiData(this.id);
        this.createEditForm();
    }

    getApiData(id) {
        this.adminService.getApiDetails(id).subscribe(result => {
            if (result['status'] === 'success') {
                this.apiData = result['data'];
                this.editApiForm.patchValue({
                    id : this.apiData.id,
                    title : this.apiData.title
                });
            } else {
                this.toastr.error(result['message']);
            }
        }, (error) => {
            this.otherService.unAuthorizedUserAccess(error);
        });
    }

    createEditForm() {
        this.editApiForm = this.fb.group({
            id: [this.id],
            title: ['', [Validators.required]]
        });
    }

    onSubmitForm(value) {

        if (this.editApiForm.invalid) {

            this.validateAllFormFields(this.editApiForm);
            return ;

        } else {

            this.adminService.editApi(value).subscribe((result) => {

                if (result['status'] === 'success') {

                    this.router.navigate(['/admin/api-management']).then(() => {
                        this.toastr.success('Edited Api Successfully');
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
        console.log(formGroup);
        Object.keys(this.editApiForm.controls).forEach(field => {
            const control = this.editApiForm.get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }



}
