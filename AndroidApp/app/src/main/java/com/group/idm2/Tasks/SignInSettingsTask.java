package com.group.idm2.Tasks;

import android.content.Context;
import android.graphics.Bitmap;

public class SignInSettingsTask extends AbstractTask {
    public SignInSettingsTask(Context context, String email, String password, Bitmap image) {
        super(context, email, password);
        this.script = "signinsettings";
        this.actionWord = "settings update";
    }
}
