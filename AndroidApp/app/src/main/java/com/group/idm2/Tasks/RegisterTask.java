package com.group.idm2.Tasks;

import android.content.Context;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;

public class RegisterTask extends AbstractTask {
    private String first_name, last_name, confirm_password;

    public RegisterTask(Context context, String email, String password, String confirm_password, String first_name, String last_name) {
        super(context, email, password);
        this.confirm_password = confirm_password;
        this.actionWord = "registration";
        this.script = "register";
        this.first_name = first_name;
        this.last_name = last_name;
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("first_name", this.first_name)
                .addFormDataPart("last_name", this.last_name)
                .addFormDataPart("password", this.getSHA(this.password))
                .addFormDataPart("confirm_password", this.getSHA(this.confirm_password))
                .build();
    }

}
