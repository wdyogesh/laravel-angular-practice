import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';

import { ValidatorList } from '../../../../services/validator.service';
import { AdminService } from '../../../../services/admin.service';
import { OtherService } from '../../../../services/other.service';

@Component({
    selector: 'app-add-api-keys',
    templateUrl: './add-api-keys.component.html',
    styleUrls: ['./add-api-keys.component.scss']
})
export class AddApiKeysComponent implements OnInit {

    apiKeyForm: FormGroup;
    keyMetaId: any;
    validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private router: Router,
        private toastr: ToastrService,
        private adminService: AdminService,
        private otherService: OtherService,
        private activatedRoute: ActivatedRoute,
        private fb: FormBuilder
    ) {
        this.activatedRoute.params.subscribe(result => {
            this.keyMetaId = result.id;
        });
    }

    ngOnInit() {
        this.buildKeyForm();
    }

    buildKeyForm() {
        this.apiKeyForm = this.fb.group({
            id: this.keyMetaId,
            keys: this.fb.array([this.createItem()])
        });
    }

    createItem() {
        return this.fb.group({
            key_title: ['', [Validators.required]],
            key_value: ['', [Validators.required]]
        });
    }

    getKey(form) {
        return form.get('keys').controls as FormArray;
    }

    addNext() {
        (this.apiKeyForm.get('keys') as FormArray).push(this.createItem());
    }

    remove(i: number) {
        (this.apiKeyForm.get('keys') as FormArray).removeAt(i);
    }

    onSubmit(value) {
        if (this.apiKeyForm.invalid) {
            this.validateAllFormFields(this.apiKeyForm);
            return ;
        } else {
            this.adminService.addApiKeys(this.apiKeyForm.value).subscribe((result) => {
                if (result['status'] === 'success') {
                    this.router.navigate(['/admin/api-management']).then(() => {
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

    validateAllFormFields(formGroup: FormGroup) {
        (this.apiKeyForm.get('keys') as FormArray).markAllAsTouched();
    }

}
