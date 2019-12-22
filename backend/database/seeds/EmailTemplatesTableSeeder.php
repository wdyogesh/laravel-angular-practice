<?php

use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->insert([
            [
                'id' => 1,
                'title' => 'change_password',
                'subject' => 'XXXXX : Password changed successfully.',
                'template' => '<p>Dear {{$name}},</p>\n\n<p>Your password has been changed successfully.</p>',
                'tag_desc' => '[{"tag" : "{{$name}}", "desc" : "To display User Name"}]',
                'created_at' => '2018-09-25 07:30:00',
                'updated_at' =>'2019-03-14 21:27:27'
            ],
            [
                'id' => 2,
                'title' => 'reset_password',
                'subject' => 'XXXXX : Password Reset Link.',
                'template' => '<html>\n<head>\n	<title></title>\n</head>\n<body>\n<p>Dear User,</p>\n\n<p>Click on the below link to reset Password</p>\n\n<p><br />\n{{$url}}</p>\n</body>\n</html>',
                'tag_desc' => '[{"tag" : "{{$url}}","desc" : "To display password reset link"}]',
                'created_at' =>'2018-09-25 07:30:00',
                'updated_at' => '2019-03-10 15:56:43'
            ],
            [
                'id' => 4,
                'title' => 'new_registration',
                'subject' => 'XXXXX : Registered Successfully.',
                'template' => '<tr>\r\n    <td bgcolor="#ffffff" style=" font-size: 13px;font-family: \'Lato\', sans-serif; color: #777777;  padding: 10px 30px;">\r\n        <p>Dear {{$name}},</p>\r\n        <p>Thanks for registering an account to <span style="color: #2b9fe0;">XXXXXX</span>. </p>\r\n        <p style="padding: 10px 0;">To begin using UPS services, you will need to enter your User ID and Password*.</p>\r\n        <p>Below you will find more information about your user registration to XXXXXX.</p>\r\n    </td>\r\n</tr>\r\n\r\n<tr>\r\n<tr>\r\n    <td bgcolor="#262626" style="font-size: 15.24px;font-family: \'Montserrat\', sans-serif;color: #eaeaea;padding: 10px 30px;">\r\n        <p>Email: {{$email}}</p>\r\n        <p>Full Name: {{$name}}</p>\r\n    </td>\r\n</tr>\r\n<tr>\r\n    <td bgcolor="#ffffff" style=" font-size: 13px;font-family: \'Lato\', sans-serif;color: #777777;padding: 10px 30px;">\r\n        <p>* Click on <span style="color: #2b9fe0;">verify email buttom</span> below, to verify your email and activate acount</p>\r\n    </td>\r\n</tr>\r\n<tr>\r\n    <td align="center" bgcolor="#ffffff" style=" font-size: 13px;font-family: \'Lato\', sans-serif;color: #777777;padding: 10px 30px;">\r\n        {{$confirmation_link}}\r\n    </td>\r\n</tr>\r\n<tr>\r\n    <td bgcolor="#ffffff" style=" font-size: 13px;font-family: \'Lato\', sans-serif;color: #777777;padding: 10px 30px;">\r\n        <p>* Your User ID and Password are case sensitive. If you forget your password, visit <span style="color: #2b9fe0;">Forgot Password</span> on XXXXXXX.com</p>\r\n    </td>\r\n</tr>',
                'tag_desc' => '[{"tag" : "{{$name}}","desc" : "To display user full name"},{"tag" : "{{$user_name}}","desc" : "To display user name used for login purpose like : Email Id"}]',
                'created_at' => '2018-09-25 07:30:00',
                'updated_at' => '2018-09-25 07:30:00'
            ],
            [
                'id' => 8,
                'title' => 'subscriber_news_letters',
                'subject' => 'XXXXX : News Letters',
                'template' => '<tr>\r\n    <td bgcolor="#ffffff" style=" font-size: 13px;font-family: \'Lato\', sans-serif;color: #777777;padding: 10px 30px;    text-align: justify;">\r\n        <div style="padding-top: 10px;">Dear Subscriber,</div>\r\n        <p>You are subscribed with us by {{$email1}} Id.</p>\r\n        <p style="padding: 10px 0;">You are our subscribed user. And you will get benefit of it with regular updates.</p>\r\n        <p>Here are some latest product at our site:-</p>\r\n        <p>For any further information, you can reach us at:</p>\r\n        <div>Tel: {{$telephone1}}</div>\r\n        <div style="padding: 10px 0;">Tel: {{$telephone2}}</div>\r\n        <p style="    margin: 0;padding-bottom: 20px;">{{$email2}}</p>\r\n        <p>We will respond to your enquiries from 9:00am to 5:00pm.</p>\r\n        <p>Thank you once again for your order and best wishes.</p>\r\n    </td>\r\n</tr>\r\n<tr>\r\n    <td>\r\n        <table bgcolor="#ffffff" cellspacing="0" cellpadding="0" style="width: 100%;    padding: 10px 0;">\r\n            <thead>\r\n                <tr style="padding: 10px 30px; text-align: left; background: #eee;font-size: 15px; font-family: \'Montserrat\', sans-serif;">\r\n                    <th></th>\r\n                    <th>Product Title</th>\r\n                    <th style="padding: 10px 30px;">Price</th>\r\n                    <th>Action</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                {{$rowData}}\r\n            </tbody>\r\n            <tfoot>\r\n                <tr style="padding: 10px 30px; text-align: left; background: #eee;font-size: 15px; font-family: \'Montserrat\', sans-serif;">\r\n                    <td colspan="4" align="center">{{$unsubscribe_link}}</td>\r\n                </tr>\r\n            </tfoot>\r\n        </table>                                        \r\n    </td>\r\n</tr>',
                'tag_desc' => '[{"tag" : "{{$email1}}","desc" : "To display user email id"},{"tag" : "{{$telephone1}}","desc" : "To display portal contact number"},{"tag" : "{{$telephone2}}","desc" : "To display portal contact number"},{"tag" : "{{$email2}}","desc" : "To display portal contact Email Id"},{"tag" : "{{$rowData}}","desc" : "To display product list"},{"tag" : "{{$unsubscribe_link}}","desc" : "To display Unsuscribe link"}]',
                'created_at' => '2018-11-20 01:49:14',
                'updated_at' => '2018-11-19 07:30:00'
            ],
            [
                'id' => 10,
                'title' => 'newsletter_welcome',
                'subject' => 'XXXXX : Successfully subscribed to the newsletter service.',
                'template' => '<tr>\r\n    <td bgcolor="#ffffff" style=" font-size: 13px;font-family: \'Lato\', sans-serif;color: #777777;padding: 10px 30px;    text-align: justify;">\r\n        <div style="padding-top: 10px;">Dear User,</div>\r\n        <p>You are subscribed to our newsletter service.</p>\r\n        <p style="padding: 10px 0;">You will recieve promotional emails about our offers and sale.</p>\r\n    </td>\r\n</tr>',
                'tag_desc' => NULL,
                'created_at' =>'2019-01-06 07:30:00',
                'updated_at' => '2019-01-06 07:30:00'
            ]
        ]);
    }
}
