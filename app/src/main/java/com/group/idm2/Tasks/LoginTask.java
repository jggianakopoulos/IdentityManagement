package com.group.idm2.Tasks;

import android.content.Context;


import com.group.idm2.Tasks.AbstractTask;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;


public class LoginTask extends AbstractTask {

    public LoginTask(Context context, String email, String password) {
        super(context, email, password);
        this.actionWord = "login";
        this.script = "login";
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.getSHA(this.password))
                .build();
    }
}
