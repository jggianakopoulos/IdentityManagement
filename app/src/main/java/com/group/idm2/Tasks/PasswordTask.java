package com.group.idm2.Tasks;

import android.content.Context;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;

public class PasswordTask extends AbstractTask {
    private String new_password, confirm_password;

    public PasswordTask(Context context, String email, String old_password, String new_password, String confirm_password) {
        super(context, email, old_password);
        this.new_password = new_password;
        this.confirm_password = confirm_password;
        this.actionWord = "password update";
        this.script = "passwordchange";
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.getSHA(this.password))
                .addFormDataPart("new_password", this.getSHA(this.new_password))
                .addFormDataPart("confirm_password", this.getSHA(this.confirm_password))
                .build();
    }
}