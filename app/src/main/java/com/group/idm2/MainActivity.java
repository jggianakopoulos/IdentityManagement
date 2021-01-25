package com.group.idm2;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {

    private EditText emailET, passwordET;
    Button loginButton, registerButton;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        emailET = (EditText)findViewById(R.id.emailET);
        passwordET = (EditText)findViewById(R.id.passwordET);
    }


    public void login(View view) {
        String email = emailET.getText().toString();
        String password = passwordET.getText().toString();
        new LoginTask(this, "login", email, password, "", "").execute(email, password);
    }

    public void register(View view) {
        Intent send = new Intent(this, ProfileActivity.class);
        this.startActivity(send);
    }

}