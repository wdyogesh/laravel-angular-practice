import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { FormGroup, FormBuilder, FormControl, Validators } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';

/*   Services   */
import { ValidatorList } from '../../../services/validator.service';
import { OtherService } from '../../../services/other.service';
import { AuthService } from '../../../services/auth.service';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

    loginForm: FormGroup;
    public validationMessages = ValidatorList.accountValidationMessages;

    constructor(
        private fb: FormBuilder,
        private router: Router,
        private toastr: ToastrService,
        private otherService: OtherService,
        private authService: AuthService
    ) { }

    ngOnInit() {
        this.createLoginForm();
    }

    createLoginForm() {
        this.loginForm = this.fb.group({
            email: ['', [Validators.required, ValidatorList.emailValidator]],
            password: ['', [Validators.required]],
        });
    }

    onLoginSubmit(values) {
        if (this.loginForm.valid) {
            this.authService.doLogin(values).subscribe(result => {
                if (result['status'] === 'success' && !result['authorization_token']['error']) {
                    result['authData']['last_access_time'] = new Date().getTime();
                    localStorage.setItem('authData',JSON.stringify(result['authData']));
                    localStorage.setItem('authToken', JSON.stringify(result['authorization_token']));
                    this.otherService.setUserData(result['authData']);
                    this.toastr.success('Login Successful!');

                    switch (result['authData']['role_id']) {
                        case 1:
                            this.router.navigate(['/admin']);
                            break;
                        case 2:
                            this.router.navigate(['/user']);
                            break;
                        case 3:
                            this.router.navigate(['/superadmin']);
                            break;
                        default:
                        break;
                    }
                } else if (result['status'] === 'error') {
                    this.toastr.error(result['message']);
                } else if (result['authorization_token']['error']) {
                    this.toastr.error(result['authorization_token']['error_description']);
                } else {
                    this.toastr.error('Error');
                }
            }, (error) => {
                this.otherService.unAuthorizedUserAccess(error);
            });
        } else {
            this.validateFields('loginForm');
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
