import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ValidatorList } from 'src/app/services/validator.service';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { OtherService } from 'src/app/services/other.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
    selector: 'app-signup',
    templateUrl: './signup.component.html',
    styleUrls: ['./signup.component.scss']
})
export class SignupComponent implements OnInit {

    signupForm: FormGroup;
    phoneCodeList: any;
    public validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private fb: FormBuilder,
        private router: Router,
        private toastr: ToastrService,
        private otherService: OtherService,
        private authService: AuthService
    ) { }

    ngOnInit() {
        this.getPhoneCodes();
        this.createSignupForm();
    }

    createSignupForm() {
        this.signupForm = this.fb.group({

            first_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            last_name: ['', [Validators.required, ValidatorList.numberNotRequiredValidator, ValidatorList.avoidEmptyStrigs]],
            email: ['', [Validators.required, ValidatorList.emailValidator]],
            // ip_address: ['', [Validators.required]],
            mobile_code: ['', [Validators.required]],
            mobile: ['', [Validators.required, Validators.minLength(7), Validators.maxLength(15), Validators.pattern('^[0-9]*$')]],
            password: ['', [Validators.required, Validators.minLength(6),
                Validators.pattern('^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{6,}$')]],
            password_confirmation: ['', [Validators.required]]

        });
    }

    public getPhoneCodes() {
        try {
            this.otherService.getPhoneCodes().subscribe(result => {

                if (result['status'] === 'success') {
                    this.phoneCodeList = result['data'];
                } else {
                    this.toastr.error(result['message']);
                }

            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });

        } catch (err) {
            this.toastr.error(err);
        }
    }

    onSignupSubmit(values) {
        if (this.signupForm.valid) {
            this.authService.doSignup(values).subscribe(result => {

                if (result['status'] === 'success') {
                    this.router.navigate(['/login']).then(() => {
                        this.toastr.success(result['message']);
                    });
                } else {
                    this.toastr.error(result['message']);
                }

            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });
        } else {
            this.validateFields('signupForm');
        }
    }

    validateFields(formGroup) {
        Object.keys(this[formGroup].controls).forEach(field => {
            const control = this[formGroup].get(field);
            control.markAsTouched({ onlySelf: true });
            control.markAsDirty({ onlySelf: true });
        });
    }

}
