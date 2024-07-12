<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse:collapse">
    <tbody>
        <tr>
            <td>
                <div
                    style="background-color:#fff;padding-left:30px;padding-right:10px;padding-top:20px;padding-bottom:0px;color:#021744;margin-top:5px;border-radius:10px 10px 0 0">
                    <div style="padding-bottom:20px;font-size:20px;font-weight:bold">
                        {{ trans('mail.forgot_password.title') }}
                        <span
                            style="display:block;overflow:hidden;width:514px;height:1px;border-radius:5px;background-color:#707070;margin-top:15px;opacity:0.16"></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div
                    style="padding-top:20px;padding-bottom:25px;padding-left:30px;padding-right:30px;border-radius:0px 0px 10px 10px;border-top:none;background:#fff">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="border-collapse:collapse;font-size:14px">
                        <tbody>
                            <tr>
                                <td style="padding-top:5px;padding-bottom:15px"> {{ trans('mail.greet') }} <span
                                        style="color:#021744;font-weight:bold;font-size:16px">{{ $username }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:5px;padding-bottom:5px">
                                    {{ trans('mail.forgot_password.content') }}
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding-top:10px;padding-bottom:10px">
                                    <a href="{{ $verifyCodeUrl }}"
                                        style="text-align:center;padding:0 20px;line-height:50px;overflow:hidden;border-radius:5px;text-decoration:none;background-color:#952ee7;color:#fff;font-size:14px;margin-bottom:10px;display:inline-block;font-weight:bold"
                                        target="_blank">
                                        {{ trans('mail.forgot_password.button') }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding-top:25px;padding-bottom:5px"> {{ trans('mail.regards') }} </td>
                            </tr>
                            <tr>
                                <td style="padding-top:5px;padding-bottom:5px">
                                    <span style="color:#4837d0"></span> {{ trans('mail.team') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding-top:25px;padding-bottom:5px;font-weight:normal;color:#8c9dbb">
                Copyright © 2024
            </td>
        </tr>
    </tbody>
</table>
