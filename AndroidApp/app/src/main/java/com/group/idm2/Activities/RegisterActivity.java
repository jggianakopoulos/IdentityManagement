package com.group.idm2.Activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import com.group.idm2.R;
import com.group.idm2.Tasks.RegisterTask;

public class RegisterActivity extends AbstractActivity {
    private EditText emailET, passwordET, confirmPasswordET, firstNameET, lastNameET, phoneNumberET;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        emailET = (EditText)findViewById(R.id.emailET);
        passwordET = (EditText)findViewById(R.id.passwordET);
        confirmPasswordET = (EditText)findViewById(R.id.confirmPasswordET);
        firstNameET = (EditText)findViewById(R.id.firstNameET);
        lastNameET = (EditText)findViewById(R.id.lastNameET);
        phoneNumberET = (EditText)findViewById(R.id.phoneNumberET);
    }

    public void register(View view) {
        String email = emailET.getText().toString();
        String password = passwordET.getText().toString();
        String confirm_password = confirmPasswordET.getText().toString();
        String first_name = firstNameET.getText().toString();
        String last_name = lastNameET.getText().toString();
        String phone_number = phoneNumberET.getText().toString();


        if (invalidEmail(email)) {
            showToast(this, "Error: That email is not valid");
            return;
        }

        if (!password.equals(confirm_password)) {
            showToast(this, "Error: Your passwords do not match");
            return;
        }

        if (invalidPassword(password)) {
            showToast(this, "Error: Your password must be at least 8 characters");
            return;
        }

        new RegisterTask(this, email, password, confirm_password, first_name, last_name, phone_number).execute();
    }

    public void login(View view) {
        Intent send = new Intent(this, LoginActivity.class);
        this.startActivity(send);
    }

}