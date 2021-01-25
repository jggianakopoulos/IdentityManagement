package com.group.idm2;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

public class ProfileActivity extends AppCompatActivity {
    private EditText emailET, passwordET, confirmpasswordET, nameET;
    Button loginButton, registerButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);
        emailET = (EditText)findViewById(R.id.emailET);
        passwordET = (EditText)findViewById(R.id.passwordET);
        confirmpasswordET = (EditText)findViewById(R.id.confirmpasswordET);
        nameET = (EditText)findViewById(R.id.nameET);
    }

    public void updateProfile() {
        System.out.println("update");
    }

    public void register(View view) {
        String email = emailET.getText().toString();
        String password = passwordET.getText().toString();
        String confirm_password = confirmpasswordET.getText().toString();
        String name = nameET.getText().toString();

        new LoginTask(this, "register", email, password, confirm_password, name).execute("register", email, password,confirm_password, name);
    }

}
