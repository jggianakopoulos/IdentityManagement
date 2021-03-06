package com.group.idm2.Activities;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import com.group.idm2.R;
import com.group.idm2.Tasks.ProfileTask;

public class ProfileActivity extends AbstractActivity {

    private EditText emailET, passwordET, firstNameET, lastNameET, phoneNumberET;
    private SharedPreferences sharedPreferences;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);

        emailET = (EditText)findViewById(R.id.emailET);
        passwordET = (EditText)findViewById(R.id.passwordET);
        firstNameET = (EditText)findViewById(R.id.firstNameET);
        lastNameET = (EditText)findViewById(R.id.lastNameET);
        phoneNumberET = (EditText)findViewById(R.id.phoneNumberET);

        SharedPreferences sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);

        emailET.setText(sharedPreferences.getString("email", ""));
        firstNameET.setText(sharedPreferences.getString("first_name", ""));
        lastNameET.setText(sharedPreferences.getString("last_name", ""));
        phoneNumberET.setText(sharedPreferences.getString("phone_number", ""));
    }

    public void update(View view) {
        String email = emailET.getText().toString().trim();
        String password = passwordET.getText().toString().trim();
        String first_name = firstNameET.getText().toString().trim();
        String last_name = lastNameET.getText().toString().trim();
        String phone_number = phoneNumberET.getText().toString().trim();

        new ProfileTask(this, email, password, first_name, last_name, phone_number).execute();
    }
}
