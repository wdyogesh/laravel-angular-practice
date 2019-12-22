import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})

export class ValidatorList {
    static accountValidationMessages: any = {
        email: [
            { type: 'required', message: 'This field is required' },
            { type: 'emailValidator', message: 'Enter a valid email' },
        ],
        password_confirmation: [
            { type: 'required', message: 'This field is required' },
            { type: 'areEqual', message: 'Password mismatch' },
            { type: 'pattern', message: 'Confirm password must be alpha-numeric'}
        ],
        password: [
            { type: 'required', message: 'This field is required' },
            { type: 'minlength', message: 'Password must be at least 6 characters long' },
            { type: 'pattern', message: 'Password must be alpha-numeric' }
        ],
        old_password: [
            { type: 'required', message: 'This field is required' },
            { type: 'minlength', message: 'Old Password must be at least 6 characters long' },
            { type: 'pattern', message: 'Old password must be alpha-numeric' }
        ],
        first_name: [
            { type: 'required', message: 'This field is required' },
            { type: 'pattern', message: 'First Name should contain alphabets only' },
            { type: 'numberNotRequiredValidator', message: 'First Name should not contain numbers.' },
            { type: 'avoidEmptyStrigs', message: 'First Name should not be empty string.' },
        ],
        last_name: [
            { type: 'required', message: 'This field is required' },
            { type: 'pattern', message: 'Last Name should contain alphabets only' },
            { type: 'numberNotRequiredValidator', message: 'Last Name should not contain numbers.' },
            { type: 'avoidEmptyStrigs', message: 'Last Name should not be empty string.' },
        ],
        mobile_code: [
            { type: 'required', message: 'This field is required' },
        ],
        mobile: [
            { type: 'required', message: 'This field is required' },
            { type: 'pattern', message: 'Mobile number should contain numbers only' },
            { type: 'minlength', message: 'Minimum length is 7' },
            { type: 'maxlength', message: 'Maximum length is 15' },
        ],
        display_title: [
            { type: 'required', message: 'This field is required' },
            { type: 'pattern', message: 'Title should only contiain alphabets and numbers' },
        ],
        title: [
            { type: 'required', message: 'This field is required' },
            { type: 'pattern', message: 'Title should only contiain alphabets and numbers' },
        ],
        key_title: [
            { type: 'required', message: 'This field is required' }
        ],
        key_value: [
            { type: 'required', message: 'This field is required' }
        ],
        content: [
            { type: 'required', message: 'This field is required' }
        ],
        recaptcha : [
            { type: 'required', message: 'Please solve reCaptcha.' }
        ]
    };


    static numberNotRequiredValidator(num): any {
        if (num.pristine) {
            return null;
        }
        const NUMBER_REGEXP = /^-?[\d.]+(?:e-?\d+)?$/;
        // const NUMBER_REGEXP = /[A-Za-z]+/;

        num.markAsTouched();

        const value = num.value.trim();

        if (NUMBER_REGEXP.test(value)) {
            return {
                numberNotRequiredValidator: true
            };
        }

        return null;
    }

    static percentValidation(num):any{
        if (num.pristine) {
            return null;
        }
        num.markAsTouched();

        const tempNumber = parseInt(num.value);

        if ((tempNumber > 100) || (tempNumber < 0)) {
            return {
                percentValidation: true
            };
        } else {
            return null;
        }
    }

    static avoidEmptyStrigs(value): any {
        if (value.pristine) {
            return null;
        }

        value.markAsTouched();

        if (value.value.trim() === '' && value.value.length > 0) {
            return {
                avoidEmptyStrigs: true
            };
        }

        return null;
    }

    // static pricePattern(value): any
    // {
    // 	if (value.pristine) {
    //      	return null;
    //    	}

    //    	if (value.value.length == 0) {
    //    		return;
    //    	}


    // 	const PRICE_REGEXP = /^(\d*\.)?\d+$/;

    //    	value.markAsTouched();

    //    	if (PRICE_REGEXP.test(value.value)) {
    //    		return null;
    //    	}

    //    return {
    //       pricePattern: true
    //    };
    // }

    static emailValidator(value): any {

        if (value.pristine) {
            return null;
        }

        if (value.value.length === 0) {
            return;
        }


        const EMAIL_REGEXP = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        value.markAsTouched();

        if (EMAIL_REGEXP.test(value.value)) {
            return null;
        }

        return {
            emailValidator: true
        };
    }

}
