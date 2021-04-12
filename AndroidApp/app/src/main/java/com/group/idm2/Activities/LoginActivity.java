package com.group.idm2.Activities;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.group.idm2.Tasks.LoginTask;
import com.group.idm2.R;

import androidx.appcompat.widget.Toolbar;
import androidx.drawerlayout.widget.DrawerLayout;

public class LoginActivity extends AbstractActivity {


    private EditText emailET, passwordET;
    private SharedPreferences sharedPreferences;
    Button loginButton, registerButton;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        emailET = (EditText)findViewById(R.id.emailET);
        passwordET = (EditText)findViewById(R.id.passwordET);
    }


    public void login(View view) {
        String email = emailET.getText().toString().trim();
        String password = passwordET.getText().toString().trim();

        if (invalidEmail(email)) {
            showToast(this,"Error: Invalid Email.");
            return;
        }

        if (invalidPassword(password)) {
            showToast(this,"Error: Invalid Password.");
            return;
        }
        new LoginTask(this, email, password).execute();
    }

    public void register(View view) {
        Intent send = new Intent(this, RegisterActivity.class);
        this.startActivity(send);
    }

}