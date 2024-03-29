package com.group.idm2.Activities;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;

import com.group.idm2.R;
import com.group.idm2.Tasks.LoginTask;
import com.group.idm2.Tasks.SignInMethodsTask;

public class SignInMethodsActivity extends DrawerActivity {
    private CheckBox usePassword, useFace, useCode;
    SharedPreferences sharedPreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        this.header = "Sign-In Methods";
        setContentView(R.layout.activity_signinmethods);
        super.onCreate(savedInstanceState);
        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);
        usePassword = (CheckBox)findViewById(R.id.usePassword);
        useFace = (CheckBox)findViewById(R.id.useFace);
        useCode = (CheckBox)findViewById(R.id.useCode);
        System.out.println("Start of preferences");
        System.out.println((sharedPreferences.getString("use_password", "0")));
        System.out.println((sharedPreferences.getString("use_face", "0")));
        System.out.println((sharedPreferences.getString("use_code", "0")));

        usePassword.setChecked(sharedPreferences.getString("use_password", "0").equals("1"));
        useFace.setChecked(sharedPreferences.getString("use_face", "0").equals("1"));
        useCode.setChecked(sharedPreferences.getString("use_code", "0").equals("1"));
    }


    public void save_methods(View view) {
        String passwordVal = (usePassword.isChecked()) ? "1" : "0";
        String faceVal = (useFace.isChecked()) ? "1" : "0";
        String codeVal = (useCode.isChecked()) ? "1" : "0";

        new SignInMethodsTask(this, sharedPreferences.getString("email", ""), sharedPreferences.getString("password", ""), passwordVal, faceVal, codeVal).execute();
    }
}
