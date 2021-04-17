package com.group.idm2.Tasks;

import android.content.Context;
import android.graphics.Bitmap;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;

public class SignInMethodsTask extends AbstractTask {
    private String use_password, use_face, use_code;
    public SignInMethodsTask(Context context, String email, String password, String use_password, String use_face, String use_code) {
        super(context, email, password);
        this.script = "signinmethods";
        this.actionWord = "settings update";
        this.use_password = use_password;
        this.use_face = use_face;
        this.use_code = use_code;
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.password)
                .addFormDataPart("use_password", this.use_password)
                .addFormDataPart("use_face", this.use_face)
                .addFormDataPart("use_code", this.use_code)
                .build();
    }
}
