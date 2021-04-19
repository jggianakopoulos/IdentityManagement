package com.group.idm2.Tasks;

import android.content.Context;
import android.content.SharedPreferences;

import com.group.idm2.Tasks.AbstractTask;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;

public class ProfileTask extends AbstractTask {
    private String first_name, last_name;

    public ProfileTask(Context context, String email, String password, String first_name, String last_name) {
        super(context, email, password);
        this.actionWord = "profile update";
        this.script = "profileupdate";
        this.first_name = first_name;
        this.last_name = last_name;
    }

    public RequestBody getRequestBody() {
        return new MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("old_email", this.sharedPreferences.getString("email", ""))
                .addFormDataPart("email", this.email)
                .addFormDataPart("password", this.getSHA(this.password))
                .addFormDataPart("first_name", this.first_name)
                .addFormDataPart("last_name", this.last_name)
                .build();
    }
}
