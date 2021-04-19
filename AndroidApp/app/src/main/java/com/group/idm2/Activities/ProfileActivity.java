package com.group.idm2.Activities;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import com.group.idm2.R;
import com.group.idm2.Tasks.ProfileTask;

public class ProfileActivity extends DrawerActivity {

    private EditText emailET, passwordET, firstNameET, lastNameET;
    private SharedPreferences sharedPreferences;

    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_profile);
        header = "Update Your Profile";
        emailET = (EditText)findViewById(R.id.emailET);
        passwordET = (EditText)findViewById(R.id.passwordET);
        firstNameET = (EditText)findViewById(R.id.firstNameET);
        lastNameET = (EditText)findViewById(R.id.lastNameET);

        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);

        emailET.setText(sharedPreferences.getString("email", ""));
        firstNameET.setText(sharedPreferences.getString("first_name", ""));
        lastNameET.setText(sharedPreferences.getString("last_name", ""));
        super.onCreate(savedInstanceState);
    }

    public void update(View view) {
        String email = emailET.getText().toString().trim();
        String password = passwordET.getText().toString().trim();
        String first_name = firstNameET.getText().toString().trim();
        String last_name = lastNameET.getText().toString().trim();

        new ProfileTask(this, email, password, first_name, last_name).execute();
    }
}
